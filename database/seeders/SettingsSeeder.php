<?php

namespace Database\Seeders;

use App\Enums\SettingKey;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $this->addSocialLinks();
        $this->addBookingNotifiableEmails();
    }

    private function addSocialLinks(): void
    {
        $platforms = [
            ['type' => 'facebook', 'url' => 'https://www.facebook.com/SunPyramidsTours/'],
            ['type' => 'twitter', 'url' => 'https://twitter.com/sunpyramidstour'],
            ['type' => 'google-plus', 'url' => 'https://plus.google.com/104667192940732867882'],
            ['type' => 'instagram', 'url' => 'https://www.instagram.com/travelbysunpyramidstours/'],
            ['type' => 'pinterest', 'url' => 'https://www.pinterest.com/sunpyramidstours/'],
            ['type' => 'youtube', 'url' => 'https://www.youtube.com/channel/UCCsn_rbLMuer0kJd9iK6RDA'],
            ['type' => 'tripadvisor', 'url' => 'https://www.tripadvisor.com/Attraction_Review-g294202-d14040279-Reviews-Sun_Pyramids_Tours-Giza_Giza_Governorate.html'],
        ];

        Setting::firstOrCreate([
            'option_key' => SettingKey::SOCIAL_LINKS->value
        ], [
            'option_key' => SettingKey::SOCIAL_LINKS->value,
            'option_value' => $platforms
        ]);
    }

    private function addBookingNotifiableEmails(): void
    {
        Setting::firstOrCreate(['option_key' => SettingKey::NOTIFICATION_EMAILS->value], [
            'option_key' => SettingKey::NOTIFICATION_EMAILS->value,
            'option_value' => ['ahmednasr2589@gmail.com']
        ]);
    }
}
