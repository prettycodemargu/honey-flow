<?php

namespace Model\Entity;

use Db\Repository\Source as Repository;

/**
 * Class Source - источник доходов
 * @package Model\Entity
 */
class Source extends Base
{
    protected array $fieldsRequired = [
        'dashboard_id' => '',
        'source_name' => '',
    ];

    protected array $fieldsNonRequired = [
        'created' => '',
        'is_deleted' => '',
    ];

    protected function setRepository()
    {
        $this->repository = new Repository();
    }
}