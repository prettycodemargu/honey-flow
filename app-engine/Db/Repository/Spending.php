<?php

namespace Db\Repository;

use Exception;

/**
 * Class Spending
 * @package Db\Repository
 */
class Spending extends Base
{
    protected function setTable()
    {
        $this->table = 'spending';
    }

    /**
     * Получить траты, сгруппированные по категориям
     *
     * @param int $dashboardId
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     * @throws Exception
     */
    public function getSpendingsGroupsByPeriod(int $dashboardId, string $dateStart, string $dateEnd) : array
    {
        $sql = "            
            SELECT
                s.category_id as category_id, 
                s.id as id,
                si.category_name as category_name, 
                s.storage_id as storage_id, 
                s.amount as amount, 
                s.currency_digital_code as currency_digital_code, 
                s.spending_name as spending_name, 
                s.created as created
            FROM spending s
            LEFT JOIN category si ON s.category_id=si.id
            WHERE s.dashboard_id = " . $this->db->quote($dashboardId) . " 
            AND s.created > " . $this->db->quote($dateStart). "
            AND s.created < " . $this->db->quote($dateEnd). "
            AND s.is_deleted = 0
            ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_GROUP);
    }

    /**
     * @param array $categoriesIds
     * @param int $dashboardId
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     * @throws Exception
     */
    public function getSpendingsNumForCategories(array $categoriesIds, int $dashboardId, string $dateStart = '', string $dateEnd = '')
    {
        $categoriesIdsStr = implode(', ', $categoriesIds);

        $sql = "
                SELECT category_id, COUNT(id) FROM " . $this->table . "
                WHERE dashboard_id = " . $dashboardId . "
                AND category_id IN (" . $categoriesIdsStr . ")
                AND is_deleted = 0
            ";

        if ($dateStart && $dateEnd) {
            $sql .= " AND created BETWEEN 
            " . $this->db->quote($dateStart) . " AND " . $this->db->quote($dateEnd);
        }

        $sql .= " GROUP BY category_id";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_GROUP);
    }
}