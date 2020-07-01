<?php

namespace Db\Repository;

use Exception;

/**
 * Class Storage
 * @package Db\Repository
 */
class Storage extends Base
{
    protected function setTable()
    {
        $this->table = 'storage';
    }

    /**
     * @param int $dashboardId
     * @return array
     * @throws Exception
     */
    public function getStorages(int $dashboardId) : array
    {
        $sql = "
            SELECT id, storage_name, amount, currency_digital_code, created
            FROM " . $this->table . "
            WHERE dashboard_id = " . $dashboardId . "
            AND is_deleted = 0
        ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}