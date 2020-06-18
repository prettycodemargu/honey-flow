<?php

namespace Db\Repository;

use Db\Connection;
use Exception;

/**
 * Класс, позволяющий выполнять базовый CRUD для всех сущностей из репозитория
 *
 * Class Base
 * @package Db\Repository
 */
abstract class Base {

    const ERROR_SQL_EXECUTE = 'An error occurred during SQL execution';

    protected string $table;

    protected \PDO $db;

    abstract protected function setTable();

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->setTable();
        $this->db = Connection::get();
    }

    /**
     * @return bool
     */
    public function beginTransaction() : bool {
        return $this->db->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit() : bool {
        return $this->db->commit();
    }

    /**
     * @return bool
     */
    public function rollback() : bool {
        return $this->db->rollBack();
    }

    /**
     * @param array $data
     * @return int
     * @throws Exception
     */
    public function add(array $data) : int {

        foreach($data as $key => $value) {
            $data[$key] = $this->db->quote($value);
        }

        $fieldsStr = implode(',', array_keys($data));
        $valuesStr = implode(',', $data);

        $sql = "INSERT INTO " . $this->table . " ( " . $fieldsStr . ") VALUES (" . $valuesStr . ")";

        if ($query = $this->db->query($sql)) {
            $id = $this->db->lastInsertId();
        } else {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $id;
    }

    /**
     * @param int $id
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function get(int $id = 0, int $limit = 0, int $offset = 0) : array {

        $sql = "SELECT * FROM " . $this->table;
        $sql .= " WHERE";

        if (!empty($id)) {
            $sql .= " id = {$id} AND";
        }

        $sql .= " is_deleted = 0";

        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        if ($offset) {
            $sql .= ' OFFSET ' . $offset;
        }

        if ($query = $this->db->query($sql)) {
            $result = $id ?
                $query->fetch(\PDO::FETCH_ASSOC) :
                $query->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $result;
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     * @throws Exception
     */
    public function edit(int $id, array $data) : bool {

        $valueSet = [];
        foreach ($data as $key => $value)
        {
            $valueSet[] = $key . ' = ' . $this->db->quote($value);
        }

        $values = implode(', ', $valueSet);

        $sql = "UPDATE " . $this->table . " SET " . $values;

        $sql .= " WHERE id=" . $id;

        $query = $this->db->query($sql);

        if (!$query) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return true;
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id) : bool {

        $sql = "UPDATE " . $this->table . " SET is_deleted=1" .
            " WHERE id=" . $id;

        if ($query = (bool)$this->db->query($sql)) {
            $result = true;
        } else {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $result;
    }
}

