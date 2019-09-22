<?php


namespace WebRover\Framework\Cache;

use WebRover\Framework\Cache\Proxy\ProxyInterface;
use WebRover\Framework\Database\Manager as DatabaseManager;

/**
 * Class Manager
 * @package WebRover\Framework\Cache
 */
class Manager
{
    /**
     * @var \Psr\SimpleCache\CacheInterface[]
     */
    private $stores = [];

    private $proxy;

    private $params;

    private $databaseManager;

    public function __construct(array $params, DatabaseManager $databaseManager, ProxyInterface $proxy = null)
    {
        $this->params = $params;
        $this->databaseManager = $databaseManager;
        $this->proxy = $proxy;
    }

    /**
     * @return string
     */
    public function getDefaultStoreName()
    {
        return $this->params['default'];
    }

    public function getStore($name = null)
    {
        if (is_null($name)) $name = $this->getDefaultStoreName();

        if (!isset($this->stores[$name])) {
            $this->stores[$name] = $this->newStoreInstance($name);
        }

        $store = $this->stores[$name];

        if (!is_null($this->proxy)) {

            $proxy = $this->proxy;
            $proxy::setStore($store);

            return $proxy;
        }

        return $store;
    }

    private function newStoreInstance($name)
    {
        $storeNamespace = 'WebRover\\Framework\\Cache\\Store\\';

        $storeClass = $storeNamespace . ucfirst(strtolower($name)) . 'Store';
        if (!\class_exists($storeClass)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" dost not exist', $storeClass));
        }

        $store = new $storeClass();

        if (!method_exists($store, 'connect')) {
            throw new \InvalidArgumentException(sprintf('Method initialize dost not exist in class "%s"', $storeClass));
        }

        $stores = $this->params['stores'];

        $params = $stores[$name];

        if ($params['driver'] == 'pdo') {
            $params = [
                'connection' => $this->databaseManager->getConnection($params['connection'])->getDoctrine()
            ];
        }

        $store->connect($params);

        return $store;
    }
}