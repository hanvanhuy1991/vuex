<?php

namespace App\Http\Requests;

use App\Rules\UploadFilePostRule;
use App\Http\Traits\FailedValidation;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
            'content' => 'required_without:image|string|max:1000',
            'parent_id' => ['integer', 'exists:comments,id'],
            'image' => [
                'max:'.config('api.post.max_upload_image_size'),
                'mimes:jpeg,jpg,bmp,png',
                'dimensions:min_width=1,min_height=1',
                new UploadFilePostRule($this->hasFile('image'), $this->hasFile('video'))
            ],
            'mentions.*' => ['integer', 'exists:users,id'],
//            'video' => [
//                'max:'.config('api.post.max_upload_video_size'),
//                'mimetypes:video/x-ms-asf,video/x-flv,video/mp4,application/x-mpegURL,video/MP2T,video/3gpp,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/avi',
//                new UploadFilePostRule($this->hasFile('image'), $this->hasFile('video'))
//            ],
//            'width_video' => 'required_with:video|integer|min:1',
//            'height_video' => 'required_with:video|integer|min:1',
        ];
    }

    public function all($key = null)
    {
        $data = parent::all();
        if (!empty($data['parent_id'])) {
            $data['parent_id'] = $this->str2int($data['parent_id']);
        }
        if (!empty($data['mentions'])) {
            $data['mentions'] = array_map(function ($item) {
                return $this->str2int($item);
            }, $data['mentions']);
        }

        return $data;
    }

    private function str2int($data)
    {
        return (int)str_replace(['"', '\''], '', $data);
    }
}
