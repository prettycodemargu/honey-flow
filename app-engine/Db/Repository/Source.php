<?php

namespace Db\Repository;

use Exception;

/**
 * Class Source
 * @package Db\Repository
 */
class Source extends Base
{
    protected function setTable()
    {
        $this->table = 'source';
    }

    /**
     * @param int $dashboardId
     * @return array
     * @throws Exception
     */
    public function getSources(int $dashboardId) : array
    {
        $sql = "
            SELECT id, source_name FROM source
            WHERE dashboard_id = " . $dashboardId . "
            AND is_deleted = 0
            ORDER BY id DESC
        ";

        if (!($query = $this->db->query($sql))) {
            throw new Exception(self::ERROR_SQL_EXECUTE);
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}