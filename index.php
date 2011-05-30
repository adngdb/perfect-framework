<?php

define('PERFECT_VERSION', '0.1');

define('APPLICATION_ROOT', realpath( dirname( __FILE__ ) ) . '/');

require_once(APPLICATION_ROOT.'core/perfect.php');

Perfect::init();
Perfect::run();
Perfect::render();
