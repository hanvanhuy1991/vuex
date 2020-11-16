<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use App\Services\UploadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadFilePost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uploadService;
    protected $filePaths;
    protected $post;
    protected $userId;
    protected $widths;
    protected $heights;
    protected $gif;
    protected $insertImageTags;

    /**
     * Create a new job instance.
     *
     * @param UploadService $uploadService
     * @param array filePaths
     * @param Post $post
     * @param integer $userId
     * @param array $widths
     * @param array $heights
     * @param array $gif
     * @param array $insertImageTags
     *
     * @return void
     */
    public function __construct(
        UploadService $uploadService,
        $filePaths,
        $post,
        $userId,
        $widths,
        $heights,
        $gif,
        $insertImageTags
    ) {
        $this->uploadService = $uploadService;
        $this->filePaths = $filePaths;
        $this->post = $post;
        $this->userId = $userId;
        $this->widths = $widths;
        $this->heights = $heights;
        $this->gif = $gif;
        $this->insertImageTags = $insertImageTags;
    }

    /**
     * Execute the job.
     * 1. Store file in local.
     * 2. Store that file in S3.
     * 3. If store that file in S3 successfully, delete local file.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle()
    {
        try {
            foreach ($this->filePaths as $key => $filePath) {
                $fileName = explode('/', $filePath)[2];

                $extension = explode('.', $fileName)[1];

                $s3Path = $this->uploadService->storeLocalFileInS3($filePath);

                if ($this->uploadService->isImage($extension)) {
                    $this->saveImage($this->post, $s3Path, $this->widths[$key], $this->heights[$key], $this->userId, $filePath);
                    if ($key == 0) {
                        $this->post->update([
                            'thumbnail' => $s3Path,
                            'thumbnail_width' => $this->widths[$key],
                            'thumbnail_height' => $this->heights[$key],
                        ]);
                    }
                }

                if ($this->uploadService->isVideo($extension)) {
                    $this->saveVideo($this->post, $s3Path, $this->widths[$key], $this->heights[$key], $this->userId);
                    $s3GifPath = $this->uploadService->storeLocalFileInS3($this->gif[$key]);
                    if ($key == 0) {
                        $this->post->update([
                            'thumbnail' => $s3GifPath,
                            'thumbnail_width' => $this->widths[$key],
                            'thumbnail_height' => $this->heights[$key],
                        ]);
                    }
                }
            }

            $directoryPath = $this->uploadService->getDirectoryPath($this->filePaths[0]);

            $this->uploadService->deleteLocalDirectory($directoryPath);
        } catch (\Exception $e) {
            report($e);
        }
    }

    public function saveImage($post, $path, $width, $height, $userId, $filePath)
    {
        if (!empty($path)) {
            $image = $post->images()->create([
                'image_path' => $path,
                'width' => $width,
                'height' => $height,
                'created_by' => $userId,
            ]);

            $imageTags = $image->imageTags();
            $imageTags->delete();
            if (!empty($this->insertImageTags[$filePath])) {
                $imageTags->createMany($this->insertImageTags[$filePath]);
            }

            Log::info("Stored image $image->id with path $image->image_path in database SUCCESSFULLY!");
        } else {
            Log::info("404! Not found image path!");
        }
    }

    public function saveVideo($post, $path, $width, $height, $userId)
    {
        if (!empty($path)) {
            $video = $post->videos()->create([
                'video_path' => $path,
                'width' => $width,
                'height' => $height,
                'created_by' => $userId,
            ]);

            Log::info("Stored video $video->id with path $video->video_path in database SUCCESSFULLY!");
        } else {
            Log::info("404! Not found video path!");
        }
    }
}
