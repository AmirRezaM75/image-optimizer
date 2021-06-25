<?php


namespace AmirRezaM75\ImageOptimizer\Tests;


use AmirRezaM75\ImageOptimizer\Image;
use AmirRezaM75\ImageOptimizer\Optimizers\Gifsicle;

class OptimizerTest extends TestCase
{
    /** @test */
    public function it_can_set_options_via_the_constructor()
    {
        $optimizer = (new Gifsicle(['option-1', 'option-2']))->setImagePath('image.gif');

        $this->assertEquals("\"gifsicle\" option-1 option-2 -i 'image.gif' -o 'image.gif'", $optimizer->getCommand());
    }

    /** @test */
    public function it_can_set_binary_path()
    {
        $optimizer = (new Gifsicle())
            ->setBinaryPath('snap/bin')
            ->setImagePath('image.gif');

        $this->assertEquals(
            "\"snap/bin/gifsicle\"  -i 'image.gif' -o 'image.gif'",
            $optimizer->getCommand()
        );

        $optimizer = (new Gifsicle())
            ->setBinaryPath('snap/bin/')
            ->setImagePath('image.gif');

        $this->assertEquals(
            "\"snap/bin/gifsicle\"  -i 'image.gif' -o 'image.gif'",
            $optimizer->getCommand()
        );

        $optimizer = (new Gifsicle())
            ->setBinaryPath('')
            ->setImagePath('image.gif');

        $this->assertEquals(
            "\"gifsicle\"  -i 'image.gif' -o 'image.gif'",
            $optimizer->getCommand()
        );
    }

    /** @test */
    public function it_can_override_options()
    {
        $optimizer = (new Gifsicle(['option-1', 'option-2']))->setImagePath('image.gif');

        $optimizer->setOptions(['option-3', 'option-4']);

        $this->assertEquals("\"gifsicle\" option-3 option-4 -i 'image.gif' -o 'image.gif'", $optimizer->getCommand());
    }

    /** @test */
    public function it_can_detect_handling_capability()
    {
        $gif = new Image($this->getMediaPath('animated.gif'));
        $jpeg = new Image($this->getMediaPath('image.jpg'));

        $gifsicle = new Gifsicle();

        $this->assertTrue($gifsicle->canHandle($gif));
        $this->assertFalse($gifsicle->canHandle($jpeg));
    }

    /** @test */
    public function it_can_detect_last_frame_delay()
    {
        $delay = (new Gifsicle)
            ->setImagePath($this->getMediaPath('animated.gif'))
            ->getLastFrameDelay();

        $this->assertEquals(0.1, $delay);
    }
}
