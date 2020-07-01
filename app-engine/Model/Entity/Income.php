<?php

namespace Model\Entity;

use Db\Repository\Income as Repository;

/**
 * Class Income - income of user money
 * @package Model\Entity
 */
class Income extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'user_id' => '',
        'source_id' => '',
        'storage_id' => '',
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