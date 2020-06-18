<?php

namespace Db\Repository;

use Exception;

/**
 * Class Category
 * @package Db\Repository
 */
class Category extends Base {

    private string $linkTable = 'link_category_dashboard';

    protected function setTable() {
        $this->table = 'category';
    }

    /**
     * @param int $dashboardId
     * @param bool $orderDesc
     * @return array
     * @throws Exception
     */
    public function getCategories(int $dashboardId, bool $orderDesc = false) : array {

        $sql = "
            SELECT c.id, c.category_name, c.description FROM " . $this->table . " c
            INNER JOIN " . $this->linkTable . " l ON c.id=l.category_id
            WHERE l.dashboard_id = " . $dashboardId . "
            AND c.is_deleted = 0";

        if ($orderDesc) {
            $sql .= " ORDER BY c.id DESC";
        }

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param int $dashboardId
     * @param array $data
     * @return int
     * @throws Exception
     */
    public function addCategoryToDashboard(int $dashboardId, array $data) : int {

        $id = $this->add($data);

        $sql = "INSERT INTO " . $this->linkTable . " (dashboard_id, category_id) 
        VALUES (" . $dashboardId . "," . $id . ")
        ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $id;
    }

    /**
     * @param int $dashboradId
     * @param int $categoryId
     * @return bool
     * @throws Exception
     */
    public function deleteCategoryFromDashboard(int $dashboradId, int $categoryId) : bool {

        $sql = "DELETE FROM " . $this->linkTable . "
            WHERE dashboard_id = " . $dashboradId . "
            AND category_id = " . $categoryId . "
        ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return true;
    }

}