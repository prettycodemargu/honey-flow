<?php

namespace Model\Entity;

use Db\Repository\Spending as Repository;

/**
 * Class Spending - работа с расходами
 * @package Model\Entity
 */
class Spending extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'category_id' => '',
        'storage_id' => '',
        'amount' => '',
        'spending_name' => '',
    ];

    protected array $fieldsNonRequired = [
        'currency_digital_code' => self::CURRENCY_DIGITAL_CODE,
        'created' => '',
        'is_deleted' => '',
    ];

    protected function setRepository()
    {
        $this->repository = new Repository();
    }
}