<?php


namespace AmirRezaM75\ImageOptimizer\Optimizers;


use AmirRezaM75\ImageOptimizer\Optimizer;

abstract class BaseOptimizer implements Optimizer
{
    protected $options = [];

    protected $imagePath = '';

    protected $binaryPath = '';

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    public function setOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    public function setImagePath($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function setBinaryPath($binaryPath)
    {
        if ( ! empty($binaryPath) and substr($binaryPath, -1) !== DIRECTORY_SEPARATOR )
            $binaryPath = $binaryPath . DIRECTORY_SEPARATOR;

        $this->binaryPath = $binaryPath;

        return $this;
    }
}
