<?php

namespace Db;

use PDO;

/**
 * Class Connection
 * @package Db
 */
class Connection
{
    // todo в constants.php
    const CONF_PATH = '/etc/honey-flow/common.php';

    /**
     * @var PDO
     */
    private static $connection;

    /**
     * Установка соединения с БД
     */
    private static function init()
    {
        $confFile = require(self::CONF_PATH);
        $conf = $confFile['db'];

        self::$connection = new PDO(
            "mysql:host={$conf['host']};dbname={$conf['dbname']}",
            $conf['user'],
            $conf['password']
        );
    }

    /**
     * @return PDO
     */
    public static function get() : PDO
    {
        if (!self::$connection) {
            self::init();
        }

        return self::$connection;
    }
}