<?php

namespace app\core\interfaces;

interface ControllerInterface
{
    /**
     * @param string $view view name
     * @param array $data variables that will be available in the view
     * @return string view content
     */
    public function render(string $view, array $data = []) : string;
}
