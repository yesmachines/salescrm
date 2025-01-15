<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use Storage;
use File;

/**
 * This is a trait use full to store image either in s3 or in local storage
 *
 * @author Shainu
 * Usage : $resizedImage = $this->multiImage($request->file('avatar'), 'avatar', 'multi_');
 */
trait ImageTraits {

    private function singleImage($image, $path, $fileName = null) {
        $imageName = $fileName . rand(1, time()) . '.' . $image->getClientOriginalExtension();

        try {
            if (!env('CDN_ENABLED', false)) {
                $image = $image->getRealPath();
                $path = $path . '/';
                $disk = 'public';
            } else {
                $path = env('CDN_FILE_DIR', 'dev/yescrm/') . $path . '/';
                $disk = 's3';
            }
            $img = Image::make($image);
            Storage::disk($disk)->put($path . $imageName, $img->stream()->detach(), 'public');

            return $imageName;
        } catch (Exception $e) {
            return false;
        }
    }

    private function blobImage($image, $path, $fileName) {
        $imageName = $fileName . rand(1, time()) . '.jpg';

        try {
            if (!env('CDN_ENABLED', false)) {
                $image = $image->getRealPath();
                $path = $path . '/';
                $disk = 'public';
            } else {
                $path = env('CDN_FILE_DIR', 'dev/arn/') . $path . '/';
                $disk = 's3';
            }
            $img = Image::make($image);
            Storage::disk($disk)->put($path . $imageName, $img->stream()->detach(), 'public');

            return $imageName;
        } catch (Exception $e) {
            return false;
        }
    }

    private function copyImageFromUrl($url, $path, $fileName) {
        $expectedExts = array('png', 'jpg', 'jpeg', 'gif');
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        if (!in_array($extension, $expectedExts)) {
            $extension = 'png';
        }
        $imageName = $fileName . rand(1, time()) . '.' . $extension;
        try {
            if (!env('CDN_ENABLED', false)) {
                $path = $path . '/';
                $disk = 'public';
            } else {
                $path = env('CDN_FILE_DIR', 'dev/arn/') . $path . '/';
                $disk = 's3';
            }
            $img = Image::make($url);
            Storage::disk($disk)->put($path . $imageName, $img->stream()->detach(), 'public');

            return $imageName;
        } catch (Exception $e) {
            return false;
        }
    }

    private function uploadAnyFile($file, $path, $prefix = '') {

        if ($prefix) {
            $fName = $prefix . rand(1, time()) . '.' . $file->getClientOriginalExtension();
        } else {
            $fName = rand(1, time()) . '.' . $file->getClientOriginalExtension();
        }

        try {
            $file = $file->getRealPath();
            if (!env('CDN_ENABLED', false)) {
                $path = $path . '/';
                $disk = 'public';
            } else {
                $path = env('CDN_FILE_DIR', 'dev/arn/') . $path . '/';
                $disk = 's3';
            }
            Storage::disk($disk)->put($path . $fName, file_get_contents($file), 'public');
            return $fName;
        } catch (\Exception $e) {
            //dd($e);
            return false;
        }
    }
}
