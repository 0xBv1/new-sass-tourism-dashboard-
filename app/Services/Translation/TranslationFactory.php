<?php

namespace App\Services\Translation;

use App\Services\Translation\Google\FreeTranslator;
use App\Services\Translation\Google\PremiumTranslator;

class TranslationFactory
{
    public static function translate(string $sourceText, string $targetLanguage, string $sourceLanguage = 'en'): ?string
    {
        $translator = new FreeTranslator(...func_get_args());

//        if (!empty($sourceText) && strlen($sourceText) > 5000) {
//            $translator = new PremiumTranslator(...func_get_args());
//        }

        return $translator->translate();
    }
}
