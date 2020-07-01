<?php

namespace Db\Repository;

use Exception;

/**
 * Class Plan
 * @package Db\Repository
 */
class Plan extends Base {

    protected function setTable() {
        $this->table = 'plan';
    }

    /**
     * @param $date
     * @param $dashboardId
     * @return array
     * @throws Exception
     */
    public function getPlanForDate(string $date, int $dashboardId) : array {
        $sql = "SELECT id, date_start, date_end FROM " . $this->table . "
            WHERE dashboard_id = " . $dashboardId . "
            AND " . $this->db->quote($date) . " > date_start 
            AND " . $this->db->quote($date) . " < date_end
            AND is_deleted = 0";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        $result = $query->fetch(\PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }
}