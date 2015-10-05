<?php

namespace spartaksun\addresses\components;


use spartaksun\addresses\AddressBookException;

/**
 * Class Db singleton
 * @package spartaksun\addresses\components
 */
class Db
{

    /**
     * Employee table name
     */
    const TABLE_EMPLOYEE = 'employee';
    /**
     * Admin table name
     */
    const TABLE_USER = 'user';
    /**
     * @var \PDO
     */
    private static $_pdo;
    /**
     * @var Db
     */
    private static $_instance;


    private function __construct()
    {
    }

    /**
     * Singleton instance of this class
     * @return Db
     * @throws AddressBookException
     */
    public static function getInstance()
    {
        if (is_null(self::$_pdo)) {
            throw new AddressBookException('Database not configured');
        }

        if (is_null(self::$_instance)) {
            self::$_instance = new Db();
        }

        return self::$_instance;
    }

    public static function configure($params)
    {
        if (empty($params['dsn']) || empty($params['username']) || !isset($params['password'])) {
            throw new AddressBookException('Incorrect set of DB parameters');
        }
        try {
            self::$_pdo = new \PDO($params['dsn'], $params['username'], $params['password']);
        } catch (\PDOException $e) {
            throw new AddressBookException('Caught PDO exception: ' . $e->getMessage());
        }
    }

    /**
     * Delete row by ID
     *
     * @param $tableName
     * @param $id
     * @return bool
     * @throws AddressBookException
     */
    public function deleteById($tableName, $id)
    {
        $tableName = $this->checkAndQuote($tableName);
        $sth = self::$_pdo->prepare("DELETE FROM  {$tableName} WHERE id=?;");
        $rows = $sth->execute(array($id));

        return $rows;
    }

    /**
     * Select rows
     *
     * @param $tableName
     * @param array $where
     * @param string $select
     * @return array
     * @throws AddressBookException
     */
    public function selectAll($tableName, array $where = array(), $select = "*")
    {
        $fields = array('1');

        foreach ($where as $fieldName => $value) {
            $fields[] = $this->checkAndQuote($fieldName) . "=?";
        }
        if (is_array($select)) {
            $quotedSelect = array();
            foreach ($select as $selectFieldName) {
                $quotedSelect[] = $this->checkAndQuote($selectFieldName);
            }
            $selectStr = implode(",", $quotedSelect);
        } else {
            $selectStr = '*';
        }
        $whereStr = implode(" AND ", $fields);
        $tableName = $this->checkAndQuote($tableName);
        $sql = "SELECT {$selectStr} FROM  {$tableName} WHERE {$whereStr};";

        $statement = self::$_pdo->prepare($sql);
        $statement->execute(array_values($where));
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * Select one row
     *
     * @param $tableName
     * @param array $where
     * @param string $select
     * @return array
     */
    public function select($tableName, array $where = array(), $select = "*")
    {
        $result = $this->selectAll($tableName, $where, $select);
        if (count($result) == 1) {
            return array_shift($result);
        }

        return $result;
    }

    /**
     * Insert one row
     *
     * @param $tableName - name of table
     * @param array $params attribute-value pairs
     * @return bool|string last insert ID
     *
     * @throws AddressBookException
     */
    public function insert($tableName, array $params)
    {
        try {

            $fields = array();
            foreach ($params as $fieldName => $value) {
                $fields[] = $this->checkAndQuote($fieldName);
            }

            $fieldStr = implode(",", $fields);
            $stakeHoldersStr = implode(",", array_fill(0, count($params), "?"));

            $success = self::$_pdo
                ->prepare("INSERT INTO {$this->checkAndQuote($tableName)}  ({$fieldStr}) VALUES ({$stakeHoldersStr});")
                ->execute(array_values($params));

            if ($success) {
                return self::$_pdo->lastInsertId();
            }

            return false;

        } catch (\PDOException $e) {
            throw new AddressBookException('Caught PDO exception: ' . $e->getMessage());
        }
    }

    /**
     * Check if attribute name does not have not allowed symbols and
     * quotes its value
     *
     * @param $fieldName
     * @return string
     *
     * @throws AddressBookException
     */
    private function checkAndQuote($fieldName)
    {
        if (preg_match('/[^_a-zA-Z0-9]/', $fieldName)) {
            throw new AddressBookException('Incorrect attribute name: ' . $fieldName);
        }

        return "`" . $fieldName . "`";
    }

    /**
     * Avoid cloning
     */
    private function __clone()
    {
    }

}