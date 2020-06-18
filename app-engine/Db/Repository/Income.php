<?php

namespace Db\Repository;

use Exception;

/**
 * Class Income
 * @package Db\Repository
 */
class Income extends Base {

    protected function setTable() {
        $this->table = 'income';
    }

    /**
     * @param array $sourcesIds
     * @param int $dashboardId
     * @return array
     * @throws Exception
     */
    public function getIncomesNumBySources(array $sourcesIds, int $dashboardId) : array {

        $sourcesIdsStr = implode(', ', $sourcesIds);

        $sql = "
                SELECT source_id, COUNT(id) FROM income
                WHERE source_id IN (" . $sourcesIdsStr . ")
                AND dashboard_id = " . $dashboardId . "
                AND is_deleted = 0
                GROUP BY source_id
            ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_GROUP);
    }
}