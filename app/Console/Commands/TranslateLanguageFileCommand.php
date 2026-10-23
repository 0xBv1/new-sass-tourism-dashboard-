<?php

namespace App\Console\Commands;

use App\Services\Translation\TranslationFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TranslateLanguageFileCommand extends Command
{
    protected $signature = 'translate:file {--all=no} {--path=}';

    protected $description = 'Translate localization files using Google Translate';

    protected string $defaultLocale;
    private bool $success = true;

    public function handle(): void
    {
        $this->defaultLocale = config('app.locale', 'en');

        if ($this->option('all') == 'no') {
            $file_name = $this->ask('Type translation file name?');
            if (!$file_name) {
                $this->error('File Name is required');
                return;
            }
            $this->translateFile($file_name);
        } else {
            $dir_path = $this->langDir() . '/' . $this->defaultLocale . '/*.php';
            $files = glob($dir_path);
            foreach ($files as $file) {
                $fileName = File::name($file);
                $this->translateFile($fileName . '.php');
                $this->info("File [$fileName].php translated");
            }
        }
        if ($this->success) {
            $this->output->success('Translation files are generated!');
        }
    }

    public function translateFile($file_name): void
    {
        $full_path = $this->langDir() . '/' . $this->defaultLocale . '/' . $file_name;

        if (!File::exists($full_path)) {
            $this->success = false;
            $this->error("File [$file_name] Not Found!");
            return;
        }

        $array = Arr::dot(File::getRequire($full_path));
        $locales = collect(config('laravel-lang-translatable.locales'))
            ->filter(fn($local) => $local != $this->defaultLocale);

        foreach ($locales as $idx => $local) {
            $translated_array = [];
            if ($idx != 0) {
                sleep(3);
            }
            $this->info("Translating to [$local]...");
            foreach ($array as $k => $v) {
                if (empty($v)) {
                    $translated_array[$k] = is_array($v) ? [] : '';
                    continue;
                }
                $v = Str::of($v)->trim()->replace(PHP_EOL, ' ');
                $translated_array[$k] = $this->translateWord($v, $local);
            }
            $translated_array = Arr::undot($translated_array);
            $this->createTranslatedFile($translated_array, $local, $file_name);
        }
    }

    private function createTranslatedFile(array $translated_array, string $local, string $file_name): void
    {
        $file_content = "<?php\n\nreturn " . $this->varExport($translated_array) . ';' . PHP_EOL;

        $local_dir = $this->langDir() . '/' . $local;

        File::ensureDirectoryExists($local_dir);

        File::put($local_dir . '/' . $file_name, $file_content);
    }

    private function translateWord($term, $target): string
    {
        $str = Str::of($term);
        $placeholders = [];
        $new_str = '';

        foreach ($str->explode(' ') as $term) {
            if (Str::of($term)->startsWith(':')) {
                $new_str .= '____ ';
                $placeholders[] = $term;
            } else {
                $new_str .= $term . ' ';
            }
        }

        $translated = TranslationFactory::translate(
            trim($new_str),
            $target,
            $this->defaultLocale
        );

        if (!$translated) {
            return $term;
        }

        $new_str = Str::of($translated);
        foreach ($placeholders as $placeholder) {
            $new_str = $new_str->replaceFirst('____', $placeholder);
        }
        return $new_str->toString();
    }

    public function langDir(): bool|array|string
    {
        $pathOption = $this->option('path');
        return empty($pathOption) ? lang_path() : base_path($pathOption);
    }

    /**
     * Export array to PHP code with proper formatting
     * Alternative to VarExporter that doesn't require additional dependencies
     */
    private function varExport(array $array, int $indent = 0): string
    {
        $lines = [];
        $indentStr = str_repeat('    ', $indent);
        $nextIndent = str_repeat('    ', $indent + 1);

        foreach ($array as $key => $value) {
            $keyStr = is_string($key) && preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $key)
                ? $key
                : var_export($key, true);

            if (is_array($value)) {
                $valueStr = $this->varExport($value, $indent + 1);
                $lines[] = "$nextIndent$keyStr => $valueStr,";
            } else {
                $valueStr = var_export($value, true);
                $lines[] = "$nextIndent$keyStr => $valueStr,";
            }
        }

        if (empty($lines)) {
            return '[]';
        }

        return "[\n" . implode("\n", $lines) . "\n$indentStr]";
    }
}
