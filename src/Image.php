<?php

namespace AmirRezaM75\ImageOptimizer;

use InvalidArgumentException;

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
}
