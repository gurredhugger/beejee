<?php

namespace app\core\base;

use app\core\interfaces\AssetInterface;
use Exception;

class Asset implements AssetInterface
{
    private $assets;

    /**
     * @param string $asset
     * @param array $options ['position' => header/footer, priority => int, tagAttrs => [attr => value]]
     * @return AssetInterface
     * @throws Exception
     */
    public function add(string $asset, array $options = []) : AssetInterface
    {
        $position = $options['position'];
        $priority = $options['priority'] ?: 100;

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

        $this->assets[$position][$type][$priority][] = $asset;
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
            ksort($cssCollection);
            array_walk($cssCollection, function ($priorityCollection) use (&$css)
            {
                foreach ($priorityCollection as $style)
                {
                    $css .= "<link rel='stylesheet' href='$style'>\n";
                }
            });
        }

        $jsCollection = $this->assets[$position]['js'];
        if (is_array($jsCollection) && count($jsCollection))
        {
            ksort($jsCollection);
            array_walk($jsCollection, function ($priorityCollection) use (&$scripts)
            {
                foreach ($priorityCollection as $script)
                {
                    $scripts .= "<script src='$script'></script>\n";
                }
            });
        }

        $stringCollection = $this->assets[$position]['string'];
        if (is_array($stringCollection) && count($stringCollection))
        {
            ksort($stringCollection);
            array_walk($stringCollection, function ($priorityCollection) use (&$strings)
            {
                foreach ($priorityCollection as $string)
                {
                    $strings .= $string . "\n";
                }
            });
        }

        return $strings.$css.$scripts;
    }

    private function invalidPosition ()
    {
        throw new Exception('position must be ' . implode('or ', [self::FOOTER, self::HEADER]));
    }
}
