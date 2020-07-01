<?php

namespace Model\EntitySelection;

use Db\Repository\Category;
use Db\Repository\Spending;
use Db\Repository\Tranche;

/**
 * Class Categories
 * @package Model\EntitySelection
 */
class Categories extends Base
{

    private Spending $spendingRepository;
    private Tranche $trancheRepository;
    private Category $categoryRepository;

    public function __construct()
    {
        $this->spendingRepository = new Spending();
        $this->trancheRepository = new Tranche();
        $this->categoryRepository = new Category();
    }

    /**
     * @param array $params
     * @return array
     */
    public function get(array $params) : array
    {

        $result = $this->defaultResult;
        $dashboardId = $params['conditions']['id'];

        if (!is_numeric($dashboardId)) {
            $result['status'] = HTTP_BAD_REQUEST;
        }

        try {
            $categories = $this->categoryRepository->getCategories($dashboardId, true);
            $categoriesIds = array_column($categories, 'id');

            $spendings = $this->spendingRepository->getSpendingsNumForCategories($categoriesIds, $dashboardId);
            $tranches = $this->trancheRepository->getTranchesNumByCategories($categoriesIds, $dashboardId);

            foreach ($categories as $key => $category) {
                if (isset($spendings[$category['id']]) || isset($tranches[$category['id']])) {
                    $categories[$key]['in_use'] = true;
                } else {
                    $categories[$key]['in_use'] = false;
                }
            }

            $result['result_data']['categories'] = $categories;
        } catch (\Error $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            return $result;
        } catch (\ Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            return $result;
        }

        return $result;
    }
}