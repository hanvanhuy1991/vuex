<?php

namespace App\Http\Requests;

use App\Http\Traits\FailedValidation;
use App\Rules\UploadFilePostRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    use FailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['string', 'nullable', 'max:128'],
            'content' => ['string', 'nullable', 'max:2000'],
            'hash_tag_ids' => ['array', 'max:5'],
            'hash_tag_ids.*' => ['required', 'integer', 'exists:hash_tags,id'],
            'location_name' => ['string'],
            'location_lat' => ['numeric'],
            'location_long' => ['numeric'],
            'images' => [
                'required_without:videos',
                'max:' . config('api.post.max_upload_images'),
                new UploadFilePostRule($this->hasFile('images'), $this->hasFile('videos'))
            ],
            'images.*' => [
                'image',
                'mimes:jpeg,jpg,bmp,png',
                'max:' . config('api.post.max_upload_image_size'),
                'dimensions:min_width=1,min_height=1'
            ],
            'videos' => [
                'required_without:images',
                'max:' . config('api.post.max_upload_videos'),
                new UploadFilePostRule($this->hasFile('images'), $this->hasFile('videos'))
            ],
            'videos.*' => [
                'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
                'max:' . config('api.post.max_upload_video_size')
            ],
            'time_public' => 'date_format:Y-m-d H:i|after:'.date('Y-m-d H:i'),
            'image_tags.*.tags.*.ratio_width' => ['required', 'integer', 'min:0', 'max:100'],
            'image_tags.*.tags.*.ratio_height' => ['required', 'integer', 'min:0', 'max:100'],
            'image_tags.*.tags.*.tag_id' => ['required', 'exists:users,id'],
            'image_tags.*.image_name' => ['required'],
            'image_tags.*.tags' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'images.max' => trans('post.error.exceed_number_of_images', [
                'max_upload_images' => config('api.post.max_upload_images')
            ]),
            'videos.max' => trans('post.error.exceed_number_of_videos', [
                'max_upload_videos' => config('api.post.max_upload_videos')
            ]),
        ];
    }

    public function all($key = null)
    {
        $data = parent::all();

        if (!empty($data['location_lat'])) {
            $data['location_lat'] = $this->str2float($data['location_lat']);
        }

        if (!empty($data['location_long'])) {
            $data['location_long'] = $this->str2float($data['location_long']);
        }

        if (!empty($data['hash_tag_ids'])) {
            $data['hash_tag_ids'] = array_map(function ($item) {
                return $this->str2int($item);
            }, $data['hash_tag_ids']);
        }

        if (!empty($data['image_tags'])) {
            $imageTags = [];
            foreach ($data['image_tags'] as $key => $imageTag) {
                $imageTags[$key]['image_name'] = $imageTag['image_name'] ?? '';
                $tags = [];
                if (!empty($imageTag['tags'])) {
                    foreach ($imageTag['tags'] as $keyTag => $tag) {
                        $tags[$keyTag] = array_map(function ($item) {
                            return $this->str2int($item);
                        }, $tag);
                    }
                }
                $imageTags[$key]['tags'] = $tags;
            }
            $data['image_tags'] = $imageTags;
        }

        return $data;
    }

    private function str2int($data)
    {
        return (int)str_replace(['"', '\''], '', $data);
    }

    private function str2float($data)
    {
        return (float)str_replace(['"', '\''], '', $data);
    }
}
