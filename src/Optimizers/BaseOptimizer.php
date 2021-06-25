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

    /**
     * Use it when you wanna call binary file which is located somewhere else.
     * Like snap packages which are located at '/usr/snap' instead of '/usr/bin'
     * @param string $binaryPath
     * @return self
    */
    public function setBinaryPath($binaryPath)
    {
        if ( ! empty($binaryPath) and substr($binaryPath, -1) !== DIRECTORY_SEPARATOR )
            $binaryPath = $binaryPath . DIRECTORY_SEPARATOR;

        $this->binaryPath = $binaryPath;

        return $this;
    }
}
