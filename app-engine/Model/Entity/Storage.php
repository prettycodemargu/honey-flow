<?php

namespace Model\Entity;

use Db\Repository\Storage as Repository;

/**
 * Class Storage - хранилища денег
 * @package Model\Entity
 */
class Storage extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'storage_name' => '',
        'amount' => '',
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