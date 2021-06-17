<?php


namespace AmirRezaM75\ImageOptimizer\Tests;


use AmirRezaM75\ImageOptimizer\Image;
use InvalidArgumentException;

class ImageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->emptyTempFolder();
    }

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

    /** @test */
    public function it_can_optimize_a_gif()
    {
        $tempFilePath = $this->getTempPath('animated.gif');
        $originalFilePath = $this->getMediaPath('animated.gif');

        (new Image($tempFilePath))->optimize();

        $this->assertFileExists($tempFilePath);
        $this->assertFileExists($originalFilePath);
        $this->assertTrue( filesize($tempFilePath) < filesize($originalFilePath) );
    }

    /** @test */
    public function it_wont_touch_unsupported_files()
    {
        $tempFilePath = $this->getTempPath('image.jpg');
        $originalFilePath = $this->getMediaPath('image.jpg');

        (new Image($tempFilePath))->optimize();

        $this->assertEquals( file_get_contents($tempFilePath), file_get_contents($originalFilePath) );
    }

    /** @test */
    public function it_can_output_to_specified_path()
    {
        $tempFilePath = $this->getTempPath('animated.gif');
        $outputPath = __DIR__ . '/temp/output.gif';

        (new Image($tempFilePath))->optimize($outputPath);

        $this->assertFileEquals($tempFilePath, $this->getMediaPath('animated.gif'));
        $this->assertFileExists($outputPath);
    }

    /** @test */
    public function it_can_convert_gif_to_webm()
    {
        $tempFilePath = $this->getTempPath('animated.gif');

        (new Image($tempFilePath))
            ->optimize()
            ->convertToWebm();
    }
}
