<?php

namespace Spot2Generator\core\code;


use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Types\Type;
use Spot2Generator\core\helpers\StringHelpers;
use Spot2Generator\models\Entity;

class Code
{
    /**
     * @var  AbstractSchemaManager
     */
    protected $manager;
    /**
     * @var string
     */
    protected $tableName;
    protected $schemeName;

    public function __construct(AbstractSchemaManager $manager, string $tableName, string $schemeName)
    {
        $this->manager = $manager;
        $this->tableName = $tableName;
        $this->schemeName = $schemeName;
    }

    public function getTableName(): string
    {
        return $this->schemeName ? $this->schemeName . '.' . $this->tableName : $this->tableName;
    }

    /**
     * @return Column[]
     */
    public function getTableColumns(): array
    {
        return $this->manager->listTableColumns($this->getTableName());
    }

    /**
     * @return array
     */
    public function fields(): array
    {
        $list = $this->manager->listTableColumns($this->getTableName());


        $fields = [];
        foreach ($list as $name => $column) {
            $param = [];
            $param['type'] = $column->getType()->getName();
            if ($column->getDefault()) {
                $param['default'] = $column->getDefault();
            }

            if ($column->getAutoincrement()) {
                $param['autoincrement'] = true;
            }

            if ($column->getNotnull()) {
                $param['required'] = true;
            }

            if ($column->getType()->getName() === 'serial') {
                $param['primary'] = true;
                $param['autoincrement'] = true;
            }

            $fields[$name] = $param;
        }

        //set pk
        /** @var Column $primaryKeyColumn */
        $table = $this->manager->listTableDetails($this->getTableName());

        foreach ($table->getPrimaryKeyColumns() as $primaryKeyColumn) {
            $fields[$primaryKeyColumn->getName()]['primary'] = true;
        }
        
        return $fields;
    }


    public function relations(): array
    {
        $list = $this->manager->listTableForeignKeys($this->getTableName());
        $res = [];

        foreach ($list as $foreign) {

            foreach ($foreign->getColumns() as $columnName) {
                $model = new Entity($foreign->getForeignTableName());
                $relation = lcfirst($model->getClassName());

                foreach ($this->fields() as $name => $params) {
                    if ($name == $relation) {
                        $relation = ucfirst($relation);
                        break;
                    }
                }

                $res[$relation] = [$model->getClassName(), $columnName];
            }
        }

        return $res;
    }

    public function getPropertyList(): array
    {
        $list = $this->manager->listTableColumns($this->getTableName());
        $res = [];
        foreach ($list as $name => $column) {

            switch ($column->getType()->getName()) {
                case Type::BIGINT:
                case Type::INTEGER:
                case Type::SMALLINT:
                    $res[$name][] = 'integer';
                    break;
                case Type::TARRAY:
                    $res[$name][] = 'array';
                    break;
                case Type::STRING:
                case Type::TEXT:
                case Type::DATE:
                case Type::DATETIME:
                case Type::DATETIMETZ:
                    $res[$name][] = 'string';
                    break;
                default:
                    $res[$name][] = $column->getType()->getName();

            };

            $res[$name][] = $column->getComment();
        }

        return $res;
    }

}