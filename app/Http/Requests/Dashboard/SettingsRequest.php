<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\SettingKey;
use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [];
        foreach (SettingKey::all() as $key) {
            if ($key === 'seo') {
                // SEO has a more complex structure with translations
                $rules[$key] = ['nullable', 'array'];
                // Add validation for SEO translatable fields
                foreach (config('translatable.supported_locales', []) as $locale => $localeName) {
                    $rules["{$key}.{$locale}"] = ['nullable', 'array'];
                    $rules["{$key}.{$locale}.meta_title"] = ['nullable', 'string', 'max:255'];
                    $rules["{$key}.{$locale}.meta_description"] = ['nullable', 'string'];
                    $rules["{$key}.{$locale}.meta_keywords"] = ['nullable', 'string'];
                    $rules["{$key}.{$locale}.og_title"] = ['nullable', 'string', 'max:255'];
                    $rules["{$key}.{$locale}.og_description"] = ['nullable', 'string'];
                    $rules["{$key}.{$locale}.canonical"] = ['nullable', 'string', 'max:255'];
                    $rules["{$key}.{$locale}.twitter_title"] = ['nullable', 'string', 'max:255'];
                    $rules["{$key}.{$locale}.twitter_description"] = ['nullable', 'string'];
                    $rules["{$key}.{$locale}.structure_schema"] = ['nullable', 'string'];
                }
                // Non-translatable SEO fields
                $rules["{$key}.og_image"] = ['nullable', 'string'];
                $rules["{$key}.og_type"] = ['nullable', 'string', 'max:255'];
                $rules["{$key}.viewport"] = ['nullable', 'string', 'max:255'];
                $rules["{$key}.robots"] = ['nullable', 'string', 'max:255'];
                $rules["{$key}.twitter_card"] = ['nullable', 'string', 'max:255'];
                $rules["{$key}.twitter_creator"] = ['nullable', 'string', 'max:255'];
                $rules["{$key}.twitter_image"] = ['nullable', 'string'];
            } else {
                $rules[$key] = ['nullable', 'array'];
            }
        }
        return $rules;
    }

    public function authorize(): bool
    {
        return true;
    }
}
