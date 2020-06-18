<?php

namespace Model\Entity;

use Db\Repository\Plan as Repository;

/**
 * Class Plan - план расходов
 * @package Model\Entity
 */
class Plan extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'date_start' => '',
        'date_end' => '',
        'sum' => '',
    ];

    protected array $fieldsNonRequired = [
        'currency_digital_code' => self::CURRENCY_DIGITAL_CODE,
        'created' => '',
        'is_deleted' => '',
    ];

    protected function setRepository() {
        $this->repository = new Repository();
    }
}