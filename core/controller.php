<?php

class Controller
{
    private $args = array();

    public function set($data)
    {
        $this->args[] = $data;
    }
}
