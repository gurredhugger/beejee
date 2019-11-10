<?php


namespace app\core\base;


use app\core\app;
use app\core\interfaces\ControllerInterface;
use Exception;

abstract class Controller implements ControllerInterface
{
    public $layout;
    /**
     * @param string $view view name
     * @param array $data variables that will be available in the view
     * @return string view content
     * @throws Exception
     */
    public function render(string $view, array $data = []) : string
    {
        if (!is_string($view))
        {
            throw new Exception('view must be string, "' . gettype($view) . '" given');
        }

        $rootDir = app::$config['ROOT_DIR'];
        $controllerCode = preg_replace("~Controller~", "", array_pop(explode('\\', static::class)));
        $viewFile = $rootDir . 'views/' . strtolower($controllerCode) . '/' . $view . '.php';
        if (file_exists($viewFile))
        {
            $data = array_merge($data, ['controller' => &$this]);
            extract($data);
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
        }

        return $content ?? '';
    }
}
