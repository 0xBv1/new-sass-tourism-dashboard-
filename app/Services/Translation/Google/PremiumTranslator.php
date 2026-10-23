<?php

namespace App\Services\Translation\Google;

use App\Services\Translation\Translator;
use Google\Cloud\Translate\V2\TranslateClient;

class PremiumTranslator extends Translator
{

    public function translate(): ?string
    {
        $translator_client = new TranslateClient([
            'key' => config('services.google_translate.key')
        ]);

        if (empty($this->sourceText)) {
            return null;
        }

        if ($this->targetLanguage === $this->sourceLanguage) {
            return $this->sourceText;
        }

        if (str($this->sourceText)->stripTags()->trim()->isEmpty()) {
            return $this->sourceText;
        }

        $translation = $translator_client->translate($this->sourceText, [
            'source' => $this->sourceLanguage,
            'target' => $this->targetLanguage,
            'model' => 'nmt'   // Use Neural Machine Translation
        ]);

        if (isset($translation['text'])) {
            return htmlspecialchars_decode($translation['text']);
        }

        return null;
    }
}
