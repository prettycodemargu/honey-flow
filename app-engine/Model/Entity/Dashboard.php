<?php

namespace Model\Entity;

use Db\Repository\Dashboard as Repository;

/**
 * Class Dashboard - доска, на которой пользователь видит свой план и расходы
 * @package Model\Entity
 */
class Dashboard extends Base
{
    protected array $fieldsRequired = [
        'dashboard_name' => '',
        'user_id' => '',
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