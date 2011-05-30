<?php

require_once(APPLICATION_ROOT.'core/config.php');
require_once(APPLICATION_ROOT.'core/controller.php');

class Perfect
{
    public static $config = null;

    public static function init()
    {
        self::$config = new Config();

        spl_autoload_register(array('Perfect', 'autoload'));
    }

    public static function run()
    {
        list($controller, $method, $params) = self::parseQuery();
        var_dump($controller);
        var_dump($method);
        var_dump($params);
    }

    public static function render()
    {
        $layout = self::$config->get('layout');
        ob_start();
        include('application/views/' . $layout . '.phtml');
        echo ob_get_clean();
    }

    /**
     * Parse the query string into a controller name, a method name and parameters for the method.
     */
    private static function parseQuery()
    {
        $controller = '';
        $method = '';
        $params = array();

        $params = $_GET;
        unset($params['perfect']);

        $query = $_GET['perfect'];
        $parts = explode('/', $query);

        $size = count($parts);

        switch ($size)
        {
            case 0:
                $controller = self::$config->get('defaultController', 'home');
                $method = self::$config->get('defaultMethod', 'index');
                break;
            case 1:
                $controller = $parts[0];
                $method = self::$config->get('defaultMethod', 'index');
                break;
            case 2:
                $controller = $parts[0];
                $method = $parts[1];
                break;
            default:
                $controller = $parts[0];
                $method = $parts[1];
                unset($parts[0]);
                unset($parts[1]);
                $params['perfect'] = $parts;
        }

        return array($controller, $method, $params);
    }

    public static function autoload($class)
    {
        if (class_exists($class, false))
            return true;

        $paths = array(
            'Controller' => 'application/controllers/',
            'Model' => 'application/models/',
            'Helper' => 'core/helpers/',
        );

        foreach ($paths as $suffix => $path)
        {
            if (self::strEndsWith($class, $suffix))
            {
                $pathToFile = APPLICATION_ROOT.$path;
                $pathToFile .= strtolower(strstr($class, $suffix, true));
                $pathToFile .= '.php';

                if (file_exists($pathToFile))
                {
                    include($pathToFile);
                    return true;
                }
            }
        }

        return false;
    }

    private static function strEndsWith($string, $test) {
        $strlen = strlen($string);
        $testlen = strlen($test);
        if ($testlen > $strlen) return false;
        return substr_compare($string, $test, -$testlen) === 0;
    }

}
