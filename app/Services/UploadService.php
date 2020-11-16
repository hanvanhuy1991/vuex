<?php

namespace App\Services;

use Carbon\Carbon;
use App\Enums\PostEnum;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    /**
     * Generate file name.
     * Ex: file/1598439813/avatar_ZTvyndiL.jpg
     *
     * @param $file
     *
     * @return string
     */
    public function generateFileName($file)
    {
        $originalName = $file->getClientOriginalName();

        $basename = pathinfo($originalName, PATHINFO_FILENAME);

        $extension = pathinfo($originalName, PATHINFO_EXTENSION);

        $current = Carbon::now()->timestamp;

        $random = '_' . Str::random(8);

        return "file/$current/$basename$random.$extension";
    }

    /**
     * Store file in local.
     *
     * @param File $file
     * @param string $disk
     * @param string $type
     *
     * @return array|string
     *
     * @throws \Exception $e
     */
    public function storeLocal($file, $disk = 'local', $type = PostEnum::MEDIA_TYPE_IMAGE)
    {
        try {
            $fileName = $this->generateFileName($file);

            $fileContent = file_get_contents($file->getRealPath());

            $storage = Storage::disk($disk);

            $storage->put($fileName, $fileContent);

            if ($type == PostEnum::MEDIA_TYPE_VIDEO) {
                Log::info("Stored video $fileName in local SUCCESSFULLY!");

                $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
                $gif = $this->video2gif($fileName, $extension);

                return [
                    'url_video' => $fileName,
                    'url_gif' => $gif['url'],
                    'width' => $gif['width'],
                    'height' => $gif['height']
                ];
            }

            Log::info("Stored image $fileName in local SUCCESSFULLY!");

            return [
                'url' => $fileName,
                'width' => Image::make($file)->width(),
                'height' => Image::make($file)->height(),
            ];
        } catch (\Exception $e) {
            report($e);
        }
    }


    public function video2gif($fileName, $extension)
    {
        $path = storage_path('app/');
        $output = str_replace('.'.$extension, '.gif', $fileName);
        $command = config('key.ffmpeg').' -ss 0 -t '.PostEnum::TIME_GIF.' -i '.$path.$fileName.' -vf "fps=10,scale=iw:-1:flags=lanczos,split[s0][s1];[s0]palettegen[p];[s1][p]paletteuse" -loop 0 '.$path.$output;
        shell_exec($command);
        $sizes = getimagesize($path.$output);

        return [
            'url' => $output,
            'width' => $sizes[0],
            'height' => $sizes[1]
        ];
    }

    /**
     * Store file in S3.
     *
     * @param $file
     * @param $mediaType
     *
     * @return array
     * @throws \Exception
     */
    public function storeS3($file, $mediaType)
    {
        try {
            $fileName = $this->generateFileName($file);

            $fileContent = file_get_contents($file->getRealPath());

            $s3 = Storage::disk('s3');

            $s3->put($fileName, $fileContent);
            $path = $s3->url($fileName);
            Log::info("Uploaded file to S3 SUCCESSFULLY! $path");

            if ($mediaType == PostEnum::MEDIA_TYPE_VIDEO) {
                return $path;
            }

            return [
                'url' => $path,
                'width' => Image::make($file)->width(),
                'height' => Image::make($file)->height(),
            ];
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * Store file in local. If store successfully, store that file in S3.
     *
     * @param string $fileName
     *
     * @return string $path (S3)
     *
     * @throws \Exception $e
     */
    public function storeLocalFileInS3($fileName)
    {
        $local = Storage::disk('local');
        $s3 = Storage::disk('s3');

        try {
            if ($local->exists($fileName)) {
                $fileContent = $local->get($fileName);

                $s3->put($fileName, $fileContent);

                $path = $s3->url($fileName);

                Log::info("Uploaded file to S3 SUCCESSFULLY! $path");

                return $path;
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function isImage($extension)
    {
        return in_array($extension, ['jpeg', 'jpg', 'bmp', 'png']);
    }

    public function isVideo($extension)
    {
        return in_array($extension, ['mp4', 'avi', 'flv', 'wmv', 'mov']);
    }

    public function getDirectoryPath($filePath)
    {
        $parts = explode('/', $filePath);

        return $parts[0] . '/' . $parts[1];
    }

    public function deleteLocalDirectory($directoryPath)
    {
        try {
            Storage::disk('local')->deleteDirectory($directoryPath);

            Log::info("Deleted folder $directoryPath SUCCESSFULLY!");
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function deleteS3Directory($directoryPath)
    {
        try {
            Storage::disk('s3')->deleteDirectory($directoryPath);
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * @param string|array $filePaths
     */
    public function deleteS3Files($filePaths)
    {
        $filePaths = (array)$filePaths;
        $pahts = array_map(function ($item) {
            return str_replace(config('key.aws_url'), '', $item);
        }, $filePaths);

        try {
            Storage::disk('s3')->delete($pahts);
        } catch (\Exception $e) {
            report($e);
        }
    }
}
