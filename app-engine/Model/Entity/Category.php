<?php

namespace Model\Entity;

use Db\Repository\Category as Repository;

/**
 * Class Category - категоризация трат пользователя
 * @package Model\Entity
 */
class Category extends Base
{
    protected array $fieldsRequired = [
        'category_name' => '',
        'description' => '',
    ];

    protected array $fieldsNonRequired = [
        'created' => '',
        'is_moderated' => '',
        'is_deleted' => '',
    ];

    protected function setRepository() {
        $this->repository = new Repository();
    }

    const ERROR_EMPTY_DASHBOARD_ID = "Empty dashboard id";

    /**
     * @param array $params
     * @return array
     */
    public function addCategoryToDashboard(array $params) : array {

        if (empty($params['conditions']['dashboard_id'])) {
            $result['error'] = self::ERROR_EMPTY_DASHBOARD_ID;
            return $result;
        }
        $dashboardId = $params['conditions']['dashboard_id'];

        if (empty($params['data'])) {
            $result['error'] = self::ERROR_EMPTY_SET;
            return $result;
        }
        $data = $params['data'];

        $result = $this->defaultResult;

        if ($result['error'] = $this->validateAndImproveFields($data)) {
            return $result;
        }

        $this->repository->beginTransaction();
        try {
            $result['result_data']['id'] = $this->repository->addCategoryToDashboard($dashboardId, $data);
            $result['success'] = true;
            $this->repository->commit();
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            $this->repository->rollback();
        }

        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    public function deleteCategoryFromDashboard(array $params) : array {

        if (empty($params['conditions']['dashboard_id'])) {
            $result['error'] = self::ERROR_EMPTY_DASHBOARD_ID;
            return $result;
        }
        $dashboardId = $params['conditions']['dashboard_id'];

        if (empty($params['conditions']['id'])) {
            $result['error'] = self::ERROR_EMPTY_ID;
            return $result;
        }
        $categoryId = $params['conditions']['id'];

        $result = $this->defaultResult;

        $this->repository->beginTransaction();
        try {
            $result['result_data']['id'] = $this->repository->deleteCategoryFromDashboard($dashboardId, $categoryId);
            $result['success'] = true;
            $this->repository->commit();
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
            $this->repository->rollback();
        }

        return $result;
    }
}