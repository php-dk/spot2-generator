<?php
declare(strict_types = 1);

namespace Spot2Generator\core;


use Spot2Generator\core\exceptions\Exception;
use Spot2Generator\core\helpers\StringHelpers;

abstract class Model
{
    /**
     * @var string
     */
    protected $code = '';

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $schemeName = '';

    protected $namespace = '';

    /**
     * @var array
     */
    protected $params = [];

    /**
     * Model constructor.
     *
     * @param string $tableName
     * @param string $className
     */
    final public function __construct(string $tableName, string $className = '')
    {
        if (preg_match('/(?<scheme>[a-z_0-9]+)\.(?<table>[a-z_0-9]+)/', $tableName)) {
            list($this->schemeName, $this->tableName) = explode('.', $tableName);
        } else {
            $this->tableName = $tableName;
        }

        if ($path = explode('\\', $className)) {
            $this->className = array_pop($path);
            if ($this->className == '*') {
                $this->className = '';
            }
            $this->namespace = implode('\\', $path);
        } else {
            $this->className = $className;
        }
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param $name
     * @param string $defValue
     *
     * @return mixed|string
     */
    public function getParam($name, $defValue = '')
    {
        return $this->params[$name] ?? $defValue;
    }


    public function getSchemeName(): string
    {
        return $this->schemeName;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getClassNamespace(): string
    {
        return $this->namespace;
    }

    public function getClassName(): string
    {
        if ($this->className) {
            return $this->className;
        }

        $this->className = StringHelpers::toUpCase($this->tableName);

        return $this->getClassName();
    }

    abstract protected function create(): string;


    public function render(string $name, array $params = []): string
    {
        $ref = new \ReflectionClass(get_called_class());
        $renderFileName = dirname($ref->getFileName()) . '/templates/' . $name . '.php';
        if (!file_exists($renderFileName)) {
            throw new Exception('Не найден шаблон ' . $renderFileName);
        }

        extract($params);
        ob_start();
        include $renderFileName;

        return ob_get_clean();
    }

    final public function toString(): string
    {
        return (string)$this->create();
    }
}