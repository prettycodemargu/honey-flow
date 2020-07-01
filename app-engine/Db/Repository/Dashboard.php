<?php

namespace Db\Repository;

/**
 * Class Dashboard
 * @package Db\Repository
 */
class Dashboard extends Base
{
    protected function setTable() {
        $this->table = 'dashboard';
    }
}