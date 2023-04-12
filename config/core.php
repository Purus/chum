<?php

// define('DS', DIRECTORY_SEPARATOR);

define('CHUM_DIR_ROOT', substr(dirname(__FILE__), 0, -strlen('config')));
define('CHUM_PLUGIN_ROOT', CHUM_DIR_ROOT . DS . 'plugins');
define('CHUM_TEMPLATE_PATH', "template");
define('CHUM_CACHE_PATH', "cache");
define('CHUM_SECURE_HEADERS', true);

define('CHUM_DEV_MODE', true);