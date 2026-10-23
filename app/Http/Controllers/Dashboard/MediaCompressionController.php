<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaCompressionController extends Controller
{
    private array $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
    private array $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
    private array $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];
    
    public function index(Request $request)
    {
        $directory = $request->get('directory', 'assets');
        $search = $request->get('search', '') ?? '';
        
        // Get available directories
        $availableDirectories = $this->getAvailableDirectories();
        
        // If requested directory doesn't exist, use first available
        if (!in_array($directory, $availableDirectories)) {
            $directory = !empty($availableDirectories) ? $availableDirectories[0] : 'assets';
        }
        
        $mediaFiles = $this->scanMediaFiles($directory, (string)$search);
        
        // Calculate statistics
        $stats = $this->calculateStats($mediaFiles);
        
        // Check WebP support
        $webpSupported = $this->checkWebPSupport();
        
        return view('dashboard.media-compression.index', compact('mediaFiles', 'stats', 'directory', 'search', 'availableDirectories', 'webpSupported'));
    }
    
    public function test()
    {
        return view('dashboard.media-compression.test');
    }
    
    private function checkWebPSupport(): array
    {
        $support = [
            'gd_loaded' => extension_loaded('gd'),
            'webp_available' => function_exists('imagewebp'),
            'jpeg_available' => function_exists('imagecreatefromjpeg'),
            'png_available' => function_exists('imagecreatefrompng'),
            'gif_available' => function_exists('imagecreatefromgif'),
            'cwebp_available' => $this->hasCwebp(),
        ];
        
        if ($support['gd_loaded']) {
            $gdInfo = gd_info();
            $support['gd_version'] = $gdInfo['GD Version'] ?? 'Unknown';
            $support['webp_supported'] = isset($gdInfo['WebP Support']) && $gdInfo['WebP Support'];
        }
        
        // Overall WebP support (either GD or cwebp)
        $support['webp_available'] = $support['webp_available'] || $support['cwebp_available'];
        
        return $support;
    }
    
    private function getAvailableDirectories(): array
    {
        $directories = [];
        $publicPath = public_path();
        
        $possibleDirs = ['assets', 'storage', 'vendor'];
        
        foreach ($possibleDirs as $dir) {
            $fullPath = $publicPath . DIRECTORY_SEPARATOR . $dir;
            if (File::exists($fullPath) && File::isDirectory($fullPath)) {
                $directories[] = $dir;
            }
        }
        
        return $directories;
    }
    
    public function compress(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'string',
            'delete_original' => 'boolean',
            'quality' => 'integer|min:1|max:100',
        ]);
        
        $files = $request->input('files', []);
        $deleteOriginal = $request->boolean('delete_original', false);
        $quality = $request->input('quality', 85);
        
        $results = [
            'success' => [],
            'failed' => [],
            'skipped' => [],
            'total_original_size' => 0,
            'total_compressed_size' => 0,
        ];
        
        foreach ($files as $filePath) {
            try {
                $fullPath = public_path($filePath);
                
                if (!File::exists($fullPath)) {
                    $results['failed'][] = [
                        'file' => $filePath,
                        'error' => 'File not found'
                    ];
                    continue;
                }
                
                $extension = strtolower(File::extension($fullPath));
                
                if (!in_array($extension, $this->imageExtensions) || $extension === 'webp') {
                    $results['skipped'][] = [
                        'file' => $filePath,
                        'reason' => 'Not a compressible image or already WebP'
                    ];
                    continue;
                }
                
                $originalSize = File::size($fullPath);
                $results['total_original_size'] += $originalSize;
                
                // Convert to WebP
                $webpPath = $this->convertToWebP($fullPath, $quality);
                
                if ($webpPath) {
                    $compressedSize = File::size($webpPath);
                    $results['total_compressed_size'] += $compressedSize;
                    
                    $results['success'][] = [
                        'file' => $filePath,
                        'webp_file' => str_replace(public_path(), '', $webpPath),
                        'original_size' => $this->formatBytes($originalSize),
                        'compressed_size' => $this->formatBytes($compressedSize),
                        'saved' => $this->formatBytes($originalSize - $compressedSize),
                        'saved_percentage' => round((($originalSize - $compressedSize) / $originalSize) * 100, 2),
                    ];
                    
                    // Delete original if requested
                    if ($deleteOriginal) {
                        File::delete($fullPath);
                    }
                } else {
                    $results['failed'][] = [
                        'file' => $filePath,
                        'error' => 'Failed to convert to WebP'
                    ];
                }
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'file' => $filePath,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Compression completed',
            'results' => $results,
        ]);
    }
    
    private function scanMediaFiles(string $directory, ?string $search = null): array
    {
        $files = [];
        $basePath = public_path($directory);
        $search = $search ?? '';
        
        if (!File::exists($basePath)) {
            return $files;
        }
        
        if (!File::isDirectory($basePath)) {
            return $files;
        }
        
        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );
            
            foreach ($iterator as $file) {
                try {
                    if ($file->isFile() && $file->isReadable()) {
                        $relativePath = str_replace(public_path(), '', $file->getPathname());
                        $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
                        
                        // Apply search filter
                        if (!empty($search) && !Str::contains(strtolower($file->getFilename()), strtolower($search))) {
                            continue;
                        }
                        
                        $extension = strtolower($file->getExtension());
                        $size = $file->getSize();
                        
                        $files[] = [
                            'path' => $relativePath,
                            'full_path' => $file->getPathname(),
                            'name' => $file->getFilename(),
                            'extension' => $extension,
                            'size' => $size,
                            'size_formatted' => $this->formatBytes($size),
                            'type' => $this->getFileType($extension),
                            'is_image' => in_array($extension, $this->imageExtensions),
                            'is_compressible' => in_array($extension, $this->imageExtensions) && $extension !== 'webp',
                            'is_webp' => $extension === 'webp',
                            'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                        ];
                    }
                } catch (\Exception $e) {
                    // Skip files that can't be read
                    continue;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error scanning media files: ' . $e->getMessage(), [
                'directory' => $directory,
                'basePath' => $basePath,
            ]);
            return $files;
        }
        
        // Sort by size (largest first)
        usort($files, function($a, $b) {
            return $b['size'] <=> $a['size'];
        });
        
        return $files;
    }
    
    private function calculateStats(array $files): array
    {
        $stats = [
            'total_files' => count($files),
            'total_size' => 0,
            'images' => 0,
            'videos' => 0,
            'documents' => 0,
            'other' => 0,
            'compressible' => 0,
            'webp_count' => 0,
            'by_extension' => [],
        ];
        
        foreach ($files as $file) {
            $stats['total_size'] += $file['size'];
            
            if ($file['is_image']) {
                $stats['images']++;
            } elseif (in_array($file['extension'], $this->videoExtensions)) {
                $stats['videos']++;
            } elseif (in_array($file['extension'], $this->documentExtensions)) {
                $stats['documents']++;
            } else {
                $stats['other']++;
            }
            
            if ($file['is_compressible']) {
                $stats['compressible']++;
            }
            
            if ($file['is_webp']) {
                $stats['webp_count']++;
            }
            
            $ext = strtoupper($file['extension']);
            if (!isset($stats['by_extension'][$ext])) {
                $stats['by_extension'][$ext] = [
                    'count' => 0,
                    'size' => 0,
                ];
            }
            $stats['by_extension'][$ext]['count']++;
            $stats['by_extension'][$ext]['size'] += $file['size'];
        }
        
        $stats['total_size_formatted'] = $this->formatBytes($stats['total_size']);
        
        return $stats;
    }
    
    private function convertToWebP(string $filePath, int $quality = 85): ?string
    {
        $extension = strtolower(File::extension($filePath));
        $webpPath = Str::replaceLast('.' . $extension, '.webp', $filePath);
        
        try {
            // Try using cwebp command-line tool first (if available)
            if ($this->hasCwebp()) {
                return $this->convertUsingCwebp($filePath, $webpPath, $quality);
            }
            
            // Fallback to GD if WebP is supported
            if (extension_loaded('gd') && function_exists('imagewebp')) {
                return $this->convertUsingGD($filePath, $webpPath, $quality, $extension);
            }
            
            throw new \Exception('No WebP conversion method available. Please install cwebp tool or enable WebP support in GD.');
        } catch (\Exception $e) {
            \Log::error('WebP conversion failed: ' . $e->getMessage(), [
                'file' => $filePath,
                'quality' => $quality,
            ]);
            return null;
        }
    }
    
    private function hasCwebp(): bool
    {
        // Check if cwebp command is available
        $output = [];
        $returnVar = 0;
        @exec('cwebp -version 2>&1', $output, $returnVar);
        return $returnVar === 0;
    }
    
    private function convertUsingCwebp(string $filePath, string $webpPath, int $quality): ?string
    {
        try {
            // Escape file paths for command line
            $escapedInput = escapeshellarg($filePath);
            $escapedOutput = escapeshellarg($webpPath);
            
            // Build cwebp command
            $command = sprintf(
                'cwebp -q %d %s -o %s 2>&1',
                $quality,
                $escapedInput,
                $escapedOutput
            );
            
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0 || !File::exists($webpPath)) {
                throw new \Exception('cwebp conversion failed: ' . implode(' ', $output));
            }
            
            return $webpPath;
        } catch (\Exception $e) {
            \Log::error('cwebp conversion failed: ' . $e->getMessage(), [
                'file' => $filePath,
                'quality' => $quality,
            ]);
            return null;
        }
    }
    
    private function convertUsingGD(string $filePath, string $webpPath, int $quality, string $extension): ?string
    {
        try {
            // Create image resource based on extension
            $image = null;
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    if (!function_exists('imagecreatefromjpeg')) {
                        throw new \Exception('JPEG support is not available');
                    }
                    $image = @imagecreatefromjpeg($filePath);
                    break;
                case 'png':
                    if (!function_exists('imagecreatefrompng')) {
                        throw new \Exception('PNG support is not available');
                    }
                    $image = @imagecreatefrompng($filePath);
                    if ($image) {
                        // Preserve transparency
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                    }
                    break;
                case 'gif':
                    if (!function_exists('imagecreatefromgif')) {
                        throw new \Exception('GIF support is not available');
                    }
                    $image = @imagecreatefromgif($filePath);
                    break;
                default:
                    throw new \Exception("Unsupported image format: {$extension}");
            }
            
            if (!$image) {
                throw new \Exception('Failed to create image resource. The file may be corrupted or in an unsupported format.');
            }
            
            // Convert to WebP
            $success = @imagewebp($image, $webpPath, $quality);
            imagedestroy($image);
            
            if (!$success || !File::exists($webpPath)) {
                throw new \Exception('Failed to save WebP image. Check file permissions.');
            }
            
            return $webpPath;
        } catch (\Exception $e) {
            \Log::error('GD WebP conversion failed: ' . $e->getMessage(), [
                'file' => $filePath,
                'quality' => $quality,
            ]);
            return null;
        }
    }
    
    private function getFileType(string $extension): string
    {
        if (in_array($extension, $this->imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $this->videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $this->documentExtensions)) {
            return 'document';
        }
        return 'other';
    }
    
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
