<?php

namespace App\Services;

use App\Enums\LanguageEnum;

class TranslateService
{
    public function googleTranslate($post, $language)
    {
        $content = $post->content;
        if ($post->current_language == $language ||
            empty($content) ||
            !array_key_exists($language, LanguageEnum::languages())
        ) {
            return $content;
        }

        $translate = $post->translates()->whereLanguage($language);
        if ($translate->exists()) {
            return $translate->value('content');
        }
        $curl = curl_init();

        curl_setopt_array($curl, [
                CURLOPT_URL => 'https://google-translate20.p.rapidapi.com/translate?sl=auto&tl='.$language.'&text='.urlencode($content),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    'x-rapidapi-host: google-translate20.p.rapidapi.com',
                    'x-rapidapi-key: '.env('GOOGLE_TRANSLATE_KEY')
                ]
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return $content;
        }

        $textTranslate = json_decode($response)->data->translation;
        $post->translates()->create([
            'translateable_type' => LanguageEnum::MODEL_POST,
            'language' => $language,
            'content' => $textTranslate
        ]);

        return $textTranslate;
    }
}
