<?php

namespace Model\EntitySelection;

use Db\Repository\Plan as PlanRepository;
use \Db\Repository\Category;
use \Db\Repository\Spending;
use \Db\Repository\Storage;
use \Db\Repository\Tranche;
use Exception;

/**
 * Class Dashboard
 * @package Model\EntitySelection
 */
class Dashboard extends Base
{
    private PlanRepository $planRepository;
    private Tranche $trancheRepository;
    private Storage $storageRepository;
    private Category $categoryRepository;
    private Spending $spendingRepository;

    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        $this->planRepository = new PlanRepository();
        $this->trancheRepository = new Tranche();
        $this->storageRepository = new Storage();
        $this->categoryRepository = new Category();
        $this->spendingRepository = new Spending();
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
            /**
             * возможно 2 режима:
             * траты по плану
             * и свободное фиксирование трат
             */
            $plan = $this->planRepository->getPlanForDate(date('Y-m-d H:i:s'), $dashboardId);

            $tranches = $plan ? $this->getTranches($plan['id']) : [];
            $dateStart = $plan['date_start'] ?? $this->getDateStart();
            $dateEnd = $plan['date_end'] ?? $this->getDateEnd();

            $spendingsByPeriod = $this->getSpendingsByPeriod($dashboardId, $dateStart, $dateEnd);
            $spendingsGroups = $tranches ?
                $this->prepareGroups($tranches, $spendingsByPeriod) :
                $this->prepareGroupsByCategories($dashboardId, $spendingsByPeriod);

            $result['result_data'] = [
                'plan' => $plan,
                'spendings_groups' => $spendingsGroups,
                'tranches' => $tranches,
                'storages' => $this->getStorages($dashboardId),
                'currencies' => [643 => 'руб.']
            ];

        } catch (\Error $error) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            return $result;
        } catch (Exception $e) {
            $result['status'] = HTTP_INTERNAL_SERVER_ERROR;
            return $result;
        }

        return $result;
    }

    /**
     * @param int $dashboardId
     * @param string $dateStart
     * @param string $dateEnd
     * @return array
     * @throws Exception
     */
    private function getSpendingsByPeriod(int $dashboardId, string $dateStart, string $dateEnd) : array
    {
        $rawGroups = $this->spendingRepository->getSpendingsGroupsByPeriod($dashboardId, $dateStart, $dateEnd);

        $spendingsGroups = [];
        foreach ($rawGroups as $categoryId => $group) {

            usort($group, function($a, $b){
                if ($a['id'] > $b['id']) {
                    return -1;
                }
                return 1;
            });

            $spendingsGroups[] = [
                'category_id' => $categoryId,
                'category_name' => $group[0]['category_name'],
                'total' => array_sum(array_column($group, 'amount')),
                'rows' => $group
            ];
        }

        return $spendingsGroups;
    }

    /**
     * @param int $planId
     * @return array
     * @throws Exception
     */
    private function getTranches(int $planId) : array
    {
        $tranches = [];
        $tranches['rows'] = $this->trancheRepository->getTranches($planId);
        $tranches['total'] = 0;
        foreach ($tranches['rows'] as $row)
        {
            $tranches['total'] += $row['amount'];
        }
        return $tranches;
    }

    /**
     * @param int $dashboardId
     * @return array
     * @throws Exception
     */
    private function getStorages(int $dashboardId) : array
    {
        $storages = [];
        $storages['rows'] = $this->storageRepository->getStorages($dashboardId);
        $storages['total'] = 0;
        foreach ($storages['rows'] as $row) {
            $storages['total'] += $row['amount'];
        }
        return $storages;
    }

    /**
     * @param array $tranches
     * @param array $spendingsByPeriod
     * @return array
     */
    private function prepareGroups(array $tranches, array $spendingsByPeriod) : array
    {
        $spendingsGroups = $spendingsByPeriod;
        $trancheIds = array_column($tranches['rows'],'id', 'category_id');

        foreach ($spendingsGroups as $key => $value) {
            $spendingsGroups[$key]['tranche_id'] = $trancheIds[$value['category_id']];
        }

        $idsFromSpendings = array_column($spendingsByPeriod, 'category_id');
        foreach ($tranches['rows'] as $tranche) {
            if (!in_array($tranche['category_id'], $idsFromSpendings)) {
                $spendingsGroups[] = [
                    'category_id' => $tranche['category_id'],
                    'tranche_id' => $tranche['id'],
                    'category_name' => $tranche['category_name'],
                    'total' => 0,
                    'rows' => []
                ];
            }
        }

        usort($spendingsGroups, function ($a, $b) {
            if ($a['tranche_id'] < $b['tranche_id']) {
                return -1;
            }
            return 1;
        });

        return $spendingsGroups;
    }

    /**
     * @return string
     */
    private function getDateStart() : string
    {
        return date('Y-m-') . '01';
    }

    /**
     * @return string
     */
    private function getDateEnd() : string
    {
        return date('Y-m-', strtotime('+1 month')) . '01';
    }

    /**
     * @param int $dashboardId
     * @param array $spendingsByPeriod
     * @return array
     * @throws Exception
     */
    private function prepareGroupsByCategories(int $dashboardId, array $spendingsByPeriod) : array
    {
        $categories = $this->categoryRepository->getCategories($dashboardId);

        $groups = [];
        foreach ($spendingsByPeriod as $spending) {
            $groups[$spending['category_id']][] = $spending;
        }

        foreach ($categories as $category) {
            if (empty($groups[$category['id']])) {
                $groups[$category['id']] = [
                    'category_id' => $category['id'],
                    'category_name' => $category['category_name'],
                    'total' => 0,
                    'rows' => []
                ];
            }
        }

        $groups = array_values($groups);

        return $groups;
    }
}