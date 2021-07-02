## Installation
```bash
composer require amirrezam75/image-optimizer
```
## Usage

```php
(new Image('animated.gif'))->optimize();
```

You may change output location by changing first argument.

It uses gifsicle as default optimizer; if you need to use another command line utility to optimize your images just write your own optimizer and pass as second argument.

An optimizer is any class that implements the ``AmirRezaM75\ImageOptimizer\Optimizer`` interface.

```php
(new Image('animated.gif'))->optimize('output-path', Optimizer $optimizer);
```

If you wish to convert it to webm:

```php
$outputPath = (new Image('animated.gif'))
    ->optimize()
    ->convertToWebm();
```

It uses **ffmpeg** as default converter. If you need to use another command line utility to convert your file;
create a class that implements ``AmirRezaM75\ImageOptimizer\Converter`` interface.

```php
(new Image('animated.gif'))->convert(Converter $converter);
```

## Optimization tools

### Gifsicle

#### [Options](http://www.lcdf.org/gifsicle/man.html)
``--batch, -b`` Modify each GIF input in place by reading and writing to the same filename. (GIFs read from the standard input are written to the standard output.)

``-O[level], --optimize[=level]``
Optimize output GIF animations for space. Level determines how much optimization is done; higher levels take longer, but may have better results.

``--lossy[=lossiness]``
Alter image colors to shrink output file size at the cost of artifacts and noise. Lossiness determines how many artifacts are allowed; higher values can result in smaller file sizes, but cause more artifacts. The default lossiness is 20.

``-k[=num], --colors[=num]``
Reduce the number of distinct colors in each output GIF to num or less. Num must be between 2 and 256. This can be used to shrink output GIFs or eliminate any local color tables.

``-o [file], --output [file]``
Send output to file. The special filename ‘-’ means the standard output.

``--color-method [method]``
Determine how a smaller colormap is chosen. 
- ‘**diversity**’, the default, is xv(1)’s diversity algorithm, which uses a strict subset of the existing colors and generally produces good results.
- ‘**blend-diversity**’ is a modification of this: some color values are blended from groups of existing colors.
- ‘**median-cut**’ is the median cut algorithm described by Heckbert.

#### Installation
In order to compress gif files, you need to install Gifsicle v1.92+

``git clone https://github.com/kohler/gifsicle``

``apt install automake make gcc build-essential``

``./bootstrap.sh`` or ``autoreconf -i``

``./configure``

``make``

``make install``

## Converter tools

### FFMPEG

#### [Options](https://ffmpeg.org/ffmpeg.html)

``-i`` Specifies the input file

``-r[:stream_specifier]`` Set frame rate (Hz value, fraction or abbreviation).

``-c[:stream_specifier] codec`` or `` -codec``
Select an encoder or a decoder for one or more streams.

**libvpx-vp9** is the VP9 video encoder for WebM

``-crf`` Sets CRF value. Must be from 4-63 in VP8, or 0-63 in VP9. Lower is higher quality. 10 the recommended setting. There are two ways of using CRF.
- To use **constant quality** mode, you MUST use a value of "0" when specifying the video bitrate (-b:v 0). If you just remove the "-b:v" option altogether, ffmpeg will simply fall back on the default bitrate (256K, I think), which will result in a constrained quality encode with extremely poor quality. Constant quality mode tries to achieve a... well... constant level of quality, using whatever bitrate is necessary to achieve that level of quality. This can result in very large file sizes, so is generally not suitable for making webms intended for imageboards which typically have a file size limit of 10MB or less
- To use **constrained quality** mode, you must specify both a CRF value (e.g. -crf 10) AND a video bitrate value (e.g. -b:v 1M). Constrained quality mode will try to achieve a certain level of quality, but without going over a specified bitrate level. When -b:v is used without -crf, the value of -b:v is a target bitrate, but when -b:v and -crf are used together, -b:v becomes a maximum bitrate. This is a way of achieving high quality while still retaining control over the filesize. Ideally it should be used with the 2-pass encoding method.

``-b:v`` is the maximum allowed bitrate. Higher means better quality. Only use this option if you desire a constant bitrate, which will produce a higher quality file. If you are looking for a smaller file size, consider leaving this out

``-an`` Disable audio.

``-qmin, -qmax``: Tells ffmpeg what "quantization parameter" to use when assigning quality.
Don't worry if you don't know what a quantization parameter is, because neither do I.
All I know is that lower numbers = better quality.
I believe the -qmax option prevents the quality from dropping below a certain level for any given frame,
so the overall video quality will be more consistent
(it prevents you from getting certain frames in your video which are of absolutely dreadful quality where everything is blocky as fuck, basically).
> -qmin – the minimum quantizer (default 4, range 0–63)
>
> -qmax – the maximum quantizer (default 63, range qmin–63)

``-vsync parameter`` Video sync method. For compatibility reasons old values can be specified as numbers. Newly added values will have to be specified as strings always.
- **cfr** Frames will be duplicated and dropped to achieve exactly the requested constant frame rate.

> This option is necessary if you wanna fix disposal asis delay on last frame [issue](https://trac.ffmpeg.org/ticket/3052)


#### Installation
Install the latest version of ffmpeg.

``sudo add-apt-repository ppa:savoury1/ffmpeg4``

> We need **v4+** since it uses ``libavcodec 58. 91.100`` encoder, and that's what we need

``sudo apt-get update``

``sudo apt-get install ffmpeg``

``sudo apt-get install libvpx5``

> Duo to file location permission, I recommend to don't install it via snap
