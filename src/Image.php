<?php

namespace AmirRezaM75\ImageOptimizer;

use AmirRezaM75\ImageOptimizer\Converters\Webm;
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

        return $this;
    }

    public function convert(Converter $converter)
    {
        $converter->setImagePath($this->path());

        $process = Process::fromShellCommandline($converter->getCommand());
        $process->run();

        return $converter->getOutputPath();
    }

    /**
     * To install FFmpeg with support for libvpx-vp9,
     * look at the Compilation Guides and compile FFmpeg with the --enable-libvpx option.
     * @see https://trac.ffmpeg.org/wiki/Encode/VP9
     */
    public function convertToWebm()
    {
        // ['-r 16', '-c:v libvpx', '-vf fps="fps=8"', '-auto-alt-ref 0']
        return $this->convert(new Webm([
            '-qmin 10',
            '-qmax 40',
            '-c vp9',
            '-b:v 0',
            '-crf 40',
            '-vf fps="fps=16"',
            '-an'
        ]));
    }

    private function getOptimizer()
    {
        switch ($this->extension()) {
            case 'gif':
                return new Gifsicle([
                    '-b',
                    '-O3',
                    '-k=150',
                    '--lossy=100',
                    '--color-method=median-cut'
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
