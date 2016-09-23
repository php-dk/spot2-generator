<?php
namespace Spot2Generator;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Spot2Generator\core\db\Connect;
use Spot2Generator\core\exceptions\Exception;
use Spot2Generator\core\Model;
use Spot2Generator\models\Entity;

/**
 * Class Generator
 *
 *
 * @package Spot2Generator
 */
class Generator
{
    protected $path = '';
    protected $params = [];

    protected function getDb(): Connection
    {
        return Connect::getInstance();
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }


    public function varExport(array $var): string
    {
        return var_export($var, true);
    }

    public function basePath(string $path)
    {
        $this->path = $path;
    }

    protected function save(Model $model)
    {
        $fileName = $this->path . '/' .
            str_replace('\\', '/', $model->getClassNamespace()) .
            '/' . $model->getClassName() .
            '.php';

        @mkdir(dirname($fileName), 0777, true);

        if (!$f = fopen($fileName, 'w')) {
            throw new Exception("Не удалось создать файл $fileName");
        }

        fwrite($f, $model->toString());
        @fclose($f);
    }

    public function entity(string $tableName, string $className): bool
    {
        if (preg_match('/(\*)/', $tableName)) {
            $tables = $this->getDb()->getSchemaManager()->listTableNames();

            foreach ($tables as $table) {
                if (preg_match("/^($tableName)/", $table)) {
                    $this->entity($table, $className);
                }
            }

            return true;
        }

        $entity = new Entity($tableName, $className);
        $entity->setParams($this->params);

        $this->save($entity);

        return true;
    }

    public function help()
    {
        return <<<STR
---- Генератор моделей для Spot2 ---- 
    spot2-gen --table=<table> --class=Class
    spot2-gen --table=<table>
    spot2-gen --table=<table>_*

- полный список возможностей смотри в README.md компонента

STR;

    }


}