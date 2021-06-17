## Installation
```bash
composer require amirrezam75/image-optimizer
```
## Usage

```php
(new OptimizerChain)
    ->addOptimizer(new Gifsicle([
        '-b',
        '-O3',
        '--lossy=100',
        '-k=64'
    ]))
    ->optimize($inputPath, $outputPath);
```

## Optimization tools

### Gifsicle
In order to compress gif files, you need to install [Gifsicle](https://github.com/kohler/gifsicle) v1.92+

#### [Options](http://www.lcdf.org/gifsicle/man.html)
``--batch, -b`` Modify each GIF input in place by reading and writing to the same filename. (GIFs read from the standard input are written to the standard output.)

``-O[level], --optimize[=level]``
Optimize output GIF animations for space. Level determines how much optimization is done; higher levels take longer, but may have better results.

``--lossy[=lossiness]``
Alter image colors to shrink output file size at the cost of artifacts and noise. Lossiness determines how many artifacts are allowed; higher values can result in smaller file sizes, but cause more artifacts. The default lossiness is 20.

``-k[=num], --colors[=num]``
Reduce the number of distinct colors in each output GIF to num or less. Num must be between 2 and 256. This can be used to shrink output GIFs or eliminate any local color tables.

#### Installation
``./bootstrap.sh`` or ``autoreconf -i``

``make install``
