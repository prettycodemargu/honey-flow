<?php

namespace Model\Entity;

use Db\Repository\User as Repository;

/**
 * Class User
 * @package Model\Entity
 */
class User extends Base
{
    protected array $fieldsRequired = [
        'user_name' => '',
        'email' => '',
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