<?php

define('PERFECT_VERSION', '0.1');

require_once('core/perfect.php');

Perfect::init();
Perfect::run();
Perfect::render();
