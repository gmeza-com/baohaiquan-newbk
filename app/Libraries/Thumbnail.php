<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Thumbnail
{
    protected $pathStored;

    protected $prefix = 'thumbnail/images';

    public function hashGenerator($url, $width, $height, $quality, $crop, $watermark = true)
    {
        $url = str_replace(url(''), '', $url);
        $url = str_replace(get_option('site_url'), '', $url);
        $url = str_replace('/storage/', '', $url);
        $url = str_replace('http://', '', $url);
        $url = str_replace('https://', '', $url);
        $rootDir = realpath(config('filesystems.disks.public.root'));

        $data = [
            'file' => $rootDir . '/' . $url,
            'height' => $height,
            'width' => $width,
            'quality' => $quality,
            'crop' => $crop,
            'watermark' => $watermark ?: false,
        ];
        $hash = md5(implode('_',$data));
        $this->imageGenerator($data);
        return url('storage/_thumbnail/' . $hash . '.jpg');
    }

    protected function imageGenerator($data)
    {
        try {
            $hash = md5(implode('_',$data));
            $path = '_thumbnail/' . $hash . '.jpg';
            $rootDir = realpath(config('filesystems.disks.public.root'));
            $data['file'] = urldecode($data['file']);

            if (Storage::disk('public')->exists($path)) {
                return;
            } else {
                $image = Image::make(urldecode($data['file']));

                if ($data['height'] || $data['width']) {
                    if ($data['crop']) {
                        $image->crop($data['width'], $data['height']);
                    } else {
                        if (!$data['height'] || !$data['width']) {
                            $image->resize($data['width'], $data['height'], function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } else {
                            $image->resize($data['width'], $data['height']);
                        }
                    }
                }

                $stream = $image->stream('jpg', $data['quality']);

                Storage::disk('public')->put($path, $stream);
            }


            return;
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }
}
