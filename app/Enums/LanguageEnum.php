<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LanguageEnum extends Enum
{

    const
        JAPANESE = 'ja',
        ENGLISH = 'en';

    const
        MODEL_POST = 'App\Models\Post';

    public static function languages()
    {
        return  [
            "af" => "Afrikaans",
            "ar" => "Arabic",
            "az" => "Azerbaijani",
            "be" => "Belarusian",
            "bg" => "Bulgarian",
            "bn" => "Bengali",
            "bs" => "Bosnian",
            "ca" => "Catalan",
            "ceb" => "Cebuano",
            "cs" => "Czech",
            "cy" => "Welsh",
            "da" => "Danish",
            "de" => "German",
            "el" => "Greek",
            "en" => "English",
            "eo" => "Esperanto",
            "es" => "Spanish",
            "et" => "Estonian",
            "eu" => "Basque",
            "fa" => "Persian",
            "fi" => "Finnish",
            "fr" => "French",
            "ga" => "Irish",
            "gl" => "Galician",
            "gu" => "Gujarati",
            "ha" => "Hausa",
            "hi" => "Hindi",
            "hmn" => "Hmong",
            "hr" => "Croatian",
            "ht" => "Haitian Creole",
            "hu" => "Hungarian",
            "hy" => "Armenian",
            "id" => "Indonesian",
            "ig" => "Igbo",
            "is" => "Icelandic",
            "it" => "Italian",
            "iw" => "Hebrew",
            "ja" => "Japanese",
            "jw" => "Javanese",
            "ka" => "Georgian",
            "kk" => "Kazakh",
            "km" => "Khmer",
            "kn" => "Kannada",
            "ko" => "Korean",
            "la" => "Latin",
            "lo" => "Lao",
            "lt" => "Lithuanian",
            "lv" => "Latvian",
            "ma" => "Punjabi",
            "mg" => "Malagasy",
            "mi" => "Maori",
            "mk" => "Macedonian",
            "ml" => "Malayalam",
            "mn" => "Mongolian",
            "mr" => "Marathi",
            "ms" => "Malay",
            "mt" => "Maltese",
            "my" => "Myanmar (Burmese)",
            "ne" => "Nepali",
            "nl" => "Dutch",
            "no" => "Norwegian",
            "ny" => "Chichewa",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ro" => "Romanian",
            "ru" => "Russian",
            "si" => "Sinhala",
            "sk" => "Slovak",
            "sl" => "Slovenian",
            "so" => "Somali",
            "sq" => "Albanian",
            "sr" => "Serbian",
            "st" => "Sesotho",
            "su" => "Sudanese",
            "sv" => "Swedish",
            "sw" => "Swahili",
            "ta" => "Tamil",
            "te" => "Telugu",
            "tg" => "Tajik",
            "th" => "Thai",
            "tl" => "Filipino",
            "tr" => "Turkish",
            "uk" => "Ukrainian",
            "ur" => "Urdu",
            "uz" => "Uzbek",
            "vi" => "Vietnamese",
            "yi" => "Yiddish",
            "yo" => "Yoruba",
            "zh-CN" => "Chinese Simplified",
            "zh-TW" => "Chinese Traditional",
            "zu" => "Zulu"
        ];
    }
}
