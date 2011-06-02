<?php

require_once(APPLICATION_ROOT.'core/config.php');
require_once(APPLICATION_ROOT.'core/controller.php');

class Perfect
{
    public static $config = null;
    public static $controller = null;
    public static $contentForLayout = '';

    private static $page = array(
        'controller_name' => '',
        'controller_class' => '',
        'method_name' => '',
    );

    public static function init()
    {
        // Create the default configuration
        self::$config = new Config();

        // Register the autoloader function
        spl_autoload_register(array('Perfect', 'autoload'));
    }

    public static function run()
    {
        list($controller, $method, $params) = self::parseQuery();
        var_dump($controller);
        var_dump($method);
        var_dump($params);

        // Instanciate the controller, execute the method
        self::$controller = new $controller();
        call_user_func_array(array(self::$controller, $method), $params);
    }

    public static function render($__vars = array())
    {
        self::$contentForLayout = self::renderView(
            APPLICATION_ROOT . 'application/views/' . self::$page['controller_name'] . '/' . self::$page['method_name'] . '.phtml',
            self::$controller->getVars()
        );

        extract($__vars);
        ob_start();
        include('application/views/' . self::$config->get('layout') . '.phtml');
        echo ob_get_clean();
    }

    private static function renderView($__view, $__vars = array())
    {
        extract($__vars);
        ob_start();
        include($__view);
        return ob_get_clean();
    }

    /**
     * Parse the query string into a controller name, a method name and parameters for the method.
     */
    private static function parseQuery()
    {
        $controller = '';
        $method = '';
        $params = array();

        if (!isset($_GET['perfect']) || empty($_GET['perfect']))
        {
            $controller = self::$config->get('defaultController', 'home');
            $method = self::$config->get('defaultMethod', 'index');
        }
        else
        {
            $query = $_GET['perfect'];
            $parts = explode('/', $query);

            $size = count($parts);

            switch ($size)
            {
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
        }

        self::$page['controller_name'] = $controller;
        self::$page['method_name'] = $method;

        // Turn the controller name into a class name
        $words = explode('-', $controller);
        $controller = '';
        foreach ($words as $word)
        {
            $controller .= ucfirst($word);
        }
        $controller .= 'Controller';

        self::$page['controller_class'] = $controller;

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
