<?php


namespace AmirRezaM75\ImageOptimizer;


use Symfony\Component\Process\Process;

class OptimizerChain
{
    /* @var \AmirRezaM75\ImageOptimizer\Optimizer[] */
    protected $optimizers = [];

    public function getOptimizers(): array
    {
        return $this->optimizers;
    }

    public function addOptimizer(Optimizer $optimizer)
    {
        array_push($this->optimizers, $optimizer);

        return $this;
    }

    public function optimize($imagePath, $outputPath = null)
    {
        if ($outputPath) {
            copy($imagePath, $outputPath);

            $imagePath = $outputPath;
        }

        $image = new Image($imagePath);

        foreach ($this->optimizers as $optimizer) {
            if (! $optimizer->canHandle($image))
                continue;

            $optimizer->setImagePath($image->path());
            $process = Process::fromShellCommandline($optimizer->getCommand());
            $process->run();
        }
    }
}
