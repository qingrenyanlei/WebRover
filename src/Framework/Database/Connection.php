<?php


namespace WebRover\Framework\Database;


use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class Connection
{
    use ConnectionTrait;

    private $params;

    public function __construct(array $params = [], Configuration $configuration = null, EventManager $eventManager = null)
    {
        $configuration = $configuration ?: new Configuration();

        $eventManager = $eventManager ?: new EventManager();

        $this->doctrine = DriverManager::getConnection($params, $configuration, $eventManager);

        $this->params = $params;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    public function getParams()
    {
        return $this->params;
    }
}