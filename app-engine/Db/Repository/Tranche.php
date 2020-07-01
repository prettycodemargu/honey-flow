<?php

namespace Db\Repository;

use Exception;

/**
 * Class Tranche
 * @package Db\Repository
 */
class Tranche extends Base
{
    private string $categoryTable = 'category';

    protected function setTable()
    {
        $this->table = 'tranche';
    }

    /**
     * @param int $planId
     * @return array
     * @throws Exception
     */
    public function getTranches(int $planId) : array
    {
        $sql = "SELECT 
            t.id,
            t.category_id as category_id, 
            c.category_name as category_name,
            t.amount as amount, 
            t.currency_digital_code as currency_digital_code,
            t.created as created
        FROM " . $this->table . " t
        LEFT JOIN " . $this->categoryTable . " c
        ON t.category_id = c.id
        WHERE t.plan_id = " . $planId . "
        AND t.is_deleted = 0
        ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC) ?? [];
    }

    /**
     * @param array $categoriesIds
     * @param int $dashboardId
     * @return array
     * @throws Exception
     */
    public function getTranchesNumByCategories(array $categoriesIds, int $dashboardId) : array
    {
        $categoriesIdsStr = implode(', ', $categoriesIds);

        $sql = "
                SELECT category_id, COUNT(id) FROM " . $this->table . "  
                WHERE category_id IN (" . $categoriesIdsStr . ")
                AND dashboard_id = " . $dashboardId . "
                AND is_deleted = 0      
                GROUP BY category_id  
            ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_COLUMN | \PDO::FETCH_GROUP);
    }
}