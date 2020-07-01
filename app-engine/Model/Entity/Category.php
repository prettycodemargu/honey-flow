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

    protected function setRepository()
    {
        $this->repository = new Repository();
    }

    public function add(array $params) : array
    {
        if (($params['conditions']['dashboard_id'] ?? '')) {
            return $this->addCategoryToDashboard($params);
        }

        return parent::add($params);
    }

    public function delete(array $params) : array
    {
        if (($params['conditions']['dashboard_id'] ?? '')) {
            return $this->deleteCategoryFromDashboard($params);
        }

        return parent::delete($params);
    }

    /**
     * @param array $params
     * @return array
     */
    private function addCategoryToDashboard(array $params) : array
    {
        if (empty($params['conditions']['dashboard_id']) || empty($params['data'])) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }
        $dashboardId = $params['conditions']['dashboard_id'];
        $data = $params['data'];

        $result = $this->defaultResult;

        if ($error = $this->validateAndImproveFields($data)) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }

        $this->repository->beginTransaction();
        try {
            $result['result_data']['id'] = $this->repository->addCategoryToDashboard($dashboardId, $data);
            $this->repository->commit();
        } catch (\Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            $this->repository->rollback();
        }

        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    public function deleteCategoryFromDashboard(array $params) : array
    {
        if (empty($params['conditions']['dashboard_id']) || empty($params['conditions']['id'])) {
            $result['status'] = HTTP_BAD_REQUEST;
            return $result;
        }
        $dashboardId = $params['conditions']['dashboard_id'];

        $categoryId = $params['conditions']['id'];

        $result = $this->defaultResult;

        $this->repository->beginTransaction();
        try {
            $result['result_data']['id'] = $this->repository->deleteCategoryFromDashboard($dashboardId, $categoryId);
            $this->repository->commit();
        } catch (\Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            $this->repository->rollback();
        }

        $result['status'] = HTTP_NO_CONTENT;
        return $result;
    }
}