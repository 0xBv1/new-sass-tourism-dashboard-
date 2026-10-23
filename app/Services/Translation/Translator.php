<?php
namespace App\Services\Translation;

abstract class Translator
{
    protected string $sourceText;
    protected string $targetLanguage;
    protected string $sourceLanguage;

    public function __construct(string $sourceText, string $targetLanguage, string $sourceLanguage = 'en')
    {
        $this->sourceText = $sourceText;
        $this->targetLanguage = $targetLanguage;
        $this->sourceLanguage = $sourceLanguage ?? config('app.locale');
    }

    abstract function translate(): ?string;
}
