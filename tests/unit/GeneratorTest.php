<?php
declare(strict_types = 1);

namespace Spot2Generator\tests\unit;

use Doctrine\DBAL\Schema\Column;
use Spot2Generator\core\db\Connect;
use Spot2Generator\Generator;
use Spot2Generator\models\Entity;
use Spot2Generator\tests\TestCase;

class GeneratorTest extends TestCase
{
    public function exec($sql)
    {
        Connect::getInstance()->exec($sql);
    }

    public function setUp()
    {
        try {
            Connect::getInstance()->exec("CREATE TABLE table_s1 (id INT PRIMARY KEY NOT NULL, name TEXT)");
            Connect::getInstance()->exec("CREATE TABLE table_s2 (id INT, table1_id INT, name TEXT)");
            Connect::getInstance()->exec("ALTER TABLE table_s2 ADD FOREIGN KEY(table1_id) REFERENCES table_s1(id)");
        } catch (\Exception $ex) {
        }
    }

    public function tearDown()
    {
        try {
            $this->exec("DROP TABLE table_s1");
            $this->exec("DROP TABLE table_s2");
        } catch (\Exception $ex) {
        }

    }

    public function testInit()
    {
        $columns = Connect::getInstance()->getSchemaManager()->listTableColumns('table_s1');
        static::assertCount(2, $columns);
        static::assertTrue(isset($columns['id']));
        static::assertTrue(isset($columns['name']));
        static::assertTrue($columns['id'] instanceof Column);
        static::assertTrue($columns['name'] instanceof Column);
    }


    public function testDefTableName()
    {
        $entity = new Entity('table_s1');
        static::assertEquals('TableS1', $entity->getClassName());

        $entity = new Entity('scheme.table_s1');
        static::assertEquals('TableS1', $entity->getClassName());
        static::assertEquals('scheme', $entity->getSchemeName());

        $entity = new Entity('scheme.table_s1', 'foo1\subNamespace\*');
        static::assertEquals('TableS1', $entity->getClassName());
        static::assertEquals('scheme', $entity->getSchemeName());

        static::assertEquals('foo1\subNamespace', $entity->getClassNamespace());
    }

    public function testMaskClassName()
    {
        $entity = new Entity('scheme.table_s1', 'foo1\subNamespace\*');

        static::assertEquals('TableS1', $entity->getClassName());
        static::assertEquals('foo1\subNamespace', $entity->getClassNamespace());
    }

    public function testNamespaceClass()
    {
        $entity = new Entity('table_s1', 'foo1\\Class1');
        static::assertEquals('Class1', $entity->getClassName());
        static::assertEquals('foo1', $entity->getClassNamespace());

        $entity = new Entity('table_s1', 'foo1\\subNamespace\\Class1');
        static::assertEquals('Class1', $entity->getClassName());
        static::assertEquals('foo1\\subNamespace', $entity->getClassNamespace());
    }



}
