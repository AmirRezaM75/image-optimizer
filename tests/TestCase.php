<?php

namespace AmirRezaM75\ImageOptimizer\Tests;

use PHPUnit\Framework\TestCase as BaseTest;

class TestCase extends BaseTest
{
    public function getMediaPath($filename)
    {
        return __DIR__ . "/media/{$filename}";
    }
}
