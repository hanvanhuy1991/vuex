<?php

namespace App\Http\Controllers;

use App\Enums\LanguageEnum;
use App\Models\Post;
use App\Services\TranslateService;
use Illuminate\Http\Request;

class TranslateController extends BaseController
{
    /**
     * @var $translateService
     */
    protected $translateService;

    /**
     * TranslateController constructor.
     *
     * @param TranslateService $translateService
     */
    public function __construct(TranslateService $translateService)
    {
        parent::__construct();

        $this->translateService = $translateService;
    }

    public function postTranslate(Post $post, $language = LanguageEnum::ENGLISH)
    {
        return $this->responseSuccess($this->translateService->googleTranslate($post, $language));
    }
}
