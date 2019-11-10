<?php

namespace app\core\base;

use app\core\interfaces\AssetInterface;
use Exception;

class Asset implements AssetInterface
{
    private $assets;

    public function add(string $asset, string $position = '') : AssetInterface
    {
        if (preg_match('~\.js$~', $asset)) {
            // скрипты по умолчанию в футере
            $position = $position ?: static::FOOTER;
            $type = 'js';
        } elseif (preg_match('~\.css~', $asset)) {
            // стили по умолчанию в хедере
            $position = $position ?: static::HEADER;
            $type = 'css';
        } else {
            // произвольные строки по-умолчанию в хедере
            $position = $position ?: self::HEADER;
            $type = 'string';
        }

        if (!in_array($position, [self::FOOTER, self::HEADER]))
        {
            $this->invalidPosition();
        }

        $this->assets[$position][$type][] = $asset;
        return $this;
    }

    public function remove(string $asset)
    {
        // TODO: Implement remove() method.
    }

    public function get(string $position)
    {
        if (!in_array($position, [self::FOOTER, self::HEADER]))
        {
            $this->invalidPosition();
        }
        $strings = $css = $scripts = '';

        $cssCollection = $this->assets[$position]['css'];
        if (is_array($cssCollection) && count($cssCollection))
        {
            foreach ($cssCollection as $style)
            {
                $css .= "<link rel='stylesheet' href='$style'>\n";
            }
        }
        $jsCollection = $this->assets[$position]['js'];
        if (is_array($jsCollection) && count($jsCollection))
        {
            foreach ($jsCollection as $script) {
                $scripts .= "<script src='$script'></script>\n";
            }
        }
        $stringCollection = $this->assets[$position]['string'];
        if (is_array($stringCollection) && count($stringCollection))
        {
            foreach ($stringCollection as $string) {
                $strings .= $string . "\n";
            }
        }

        return $strings.$css.$scripts;
    }

    private function invalidPosition ()
    {
        throw new Exception('position must be ' . implode('or ', [self::FOOTER, self::HEADER]));
    }
}
