<?php
/**
 * @var $namespace ;
 * @var \Doctrine\DBAL\Schema\Column[] $columns ;
 * @var array $fields ;
 * @var $className ;
 * @var $tableName ;
 * @var array $relations ;
 * @var array $property ;
 * @var string $extendEntity ;
 * @var bool $mapper ;
 * @var string $extendMapper ;
 */
?>
<?= "<?php \n" ?>

<?php if (!empty($namespace)) { ?>
namespace <?= $namespace ?>;
<?php } ?>

use Spot\EntityInterface;
use Spot\MapperInterface;
<?php if ($mapper) { ?>
use <?= $extendMapper ?> as BaseMapper;
<?php } ?>
use <?=$extendEntity?> as Entity;

/**
 * Class <?= $className ?>

 *
<?php foreach ($property as $name => list($type, $comment)) {
    echo " * @property {$type} \$$name" .
        ($comment? ' - ' . $comment : '')
        . "\n";
} ?>
 *
<?php foreach ($relations as $name => list($class, $primaryKey)) {
    echo " * @property {$class} \$$name \n";
} ?>
 *
 *
 */
class <?=$className?> extends Entity
{
    protected static $table = '<?=$tableName?>';

<?php if ($mapper) { ?>
    protected static $mapper = <?= $className ?>Mapper::class;
<?php } ?>

    public static function fields()
    {
        return [
<?php foreach ($fields as $name => $params) {
                 $names = [];
                 foreach ($params as $key => $value) {
                     if (is_string($value)) {
                         $value = "'$value'";
                     }else if (is_bool($value)) {
                         $value = $value ? 'true' : 'false';
                     }

                     $names[] = "'$key' => $value";
                 }

                 echo  "            '$name' => [". implode(', ', $names) ."], \n";

             } ?>
        ];
    }

    public static function relations(MapperInterface $mapper, EntityInterface $entity)
    {
<?php if ($relations) { ?>
        return [
<?php foreach ($relations as $name=> list($relationClassName, $primaryKey)) {
 echo "            '$name' => \$mapper->belongsTo(\$entity, $relationClassName::class, '$primaryKey'), \n";
            } ?>
        ];
<?php } else { ?>
        return [];
<?php } ?>
    }
}

<?php if ($mapper) { ?>
/**
* Class <?= $className ?>Mapper
*
<?php if (!empty($namespace)) { ?>
* @package <?= $namespace ?>
<?php } ?>
*
* @method <?= $className ?> first
*/
class <?= $className ?>Mapper extends BaseMapper {
    //@todo - code
}
<?php } ?>





