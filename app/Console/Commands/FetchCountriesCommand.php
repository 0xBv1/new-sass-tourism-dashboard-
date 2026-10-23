<?php

namespace App\Console\Commands;

use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Str;

class FetchCountriesCommand extends Command
{
    protected $signature = 'countries:fetch';

    protected $description = 'Fetch Countries';

    private bool $sslWarningShown = false;
    private bool $emojiConversionWarningShown = false;
    private $cachedCaBundlePath = null;

    public function handle(): void
    {
        try {
            // Configure HTTP client to handle SSL verification
            $caBundlePath = $this->getCaBundlePath();
            $countries = Http::withOptions([
                'verify' => $caBundlePath, // Use CA bundle if available, otherwise false for local dev
            ])->get('https://restcountries.com/v3.1/independent?fields=name,flag,cca2,idd')
              ->throw() // Throw exception on HTTP errors
              ->collect()
              ->sortBy('name.common');

            $this->output->progressStart(count($countries));
            $blockedCountries = ['Israel', 'Antarctica', 'Heard Island and McDonald Islands'];

            foreach ($countries as $country) {
                if (in_array($country['name']['common'], $blockedCountries)) {
                    continue;
                }
                $phoneCode = $country['idd']['root'] ?? null;

                if (!is_null($phoneCode) && isset($country['idd']['suffixes'])) {
                    $phoneCode .= $country['idd']['suffixes'][0] ?? '';
                }

                // Try to store flag, convert if MySQL doesn't support utf8mb4
                $flag = $country['flag'] ?? '';
                
                try {
                    $country = Country::firstOrCreate([
                        'code' => $country['cca2'],
                    ], [
                        'name' => $country['name']['common'] ?? null,
                        'flag' => $flag,
                        'code' => $country['cca2'],
                        'phone_code' => $phoneCode,
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    // If emoji storage fails (utf8 error), convert to Unicode code points
                    if (str_contains($e->getMessage(), 'Incorrect string value') || str_contains($e->getMessage(), 'utf8')) {
                        // Show warning only once
                        if (!$this->emojiConversionWarningShown) {
                            $this->warn("MySQL doesn't support utf8mb4. Converting emoji flags to Unicode code points for all countries.");
                            $this->emojiConversionWarningShown = true;
                        }
                        $flag = $this->convertFlagForStorage($flag);
                        
                        $country = Country::firstOrCreate([
                            'code' => $country['cca2'],
                        ], [
                            'name' => $country['name']['common'] ?? null,
                            'flag' => $flag,
                            'code' => $country['cca2'],
                            'phone_code' => $phoneCode,
                        ]);
                    } else {
                        // Re-throw if it's a different error
                        throw $e;
                    }
                }
                sleep(5);
                
                $loadCitiesRequest = Http::withOptions([
                    'verify' => $caBundlePath, // Use cached CA bundle path
                ])->post('https://countriesnow.space/api/v0.1/countries/states', [
                    'iso2' => $country->code
                ]);
                
                $data = $loadCitiesRequest->json();
                if ($loadCitiesRequest->successful() && isset($data['data']['states'])) {
                    foreach ($data['data']['states'] as $state) {
                        $country->states()->firstOrCreate([
                            'name' => Str::of($state['name'])->remove('Governorate')->trim(),
                            'code' => $state['state_code']
                        ]);
                    }
                }
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
        } catch (\Throwable $throwable) {
            $this->output->error($throwable->getMessage());
        }
    }

    /**
     * Get CA bundle path or return false for local development
     * Result is cached to avoid repeated checks and warnings
     * 
     * @return string|bool
     */
    private function getCaBundlePath()
    {
        // Return cached result if available
        if ($this->cachedCaBundlePath !== null) {
            return $this->cachedCaBundlePath;
        }

        // Check if php.ini has curl.cainfo set
        $cainfo = ini_get('curl.cainfo');
        if ($cainfo && file_exists($cainfo)) {
            $this->cachedCaBundlePath = $cainfo;
            return $this->cachedCaBundlePath;
        }

        // Check common locations for CA bundle
        $commonPaths = [
            base_path('cacert.pem'), // In project root
            storage_path('app/cacert.pem'), // In storage
            'C:/php/extras/ssl/cacert.pem', // XAMPP default
            'C:/wamp64/bin/php/php' . PHP_VERSION . '/extras/ssl/cacert.pem', // WAMP
        ];

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                $this->cachedCaBundlePath = $path;
                return $this->cachedCaBundlePath;
            }
        }

        // For local development, you can disable SSL verification (NOT recommended for production)
        // In production, always use proper CA bundle
        if (app()->environment('local')) {
            if (!$this->sslWarningShown) {
                $this->warn('SSL verification disabled for local development. For production, please configure CA certificate bundle.');
                $this->sslWarningShown = true;
            }
            $this->cachedCaBundlePath = false;
            return $this->cachedCaBundlePath; // Disable SSL verification for local dev only
        }

        // In production, throw error if no CA bundle found
        throw new \Exception('CA certificate bundle not found. Please configure curl.cainfo in php.ini or download cacert.pem');
    }

    /**
     * Convert emoji flag to Unicode code point string for older MySQL versions
     * Example: 🇦🇫 becomes "U+1F1E6U+1F1EB"
     * 
     * @param string $emojiFlag
     * @return string
     */
    private function convertFlagForStorage(string $emojiFlag): string
    {
        if (empty($emojiFlag)) {
            return '';
        }

        // Convert emoji to Unicode code point string
        // This format can be stored in utf8 (3-byte) MySQL
        $unicodeString = '';
        $chars = preg_split('//u', $emojiFlag, -1, PREG_SPLIT_NO_EMPTY);
        
        foreach ($chars as $char) {
            $code = mb_ord($char, 'UTF-8');
            if ($code !== false && $code > 0xFFFF) {
                // For 4-byte characters (emojis), convert to code point
                $unicodeString .= 'U+' . strtoupper(dechex($code));
            } else {
                // For regular characters, keep as-is
                $unicodeString .= $char;
            }
        }

        return $unicodeString ?: $emojiFlag; // Fallback to original if conversion fails
    }
}
