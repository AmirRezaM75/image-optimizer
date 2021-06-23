<?php

namespace AmirRezaM75\ImageOptimizer;

use AmirRezaM75\ImageOptimizer\Converters\Webm;
use AmirRezaM75\ImageOptimizer\Optimizers\Gifsicle;
use InvalidArgumentException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Image
{
    protected $imagePath;
    private $lastFrameDelay = 1.0;

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

        if ($optimizer instanceof Gifsicle) {
            $infoCommand = Process::fromShellCommandline('"gifsicle" --info -i ' . $imagePath);
            $infoCommand->run();

            preg_match_all(
                '/disposal asis delay ([0-9.]+)/',
                $infoCommand->getOutput(),
                $matches
            );

            if ( is_array($matches[1]) and ! empty($matches[1]) )
                $this->lastFrameDelay = floatval(end($matches[1]));
        }


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
     * libvpx-vp9 shorthand is vp9
     * @see https://trac.ffmpeg.org/wiki/Encode/VP9
     * Disposal asis delay on last frame issue
     * @see https://trac.ffmpeg.org/ticket/6302
     * @see https://trac.ffmpeg.org/ticket/3052
     */
    public function convertToWebm()
    {
        // ['-r 16', '-vf fps="fps=8"', '-auto-alt-ref 0']
        // ['-crf 50', '-b:v 0']
        $webmOptions = $this->lastFrameDelay > 1
            ? ['-c:v libvpx-vp9', '-vsync cfr', '-qmin 30', '-qmax 50', '-an']
            : ['-c:v libvpx-vp9', '-an'];

        return $this->convert(new Webm($webmOptions));
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
