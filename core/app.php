<?php


namespace app\core;

use app\core\base\Asset;
use app\core\interfaces\AssetInterface;
use app\core\interfaces\ControllerInterface;
use Exception;

class app
{
    public static $config;
    public static $title;

    /** @var AssetInterface */
    public static $assets;

    public function run(array $config)
    {
        $this->classesAutoloadRegister();

        static::$config = $config;
        static::$title = $config['app_name'];
        static::$assets = new Asset();

        $defaultAction = $config['default_action'];
        $defaultController = $config['default_controller'];

        $requestUri = trim($_SERVER['REQUEST_URI'], '/');
        $uriFragments = explode('/', $requestUri);

        $controllerCandidate = $uriFragments[0] ?: $defaultController;
        $actionCandidate = $uriFragments[1] ?: $defaultAction;

        // автоопределение контроллера и экшена если не найдено других роутов
        static::$config['routes'][$requestUri] = "$controllerCandidate/$actionCandidate";

        /** @var ControllerInterface $controllerObject */
        $controllerObject = $currentAction = null;

        foreach (static::$config['routes'] as $uriPattern => $path)
        {
            if (preg_match("~$uriPattern~", $path))
            {
                list($controller, $action) = explode('/', $path);
                $controllerName = ucfirst($controller) . "Controller";
                $actionName = 'action' . ucfirst($action);

                $controllerFile = ROOT_DIR . 'controllers/' . $controllerName . '.php';

                if (file_exists($controllerFile))
                {
                    include_once $controllerFile;
                    $fullControllerName = 'app\\controllers\\' . $controllerName;
                    $controllerObject = new $fullControllerName;

                    $is_app_controller = $controllerObject instanceof ControllerInterface;
                    $has_action_method = method_exists($controllerObject, $actionName);

                    if ($is_app_controller && $has_action_method)
                    {
                        $currentAction = $actionName;
                        break;
                    }
                }
            }
        }

        if ($controllerObject && $currentAction)
        {
            $content = $controllerObject->$currentAction();

            $layout = $controllerObject->layout
            ? (string) $controllerObject->layout
            : static::$config['default_layout'];

            if (!is_string($layout))
            {
                throw new Exception('layout must be string, "' . gettype($controllerObject->layout) . '" given');
            }

            $layoutFilename = ROOT_DIR . 'views/layouts/' . $layout . '.php';

            /**
             * если layout найден, то задачу отображения контента возлагаем на него
             * в противном случае, просто рендерим контент
             */
            if (file_exists($layoutFilename))
            {
                include_once $layoutFilename;
            } else {
                echo $content;
            }
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }

    private function classesAutoloadRegister()
    {
        spl_autoload_register(function ($className)
        {
            $classPath = explode("\\", $className);
            $is_app_class = $classPath[0] === 'app';

            if ($is_app_class)
            {
                $classPath[0] = trim(static::$config['ROOT_DIR'], DIRECTORY_SEPARATOR);

                $classFilename = implode(DIRECTORY_SEPARATOR, $classPath) . '.php';
                if (file_exists($classFilename)) {
                    include_once $classFilename;
                }
            }
        });
    }

    public static function m (string $file, string $m, array $vars = []) : string
    {
        $langFile = static::$config['ROOT_DIR'] . 'messages/' . static::$config['lang'] . '/' . $file . '.php';
        if (file_exists($langFile))
        {
            $lang = require $langFile;
            if (count($vars))
            {
                foreach ($vars as $key => $value)
                {
                    $lang[$m] = str_replace("#$key#", $value, $lang[$m]);
                }
            }
            return $lang[$m];
        }
        return $m;
    }
}
