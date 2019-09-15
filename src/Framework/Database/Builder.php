<?php


namespace WebRover\Framework\Database;


class Builder
{
    use ConnectionTrait;

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->doctrine = $connection->getDoctrine();
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    public function table($name, $alias = null)
    {
        $params = $this->connection->getParams();

        $prefix = isset($params['prefix']) ? $params['prefix'] : '';

        $name = $prefix . $name;

        return new QueryBuilder($this->connection, $name, $alias);
    }
}