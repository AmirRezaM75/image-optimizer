<?php


namespace AmirRezaM75\ImageOptimizer\Tests;


use AmirRezaM75\ImageOptimizer\OptimizerChain;
use AmirRezaM75\ImageOptimizer\Optimizers\Gifsicle;

class OptimizerChainTest extends TestCase
{
    private $optimizerChain;

    protected function setUp(): void
    {
        parent::setUp();

        $this->optimizerChain = new OptimizerChain;

        $this->emptyTempFolder();
    }

    /** @test */
    public function it_can_get_all_optimizers()
    {
        $this->assertEquals([], $this->optimizerChain->getOptimizers());

        $this->optimizerChain->addOptimizer(new Gifsicle);

        $this->assertInstanceOf(Gifsicle::class, $this->optimizerChain->getOptimizers()[0]);
    }

    /** @test */
    public function it_can_optimize_a_gif()
    {
        $modifiedFilePath = $this->getTempPath('animated.gif');
        $originalFilePath = $this->getMediaPath('animated.gif');

        $optimizer = new Gifsicle([
            '-b',
            '-O3',
            '--lossy=100',
            '-k=64'
        ]);

        $this->optimizerChain
            ->addOptimizer($optimizer)
            ->optimize($modifiedFilePath);

        $this->assertFileExists($modifiedFilePath);
        $this->assertFileExists($originalFilePath);
        $this->assertTrue( filesize($modifiedFilePath) < filesize($originalFilePath) );
    }

    /** @test */
    public function it_wont_touch_unsupported_files()
    {
        $modifiedFilePath = $this->getTempPath('image.jpg');
        $originalFilePath = $this->getMediaPath('image.jpg');

        $this->optimizerChain
            ->addOptimizer(new Gifsicle)
            ->optimize($modifiedFilePath);

        $this->assertEquals( file_get_contents($modifiedFilePath), file_get_contents($originalFilePath) );
    }
}
