<?php

namespace Db\Repository;

/**
 * Class Transfer
 * @package Db\Repository
 */
class Transfer extends Base
{
    protected function setTable()
    {
        $this->table = 'transfer';
    }
}