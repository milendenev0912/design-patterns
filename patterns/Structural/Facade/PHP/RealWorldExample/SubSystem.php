<?php

/*
|--------------------------------------------------------------------------
| Facade Design Pattern - Sub System
|--------------------------------------------------------------------------
| Implement Facade Design Pattern to create objects without specifying
| the exact class of object that will be created.
|--------------------------------------------------------------------------
| @category  Design Pattern
| @package   Facade
| @author    Milen Denev
| @version   1.0.0
| @license   MIT License
| @link      https://github.com/milendenev0912/design-patterns
|--------------------------------------------------------------------------
*/

namespace patterns\Structural\Facade\RealWorldExample;

/**
 * The Facade provides a single method for downloading videos from YouTube. This
 * method hides all the complexity of the PHP network layer, YouTube API and the
 * video conversion library (FFmpeg).
 */
class YouTubeDownloader
{
    protected $youtube;
    protected $ffmpeg;

    /**
     * It is handy when the Facade can manage the lifecycle of the subsystem it
     * uses.
     */
    public function __construct(string $youtubeApiKey)
    {
        $this->youtube = new YouTube($youtubeApiKey);
        $this->ffmpeg = new FFMpeg();
    }

    /**
     * The Facade provides a simple method for downloading video and encoding it
     * to a target format (for the sake of simplicity, the real-world code is
     * commented-out).
     */
    public function downloadVideo(string $url): void
    {
        echo "Fetching video metadata from youtube...\n";
        // $title = $this->youtube->fetchVideo($url)->getTitle();
        echo "Saving video file to a temporary file...\n";
        // $this->youtube->saveAs($url, "video.mpg");

        echo "Processing source video...\n";
        // $video = $this->ffmpeg->open('video.mpg');
        echo "Normalizing and resizing the video to smaller dimensions...\n";
        // $video
        //     ->filters()
        //     ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
        //     ->synchronize();
        echo "Capturing preview image...\n";
        // $video
        //     ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(10))
        //     ->save($title . 'frame.jpg');
        echo "Saving video in target formats...\n";
        // $video
        //     ->save(new FFMpeg\Format\Video\X264(), $title . '.mp4')
        //     ->save(new FFMpeg\Format\Video\WMV(), $title . '.wmv')
        //     ->save(new FFMpeg\Format\Video\WebM(), $title . '.webm');
        echo "Done!\n";
    }
}

/**
 * The YouTube API subsystem.
 */
class YouTube
{
    public function fetchVideo(): string { /* ... */ }

    public function saveAs(string $path): void { /* ... */ }

    // ...more methods and classes...
}

/**
 * The FFmpeg subsystem (a complex video/audio conversion library).
 */
class FFMpeg
{
    public static function create(): FFMpeg { /* ... */ }

    public function open(string $video): void { /* ... */ }

    // ...more methods and classes... RU: ...дополнительные методы и классы...
}

class FFMpegVideo
{
    public function filters(): self { /* ... */ }

    public function resize(): self { /* ... */ }

    public function synchronize(): self { /* ... */ }

    public function frame(): self { /* ... */ }

    public function save(string $path): self { /* ... */ }

    // ...more methods and classes... RU: ...дополнительные методы и классы...
}


/**
 * The client code does not depend on any subsystem's classes. Any changes
 * inside the subsystem's code won't affect the client code. You will only need
 * to update the Facade.
 */
function clientCode(YouTubeDownloader $facade)
{
    // ...

    $facade->downloadVideo("https://www.youtube.com/watch?v=QH2-TGUlwu4");

    // ...
}

$facade = new YouTubeDownloader("APIKEY-XXXXXXXXX");
clientCode($facade);