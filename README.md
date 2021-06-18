## Installation
```bash
composer require amirrezam75/image-optimizer
```
## Usage

```php
(new Image('animated.gif'))->optimize();
```

You may want to change output location:

```php
(new Image('animated.gif'))->optimize('output-path');
```

It uses gifsicle as default optimizer; if you need to use another command line utility to optimize your images just write your own optimizer.
An optimizer is any class that implements the ``AmirRezaM75\ImageOptimizer\Optimizer`` interface.

```php
(new Image('animated.gif'))->optimize(null, Optimizer $optimizer);
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


## Converter tools

### FFMPEG

#### [Options](https://ffmpeg.org/ffmpeg.html)

``-i`` Specifies the input file

``-r[:stream_specifier]`` Set frame rate (Hz value, fraction or abbreviation).

``-c[:stream_specifier] codec (input/output,per-stream)`` or `` -codec``
Select an encoder or a decoder for one or more streams.

**libvpx-vp9** is the VP9 video encoder for WebM

``-crf`` values can go from 4 to 63. Lower values mean better quality.

``-b:v`` is the maximum allowed bitrate. Higher means better quality.

``-an`` disables audio
