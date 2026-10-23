<?php

namespace App\Services\Translation\Google;

use App\Services\Translation\Translator;
use Illuminate\Support\Facades\Log;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Stichoza\GoogleTranslate\Exceptions\TranslationRequestException;

class FreeTranslator extends Translator
{
    public function translate(): ?string
    {
        if (empty($this->sourceText) ||
            $this->targetLanguage === $this->sourceLanguage ||
            strlen($this->sourceText) > 5000 ||
            str($this->sourceText)->stripTags()->trim()->isEmpty()) {
            return $this->sourceText;
        }

        try {
            // For development on Windows, SSL verification may fail
            // Set GOOGLE_TRANSLATE_VERIFY_SSL=false in .env for development
            // In production, ensure proper CA bundle is configured in php.ini
            $verifySSL = filter_var(env('GOOGLE_TRANSLATE_VERIFY_SSL', false), FILTER_VALIDATE_BOOLEAN);
            
            // Configure options for GoogleTranslate
            // Note: setClient() expects a string ('webapp' or 'gtx'), not a GuzzleHttp\Client object
            $options = [
                'timeout' => 30,
                'connect_timeout' => 10,
            ];
            
            // Only set verify if we want to disable SSL verification
            if (!$verifySSL) {
                $options['verify'] = false;
            }

            // Create translator with options
            $translator = new GoogleTranslate($this->targetLanguage, $this->sourceLanguage, $options);

            return $translator->translate($this->sourceText);
        } catch (TranslationRequestException $e) {
            // Check if it's an SSL error
            if (str_contains($e->getMessage(), 'SSL') || str_contains($e->getMessage(), 'certificate')) {
                Log::warning('Google Translate SSL error. To fix: Download CA bundle from https://curl.se/ca/cacert.pem and set curl.cainfo in php.ini, or set GOOGLE_TRANSLATE_VERIFY_SSL=false for development: ' . $e->getMessage(), [
                    'source' => $this->sourceLanguage,
                    'target' => $this->targetLanguage,
                ]);
            } else {
                Log::warning('Google Translate error: ' . $e->getMessage(), [
                    'source' => $this->sourceLanguage,
                    'target' => $this->targetLanguage,
                    'text_length' => strlen($this->sourceText)
                ]);
            }
            
            return $this->sourceText;
        } catch (\Exception $e) {
            // Catch any other exceptions
            Log::error('Translation error: ' . $e->getMessage(), [
                'source' => $this->sourceLanguage,
                'target' => $this->targetLanguage,
                'exception' => get_class($e)
            ]);
            
            return $this->sourceText;
        }
    }
}
