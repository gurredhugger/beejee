<?php
define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);

require_once ROOT_DIR . 'vendor/autoload.php';
require_once ROOT_DIR . 'core/app.php';

use app\core\app;

$config = require ROOT_DIR . 'config/general.php';
(new app())->run($config);
