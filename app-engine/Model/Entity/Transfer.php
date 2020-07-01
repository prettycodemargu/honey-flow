<?php

namespace Model\Entity;

use Db\Repository\Transfer as Repository;

/**
 * Class Transfer - перевод денежных средств
 * @package Model\Entity
 */
class Transfer extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'from_storage_id' => '',
        'to_storage_id' => '',
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