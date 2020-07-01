<?php

namespace Model\Entity;

use Db\Repository\Tranche as Repository;

/**
 * Class Tranche - часть запланированных расходов по категории
 * @package Model\Entity
 */
class Tranche extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'plan_id' => '',
        'category_id' => '',
        'amount' => '',
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