<?php


namespace AmirRezaM75\ImageOptimizer;


interface Converter
{
    /**
     * Determines if the given image can be handled by the optimizer.
     *
     * @param \AmirRezaM75\ImageOptimizer\Image $image
     *
     * @return bool
     */
    public function canHandle(Image $image) : bool;


    /**
     * Get the command that should be executed
     *
     * @return string
     */
    public function getCommand() : string;

    /**
     * Set the path to the image that should be optimized.
     *
     * @param string $imagePath
     *
     * @return $this
     */
    public function setImagePath(string $imagePath);

    /**
     * Set the options the optimizer should use.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options = []);
}
