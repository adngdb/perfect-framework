<?php

class Option
{
    public $doc = '';
    public $value = null;
}

class Config
{
    public function __construct()
    {
        $this->version = new Option();
        $this->version->doc = 'Current version of Perfect Framework';
        $this->version->value = '0.1';

        $this->layout = new Option();
        $this->layout->doc = 'Basic template to render';
        $this->layout->value = '_layout';
    }

    public function get($option, $defaultValue = null)
    {
        if (isset($this->$option) && $this->$option !== null)
        {
            return $this->$option->value;
        }
        return $defaultValue;
    }
}
