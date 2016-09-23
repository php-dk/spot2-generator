<?php

namespace Spot2Generator\core\code;


class Argv
{
    protected $args;

    /**
     * Argv constructor.
     *
     * @param array $argv
     */
    public function __construct(array $argv)
    {
        $this->args = $argv;
    }

    public function getParam(string $name, $defValue = '')
    {
        foreach ($this->args as $arg) {
            if (preg_match('/--(?<param>[a-zA-z]+)=(?<value>.*)/', $arg, $math)) {
                if ($math['param'] == $name) {
                    return $math['value'];
                }
            }
        }

        return $defValue;
    }

    public function getParamList(): array
    {
        $res = [];
        foreach ($this->args as $arg) {
            if (preg_match('/--(?<param>[a-zA-z]+)=(?<value>.*)/', $arg, $math)) {
                $res[$math['param']] = $math['value'];
            }
        }

        return $res;
    }
}