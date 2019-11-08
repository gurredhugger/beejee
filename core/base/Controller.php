<?php


namespace app\core\base;


use app\core\app;
use app\core\interfaces\ControllerInterface;
use Exception;

abstract class Controller implements ControllerInterface
{
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
        $viewFile = $rootDir . Controller::class . '/' . $view . '.php';

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
