<?php


namespace WebRover\Framework\Foundation\Session\Storage\Handler;


/**
 * Memcached based session storage handler based on the Memcached class
 * provided by the PHP memcached extension.
 *
 * @see https://php.net/memcached
 *
 * Class MemcachedSessionHandler
 * @package WebRover\Framework\Foundation\Session\Storage\Handler
 */
class MemcachedSessionHandler extends AbstractSessionHandler
{
    private static $defaultClientOptions = [
        'persistent_id' => null,
        'username' => null,
        'password' => null,
        'serializer' => 'php',
    ];

    private $memcached;

    /**
     * @var int Time to live in seconds
     */
    private $ttl;

    /**
     * @var string Key prefix for shared environments
     */
    private $prefix;

    /**
     * Constructor.
     *
     * List of available options:
     *  * prefix: The prefix to use for the memcached keys in order to avoid collision
     *  * expiretime: The time to live in seconds.
     *
     * @throws \InvalidArgumentException When unsupported options are passed
     */
    public function __construct(\Memcached $memcached, array $options = [])
    {
        $this->memcached = $memcached;

        if ($diff = array_diff(array_keys($options), ['prefix', 'expiretime'])) {
            throw new \InvalidArgumentException(sprintf('The following options are not supported "%s"', implode(', ', $diff)));
        }

        $this->ttl = isset($options['expiretime']) ? (int)$options['expiretime'] : 86400;
        $this->prefix = isset($options['prefix']) ? $options['prefix'] : 'wr1s';
    }

    public static function isSupported()
    {
        return \extension_loaded('memcached') && version_compare(phpversion('memcached'), '2.2.0', '>=');
    }

    public static function createConnection($servers, array $options = [])
    {
        if (\is_string($servers)) {
            $servers = [$servers];
        } elseif (!\is_array($servers)) {
            throw new \InvalidArgumentException(sprintf('MemcachedAdapter::createClient() expects array or string as first argument, %s given.', \gettype($servers)));
        }
        if (!static::isSupported()) {
            throw new \Exception('Memcached >= 2.2.0 is required');
        }
        set_error_handler(function ($type, $msg, $file, $line) {
            throw new \ErrorException($msg, 0, $type, $file, $line);
        });
        try {
            $options += static::$defaultClientOptions;
            $client = new \Memcached($options['persistent_id']);
            $username = $options['username'];
            $password = $options['password'];

            // parse any DSN in $servers
            foreach ($servers as $i => $dsn) {
                if (\is_array($dsn)) {
                    continue;
                }
                if (0 !== strpos($dsn, 'memcached://')) {
                    throw new \InvalidArgumentException(sprintf('Invalid Memcached DSN: %s does not start with "memcached://"', $dsn));
                }
                $params = preg_replace_callback('#^memcached://(?:([^@]*+)@)?#', function ($m) use (&$username, &$password) {
                    if (!empty($m[1])) {
                        list($username, $password) = explode(':', $m[1], 2) + [1 => null];
                    }

                    return 'file://';
                }, $dsn);
                if (false === $params = parse_url($params)) {
                    throw new \InvalidArgumentException(sprintf('Invalid Memcached DSN: %s', $dsn));
                }
                if (!isset($params['host']) && !isset($params['path'])) {
                    throw new \InvalidArgumentException(sprintf('Invalid Memcached DSN: %s', $dsn));
                }
                if (isset($params['path']) && preg_match('#/(\d+)$#', $params['path'], $m)) {
                    $params['weight'] = $m[1];
                    $params['path'] = substr($params['path'], 0, -\strlen($m[0]));
                }
                $params += [
                    'host' => isset($params['host']) ? $params['host'] : $params['path'],
                    'port' => isset($params['host']) ? 11211 : null,
                    'weight' => 0,
                ];
                if (isset($params['query'])) {
                    parse_str($params['query'], $query);
                    $params += $query;
                    $options = $query + $options;
                }

                $servers[$i] = [$params['host'], $params['port'], $params['weight']];
            }

            // set client's options
            unset($options['persistent_id'], $options['username'], $options['password'], $options['weight'], $options['lazy']);
            $options = array_change_key_case($options, CASE_UPPER);
            $client->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
            $client->setOption(\Memcached::OPT_NO_BLOCK, true);
            $client->setOption(\Memcached::OPT_TCP_NODELAY, true);
            if (!\array_key_exists('LIBKETAMA_COMPATIBLE', $options) && !\array_key_exists(\Memcached::OPT_LIBKETAMA_COMPATIBLE, $options)) {
                $client->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
            }
            foreach ($options as $name => $value) {
                if (\is_int($name)) {
                    continue;
                }
                if ('HASH' === $name || 'SERIALIZER' === $name || 'DISTRIBUTION' === $name) {
                    $value = \constant('Memcached::' . $name . '_' . strtoupper($value));
                }
                $opt = \constant('Memcached::OPT_' . $name);

                unset($options[$name]);
                $options[$opt] = $value;
            }
            $client->setOptions($options);

            // set client's servers, taking care of persistent connections
            if (!$client->isPristine()) {
                $oldServers = [];
                foreach ($client->getServerList() as $server) {
                    $oldServers[] = [$server['host'], $server['port']];
                }

                $newServers = [];
                foreach ($servers as $server) {
                    if (1 < \count($server)) {
                        $server = array_values($server);
                        unset($server[2]);
                        $server[1] = (int)$server[1];
                    }
                    $newServers[] = $server;
                }

                if ($oldServers !== $newServers) {
                    $client->resetServerList();
                    $client->addServers($servers);
                }
            } else {
                $client->addServers($servers);
            }

            if (null !== $username || null !== $password) {
                if (!method_exists($client, 'setSaslAuthData')) {
                    trigger_error('Missing SASL support: the memcached extension must be compiled with --enable-memcached-sasl.');
                }
                $client->setSaslAuthData($username, $password);
            }

            return $client;
        } finally {
            restore_error_handler();
        }
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->memcached->quit();
    }

    /**
     * {@inheritdoc}
     */
    protected function doRead($sessionId)
    {
        return $this->memcached->get($this->prefix . $sessionId) ?: '';
    }

    /**
     * @return bool
     */
    public function updateTimestamp($sessionId, $data)
    {
        $this->memcached->touch($this->prefix . $sessionId, time() + $this->ttl);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function doWrite($sessionId, $data)
    {
        return $this->memcached->set($this->prefix . $sessionId, $data, time() + $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    protected function doDestroy($sessionId)
    {
        $result = $this->memcached->delete($this->prefix . $sessionId);

        return $result || \Memcached::RES_NOTFOUND == $this->memcached->getResultCode();
    }

    /**
     * @return bool
     */
    public function gc($maxlifetime)
    {
        // not required here because memcached will auto expire the records anyhow.
        return true;
    }

    /**
     * Return a Memcached instance.
     *
     * @return \Memcached
     */
    protected function getMemcached()
    {
        return $this->memcached;
    }
}