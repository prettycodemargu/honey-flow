<?php

namespace Model\EntitySelection;

use Db\Repository\Category;
use Db\Repository\Spending;
use Db\Repository\Tranche;
use Db\Repository\Plan as PlanRepository;
use Exception;

/**
 * Class Plan
 * @package Model\EntitySelection
 */
class Plan extends Base {

    private PlanRepository $planRepository;
    private Tranche $trancheRepository;
    private Spending $spendingRepository;
    private Category $categoryRepository;

    public function __construct() {
        $this->planRepository = new PlanRepository();
        $this->trancheRepository = new Tranche();
        $this->spendingRepository = new Spending();
        $this->categoryRepository = new Category();
    }

    /**
     * @param array $params
     * @return array
     */
    public function getSelection(array $params) : array {
       $planId = $params['conditions']['id'];
       $result = $this->defaultResult;

       try {
           $plan = $this->planRepository->get($planId);
           $tranches = $this->getTranches($plan);
           $dashboardCategories = $this->categoryRepository->getCategories($plan['dashboard_id']);

           $tranchesCategoryIds = array_column($tranches, 'category_id');
           $categoriesAvailable = [];
           foreach ($dashboardCategories as $key => $category) {
               if (!in_array($category['id'], $tranchesCategoryIds)) {
                   $categoriesAvailable[] = $category;
               }
           }

           $result['result_data'] = [
               'plan' => $plan,
               'tranches' => $tranches,
               'categories_available' => $categoriesAvailable
           ];

       } catch (\Error $error) {
           $result['error'] = $error->getMessage();
           return $result;
       } catch (Exception $ex) {
           $result['error'] = $ex->getMessage();
           return $result;
       }

       $result['success'] = true;
       return $result;
    }


    /**
     * @param array $plan
     * @return array
     * @throws Exception
     */
    private function getTranches(array $plan) : array {

        $tranches = $this->trancheRepository->getTranches($plan['id']);

        if (!$tranches) {
            return [];
        }

        $categoriesIds = array_column($tranches, 'category_id');

        $spendings = $this->spendingRepository->getSpendingsNumForCategories(
            $categoriesIds,
            $plan['dashboard_id'],
            $plan['date_start'],
            $plan['date_end']
        );

        foreach ($tranches as $key => $tranche) {
            $tranches[$key]['has_spendings'] = isset($spendings[$tranche['category_id']]) ? 1 : 0;
        }

        return $tranches;
    }
}