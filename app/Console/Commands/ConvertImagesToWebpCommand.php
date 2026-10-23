<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;
use Intervention\Image\Facades\Image;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ConvertImagesToWebpCommand extends Command
{
    protected $signature = 'convert:images-to-webp';

    protected $description = 'Command description';

    public function handle(): void
    {
        $iterations = 0;
        $converted = [];
        $files = $this->getAllFiles(storage_path('app/public/media'));
        $progressBar = $this->output->createProgressBar(count($files));
        foreach ($files as $file) {
            try {
                $this->convertToWebp($file);
                $converted[] = $file;
                File::delete(storage_path($file));
            } catch (\Throwable $throwable) {
                $this->warn("Can't convert file [$file]: " . $throwable->getMessage());
            }
            $progressBar->advance();
//            if ($iterations % 100 == 0) {
//                sleep(10);
//            }
        }
        $progressBar->finish();
        File::put(storage_path('app/converted-webp.json'), json_encode($converted, JSON_PRETTY_PRINT));
        $this->output->success('All Images Converted');
    }

    public function getAllFiles($dir): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);
        $files = array();

        foreach ($iterator as $file) {
            if ($file->isFile() && !in_array(strtolower($file->getExtension()), ['webp', 'pdf', 'txt', 'html'])) {
                $files[] = str($file->getPathname())->replace(storage_path(), '')->replace("\\", '/')->toString();
            }
        }

        return $files;
    }

    private function convertToWebp(mixed $file): void
    {
        $file_extension = File::extension(storage_path($file));
        $new_file_name = str($file)->replaceLast($file_extension, 'webp');
        Image::make(storage_path($file))
            ->encode('webp')
            ->save(storage_path($new_file_name));
    }
}
