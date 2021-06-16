<?php


namespace AmirRezaM75\ImageOptimizer\Optimizers;


use AmirRezaM75\ImageOptimizer\Image;

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
}
