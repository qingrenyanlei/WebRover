<?php


namespace WebRover\Framework\Support\Connection;


/**
 * Class Memcached
 * @package WebRover\Framework\Support\Connection
 */
class Memcached
{
    private static $defaultClientOptions = [
        'persistent_id' => null,
        'username' => null,
        'password' => null,
        'serializer' => 'php',
    ];

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
}