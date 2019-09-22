<?php


namespace WebRover\Framework\Database\Facade;


use Closure;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use WebRover\Framework\Database\Builder;
use WebRover\Framework\Database\Connection;
use WebRover\Framework\Database\QueryBuilder;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class DB
 * @package WebRover\Framework\Database\Facade
 * @mixin Builder
 * @method Builder connect($params) static
 * @method QueryBuilder table($name, $alias = null) static
 * @method Connection getConnection() static
 * @method Connection getDefaultConnection() static
 * @method string getDefaultConnectionName() static
 * @method string getDatabase() static
 * @method string|null getHost() static
 * @method mixed getPort() static
 * @method string|null getUsername() static
 * @method string|null getPassword() static
 * @method Driver getDriver() static
 * @method Configuration getConfiguration() static
 * @method EventManager getEventManager() static
 * @method AbstractPlatform getDatabasePlatform() static
 * @method void setAutoCommit($autoCommit) static
 * @method void setFetchMode($fetchMode) static
 * @method array fetchAll($sql, array $params = [], $types = []) static
 * @method array fetchAssoc($statement, array $params = [], array $types = []) static
 * @method array fetchArray($statement, array $params = [], array $types = []) static
 * @method mixed fetchColumn($statement, array $params = [], $column = 0, array $types = []) static
 * @method bool isConnected() static
 * @method bool isTransactionActive() static
 * @method int delete($tableExpression, array $identifier, array $types = []) static
 * @method void close() static
 * @method int setTransactionIsolation($level) static
 * @method int getTransactionIsolation() static
 * @method int update($tableExpression, array $data, array $identifier, array $types = []) static
 * @method int insert($tableExpression, array $data, array $types = []) static
 * @method string quoteIdentifier($str) static
 * @method string|null quote($input, $type = null) static
 * @method Driver\Statement prepare($statement) static
 * @method Driver\Statement executeQuery($query, array $params = array(), $types = array(), QueryCacheProfile $qcp = null) static
 * @method Driver\ResultStatement executeCacheQuery($query, $params, $types, QueryCacheProfile $qcp) static
 * @method array project($query, array $params, Closure $function) static
 * @method Driver\Statement query(...$args) static
 * @method int executeUpdate($query, array $params = [], array $types = []) static
 * @method int exec($statement) static
 * @method int getTransactionNestingLevel() static
 * @method int errorCode() static
 * @method array errorInfo() static
 * @method string lastInsertId($seqName = null) static
 * @method void transactional(Closure $func) static
 * @method void setNestTransactionsWithSavepoints($nestTransactionsWithSavepoints) static
 * @method bool getNestTransactionsWithSavepoints() static
 * @method void beginTransaction() static
 * @method void commit() static
 * @method void rollBack() static
 * @method void createSavepoint($savepoint) static
 * @method void releaseSavepoint($savepoint) static
 * @method void rollbackSavepoint($savepoint) static
 * @method Driver\Connection getWrappedConnection() static
 * @method void setRollbackOnly() static
 * @method bool isRollbackOnly() static
 * @method mixed convertToDatabaseValue($value, $type) static
 * @method mixed convertToPHPValue($value, $type) static
 * @method array resolveParams(array $params, array $types) static
 * @method bool ping static
 */
class DB extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'db';
    }
}