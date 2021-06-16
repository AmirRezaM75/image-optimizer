<?php


namespace AmirRezaM75\ImageOptimizer\Tests;


use AmirRezaM75\ImageOptimizer\Image;
use InvalidArgumentException;

class ImageTest extends TestCase
{
    /** @test */
    public function it_throws_exception_if_given_non_existing_file()
    {
        $this->expectException(InvalidArgumentException::class);

        new Image('non existing file');
    }

    /** @test */
    public function it_can_get_the_mime_type()
    {
        $image = new Image($this->getMediaPath('animated.gif'));

        $this->assertEquals('image/gif', $image->mime());
    }

    /** @test */
    public function it_can_get_the_path()
    {
        $path = $this->getMediaPath('animated.gif');

        $image = new Image($path);

        $this->assertEquals($path, $image->path());
    }

    /** @test */
    public function it_can_get_the_extension()
    {
        $image = new Image($this->getMediaPath('animated.gif'));

        $this->assertEquals('gif', $image->extension());
    }
}
