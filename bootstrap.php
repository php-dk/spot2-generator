<?php

include "vendor/autoload.php";

\Spot2Generator\core\Config::getInstance()
    ->setEnv($_ENV['CONFIG'] ?? 'dev');