<?php


namespace app\core\interfaces;

interface AssetInterface
{
    const FOOTER = 'footer';
    const HEADER = 'header';

    public function add (string $asset, string $position = '') : AssetInterface;
    public function remove (string $asset);
    public function get(string $position);
}
