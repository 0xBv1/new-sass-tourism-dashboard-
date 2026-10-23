<?php

namespace App\Models;

use App\Traits\Models\Activated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use Activated;

    protected $fillable = [
        'name',
        'code',
        'active',
        'flag',
        'phone_code',
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    /**
     * Get the flag attribute, converting Unicode code points back to emoji if needed
     * 
     * @param string|null $value
     * @return string|null
     */
    public function getFlagAttribute($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Check if value is Unicode code point format (e.g., "U+1F1E6U+1F1EB")
        if (preg_match('/^U\+[0-9A-F]+(U\+[0-9A-F]+)*$/i', $value)) {
            // Convert Unicode code points back to emoji
            return $this->convertUnicodeToEmoji($value);
        }

        // Return as-is (already emoji or other format)
        return $value;
    }

    /**
     * Convert Unicode code point string to emoji
     * Example: "U+1F1E6U+1F1EB" becomes "🇦🇫"
     * 
     * @param string $unicodeString
     * @return string
     */
    private function convertUnicodeToEmoji(string $unicodeString): string
    {
        $emoji = '';
        $codePoints = preg_split('/U\+/i', $unicodeString, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($codePoints as $codePoint) {
            $code = hexdec($codePoint);
            if ($code > 0) {
                $emoji .= mb_chr($code, 'UTF-8');
            }
        }

        return $emoji ?: $unicodeString; // Fallback to original if conversion fails
    }
}
