<?php

namespace Model\EntitySelection;

use Db\Repository\Income;
use Db\Repository\Source;

/**
 * Class Sources
 * @package Model\EntitySelection
 */
class Sources extends Base {

    private Source $sourceRepository;
    private Income $incomeRepository;

    /**
     * Sources constructor.
     */
    public function __construct()
    {
        $this->sourceRepository = new Source();
        $this->incomeRepository = new Income();
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
            $sources = $this->sourceRepository->getSources($dashboardId);
            $sourcesIds = array_column($sources, 'id');
            $incomes = $this->incomeRepository->getIncomesNumBySources($sourcesIds, $dashboardId);

            foreach ($sources as $key => $source) {
                $sources[$key]['in_use'] = isset($incomes[$source['id']]);
            }

            $result['result_data']['sources'] = $sources;
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