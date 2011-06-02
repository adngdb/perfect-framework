<?php

class Controller
{
    private $args = array();

    public function set($key, $value = null)
    {
        if (is_array($key))
            $this->args = array_merge($this->args, $key);
        else
            $this->args[$key] = $value;
    }

    public function getVars()
    {
        return $this->args;
    }
}
