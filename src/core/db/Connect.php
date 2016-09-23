<?php

namespace Spot2Generator\core\db;


use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Spot2Generator\core\Config;

class Connect
{
    protected static $connect;

    public static function getInstance(): Connection
    {
        if (!self::$connect) {
            $dns = Config::getInstance()->getParam('db');

            return self::$connect = DriverManager::getConnection($dns, new Configuration());
        }

        return self::$connect;
    }

}