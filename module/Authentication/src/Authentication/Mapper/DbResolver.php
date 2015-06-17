<?php
namespace Authentication\Mapper;

use Zend\Authentication\Adapter\Http\ResolverInterface;
use Zend\Db\Sql\Sql;

class DbResolver implements ResolverInterface
{
    public function __construct($dbAdapter, $tbName, $userColumn, $passwdColumn)
    {
        $this->dbAdapter = $dbAdapter;
        $this->tbName = $tbName;
        $this->userColumn = $userColumn;
        $this->passwdColumn = $passwdColumn;
    }

    public function resolve($username, $realm, $passwd = null)
    {
        if (empty($username)) {
            throw new Exception\InvalidArgumentException('Username is required');
        } elseif (!ctype_print($username) || strpos($username, ':') !== false) {
            throw new Exception\InvalidArgumentException(
                    'Username must consist only of printable characters, excluding the colon'
            );
        }
        if (empty($realm)) {
            throw new Exception\InvalidArgumentException('Realm is required');
        } elseif (!ctype_print($realm) || strpos($realm, ':') !== false) {
            throw new Exception\InvalidArgumentException(
                    'Realm must consist only of printable characters, excluding the colon.'
            );
        }
        // Query db: select passwd from table where '$this->userColumn == $username'
        // realm can be added later depending on its value
        $sql = new Sql($this->dbAdapter, $this->tbName);
        $select = $sql->select();
        $select->columns(array($this->passwdColumn));
        $select->where($this->userColumn. '= "'. $username. '"');

        $adapter = $this->dbAdapter;
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $this->dbAdapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        foreach ($results as $row) {
            return $row['password'];
        }
    }
}