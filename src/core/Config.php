<?php

namespace Spot2Generator\core;


use Spot2Generator\core\exceptions\ConfigException;

class Config
{
    private $file;
    protected $env = 'dev';
    protected $config;

    protected function getFileName(): string
    {
        return __DIR__ . "/../config/config.{$this->env}.php";
    }

    public function setConfig(array $config)
    {
        $this->file = $this->config;
    }

    public function setEnv(string $name)
    {
        $this->env = $name;

        return $this;
    }

    /**
     * Config constructor.
     *
     * @throws \Spot2Generator\core\exceptions\ConfigException
     */
    protected function __construct()
    {
        $fileName = $this->getFileName();
        if (!file_exists($fileName)) {
            throw new ConfigException("Файл с конфигурайцией не найден $fileName");
        }

        $this->file = include $fileName;
    }

    /**
     * @return \Spot2Generator\core\Config
     */
    public static function getInstance(): self
    {
        static $f;

        return $f ?: $f = new static;
    }

    public function getParam($name)
    {
        return $this->file[$name];
    }
}