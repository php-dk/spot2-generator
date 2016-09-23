<?php
namespace Spot2Generator\models;

use Spot2Generator\core\code\Code;
use Spot2Generator\core\db\Connect;
use Spot2Generator\core\Model;

class Entity extends Model
{
    public function create(): string
    {

        $code = new Code(Connect::getInstance()->getSchemaManager(), $this->getTableName(), $this->getSchemeName());

        return $this->render('entity', [
            'extendEntity' => $this->getParam('extendEntity', 'Spot\Entity'),
            'extendMapper' => $this->getParam('extendMapper', 'Spot\Mapper'),
            'mapper' => $this->getParam('isMapper', false),

            'relations' => $code->relations(),
            'columns' => $code->getTableColumns(),
            'fields' => $code->fields(),
            'property' => $code->getPropertyList(),
            'className' => $this->getClassName(),
            'namespace' => $this->getClassNamespace(),
            'tableName' => $this->getSchemeName() ?
                $this->getSchemeName() . '.' . $this->getTableName() :
                $this->getTableName()
            ,
        ]);
    }

}