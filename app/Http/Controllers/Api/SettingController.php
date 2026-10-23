<?php

namespace App\Http\Controllers\Api;

use App\Enums\SettingKey;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Query\QueryBuilder;
use App\Traits\Response\HasApiResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HasApiResponse;

    public function index(Request $request)
    {
        $builder = new QueryBuilder((new Setting()), $request);
        $settings = $builder->build()->whereNotIn('option_key', [
            SettingKey::NOTIFICATION_EMAILS->value,
            SettingKey::TINY_EDITOR->value,
            SettingKey::QUEUE_MONITOR_UI->value,
        ])->get()->toArray();
        return $this->send(
            data: $settings
        );
    }
}
