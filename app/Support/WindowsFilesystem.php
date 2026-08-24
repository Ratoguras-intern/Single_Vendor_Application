<?php

namespace App\Support;

use Illuminate\Filesystem\Filesystem;

class WindowsFilesystem extends Filesystem
{
    public function replace($path, $content, $mode = null)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        if (! is_null($mode)) {
            @chmod($tempPath, $mode);
        } else {
            @chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            if (@rename($tempPath, $path)) {
                return;
            }

            usleep(100_000);
        }

        @file_put_contents($path, $content);

        @unlink($tempPath);
    }
}
