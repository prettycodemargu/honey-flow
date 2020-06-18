<?php

namespace Db\Repository;

/**
 * Class User
 * @package Db\Repository
 */
class User extends Base {
    protected function setTable() {
        $this->table = 'user';
    }
}