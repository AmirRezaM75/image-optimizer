<?php

namespace AmirRezaM75\ImageOptimizer\Tests;

use PHPUnit\Framework\TestCase as BaseTest;

class TestCase extends BaseTest
{
    public function emptyTempFolder()
    {
        $tempDirPath = __DIR__ . '/temp';

        $files= scandir($tempDirPath);

        foreach ($files as $file) {
            if ( ! in_array($file, ['.', '..', '.gitignore']))
                unlink("{$tempDirPath}/{$file}");
        }
    }

    public function getMediaPath(string $filename)
    {
        return __DIR__ . "/media/{$filename}";
    }

    public function getTempPath(string $filename)
    {
        $source = $this->getMediaPath($filename);

        $destination = __DIR__ . "/temp/{$filename}";

        copy($source, $destination);

        return $destination;
    }
}
