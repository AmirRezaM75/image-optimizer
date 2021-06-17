<?php

namespace AmirRezaM75\ImageOptimizer;

use AmirRezaM75\ImageOptimizer\Optimizers\Gifsicle;
use InvalidArgumentException;
use Symfony\Component\Process\Process;

class Image
{
    protected $imagePath;

    public function __construct($imagePath)
    {
        if (! file_exists($imagePath))
            throw new InvalidArgumentException("{$imagePath} doesn't exits");

        $this->imagePath = $imagePath;
    }

    public function mime() : string
    {
        return mime_content_type($this->imagePath);
    }

    public function path() : string
    {
        return $this->imagePath;
    }

    public function extension() : string
    {
        $extension = pathinfo($this->imagePath, PATHINFO_EXTENSION);

        return strtolower($extension);
    }

    public function optimize($outputPath = null, Optimizer $optimizer = null)
    {
        $imagePath = $this->getOutputPath($outputPath);

        if (is_null($optimizer))
            $optimizer = $this->getOptimizer();

        if (! $optimizer or ! $optimizer->canHandle($this)) return;

        $optimizer->setImagePath($imagePath);

        $process = Process::fromShellCommandline($optimizer->getCommand());
        $process->run();
    }

    private function getOptimizer()
    {
        switch ($this->extension()) {
            case 'gif':
                return new Gifsicle([
                    '-b',
                    '-O3',
                    '--lossy=100',
                    '-k=64'
                ]);
            default: return false;
        }
    }

    private function getOutputPath($outputPath)
    {
        if (is_null($outputPath))
            return $this->path();

        copy($this->path(), $outputPath);

        return $outputPath;
    }
}
