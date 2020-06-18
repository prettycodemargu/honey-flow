<?php

namespace Model\EntitySelection;

use Db\Repository\Category;
use Db\Repository\Spending;
use Db\Repository\Tranche;

/**
 * Class Categories
 * @package Model\EntitySelection
 */
class Categories extends Base {

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
    public function getSelection(array $params) : array {

        $result = $this->defaultResult;
        $dashboardId = $params['conditions']['id'];

        if (!is_numeric($dashboardId)) {
            $result['error'] = self::ERROR_WRONG_PARAM_TYPE;
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
            $result['error'] = $e->getMessage();
            return $result;
        } catch (\ Exception $e) {
            $result['error'] = $e->getMessage();
            return $result;
        }

        $result['success'] = true;
        return $result;
    }
}