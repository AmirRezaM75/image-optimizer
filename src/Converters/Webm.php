<?php


namespace AmirRezaM75\ImageOptimizer\Converters;


use AmirRezaM75\ImageOptimizer\Image;

class Webm extends BaseConverter
{
    protected $binaryName = 'ffmpeg';

    public function canHandle(Image $image) : bool
    {
        return $image->mime() === 'image/gif';
    }

    public function getCommand() : string
    {
        $optionString = implode(' ', $this->options);

        return "\"{$this->binaryPath}{$this->binaryName}\""
            . ' -i ' . escapeshellarg($this->imagePath)
            . " {$optionString} "
            . escapeshellarg($this->getOutputPath());
    }

    public function getOutputPath()
    {
        return pathinfo($this->imagePath, PATHINFO_DIRNAME)
            . DIRECTORY_SEPARATOR
            . pathinfo($this->imagePath, PATHINFO_FILENAME)
            . '.webm';
    }
}
