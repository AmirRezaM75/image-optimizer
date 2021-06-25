<?php


namespace AmirRezaM75\ImageOptimizer\Optimizers;


use AmirRezaM75\ImageOptimizer\Image;
use Symfony\Component\Process\Process;

class Gifsicle extends BaseOptimizer
{
    protected $binaryName = 'gifsicle';

    public function canHandle(Image $image) : bool
    {
        return $image->mime() === 'image/gif';
    }

    public function getCommand() : string
    {
        $optionString = implode(' ', $this->options);

        return "\"{$this->binaryPath}{$this->binaryName}\" {$optionString}"
            .' -i '.escapeshellarg($this->imagePath)
            .' -o '.escapeshellarg($this->imagePath);
    }

    public function getInfoCommand() : string
    {
        return "\"{$this->binaryPath}{$this->binaryName}\" --info"
            .' -i '.escapeshellarg($this->imagePath);
    }

    public function getLastFrameDelay()
    {
        $infoCommand = Process::fromShellCommandline($this->getInfoCommand());
        $infoCommand->run();

        preg_match_all(
            '/disposal asis delay ([0-9.]+)/',
            $infoCommand->getOutput(),
            $matches
        );

        if ( is_array($matches[1]) and ! empty($matches[1]) )
            return floatval(end($matches[1]));

        return false;
    }
}
