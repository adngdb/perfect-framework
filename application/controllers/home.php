<?php

class HomeController extends Controller
{
    public $routes = array(
        'name' => 'name:str/age:int',
    );

    public function index()
    {
        echo 'hehehe';
        $tt = 34;

        return get_defined_vars();
    }

    public function name($name, $age)
    {
        echo 'My name is ' . $name . ' and I am ' . $age . ' years old.';
    }
}
