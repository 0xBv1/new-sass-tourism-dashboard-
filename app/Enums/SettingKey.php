<?php

namespace App\Enums;

enum SettingKey: string
{
    case SOCIAL_LINKS = 'social_links';
    case NOTIFICATION_EMAILS = 'notification_emails';
    case SITE_TITLE = 'site_title';
    case CONTACT_PHONE_NUMBER = 'CONTACT_PHONE_NUMBER';
    case EMAIL_ADDRESS = 'email_address';
    case ADDRESS = 'address';
    case COMPANY_TEAM = 'company_team';
    case TINY_EDITOR = 'tiny_editor';
    case LOGO = 'logo';
    case QUEUE_MONITOR_UI = 'queue_monitor_ui';

    case COMPANY_LOCATION_URL = 'company_location_url';
    
    case SEO = 'seo';
    public static function all(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

}
