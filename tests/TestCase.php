<?php
namespace Spot2Generator\tests;

use PHPUnit_Framework_TestCase;
use Spot2Generator\core\db\Connect;

class TestCase extends PHPUnit_Framework_TestCase
{
    public function exec($sql)
    {
        Connect::getInstance()->exec($sql);
    }

    protected function tearDown()
    {

        parent::tearDown();
    }


}