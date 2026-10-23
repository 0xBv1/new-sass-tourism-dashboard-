<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\SettingKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SettingsRequest;
use App\Models\Seo;
use App\Models\Setting;
use App\Services\Cache\AppCache;

class SettingController extends Controller
{
    public function show()
    {
        $settings = Setting::all();
        
        // Load SEO data from settings and create Seo model instance
        $seoData = $settings->firstWhere('option_key', SettingKey::SEO->value)?->option_value ?? [];
        $seo = new Seo();
        
        if (!empty($seoData)) {
            // Populate non-translatable attributes
            $seo->fill([
                'og_image' => $seoData['og_image'] ?? null,
                'og_type' => $seoData['og_type'] ?? null,
                'viewport' => $seoData['viewport'] ?? null,
                'robots' => $seoData['robots'] ?? null,
                'twitter_card' => $seoData['twitter_card'] ?? null,
                'twitter_creator' => $seoData['twitter_creator'] ?? null,
                'twitter_image' => $seoData['twitter_image'] ?? null,
            ]);
            
            // Populate translatable attributes for each locale
            foreach (config('translatable.supported_locales', []) as $locale => $localeName) {
                if (isset($seoData[$locale])) {
                    $seo->translateOrNew($locale)->fill($seoData[$locale]);
                }
            }
        }
        
        return view('dashboard.settings.show', compact('settings', 'seo'));
    }

    public function update(SettingsRequest $request)
    {
        // Handle SEO data separately
        if ($request->has('seo')) {
            $seoData = $request->get('seo');
            Setting::where('option_key', SettingKey::SEO->value)->updateOrCreate([
                'option_key' => SettingKey::SEO->value,
            ], [
                'option_key' => SettingKey::SEO->value,
                'option_value' => $seoData
            ]);
        }
        
        // Handle other settings (exclude SEO from the loop)
        foreach (SettingKey::all() as $key) {
            if ($key === SettingKey::SEO->value) {
                continue; // Skip SEO as it's handled above
            }
            Setting::where('option_key', $key)->updateOrCreate([
                'option_key' => $key,
            ], [
                'option_key' => $key,
                'option_value' => $request->get($key)
            ]);
        }
        session()->flash('message', 'Settings Updated Successfully!');
        session()->flash('type', 'success');
        AppCache::forgetBulk('settings_');
        return back();
    }
}
