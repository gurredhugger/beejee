<?php

use app\core\app;

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

require_once ROOT_DIR . 'vendor/autoload.php';

$config = require ROOT_DIR . 'config/general.php';

(new app())->run($config);
