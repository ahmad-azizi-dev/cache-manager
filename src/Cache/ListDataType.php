<?php

namespace App\Cache;


class ListDataType implements \Countable
{
    private array $list;

    public function __construct($list)
    {
        $this->list = $list;
    }

    public function lPush($values)
    {
        $this->list = array_merge($values, $this->list);
    }

    public function lPop()
    {
        return array_shift($this->list) ?? false;
    }

    public function count(): int
    {
        return count($this->list);
    }

}