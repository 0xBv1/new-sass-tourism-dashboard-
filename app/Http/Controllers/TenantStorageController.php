<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TenantStorageController extends Controller
{
    public function show(Request $request, ?string $path = null): BinaryFileResponse
    {
        $this->abortIf($path === null || $path === '', 404);

        $root = in_array($request->getHost(), config('tenancy.central_domains', []), true)
            ? base_path('storage/app/public')
            : storage_path('app/public');
        $allowedRoot = realpath($root);
        $this->abortIf($allowedRoot === false, 404);

        $attemptedPath = realpath($allowedRoot.DIRECTORY_SEPARATOR.$path);
        $this->abortIf($attemptedPath === false, 404);
        $this->abortIf(! str_starts_with($attemptedPath, $allowedRoot), 404);
        $this->abortIf(! is_file($attemptedPath), 404);

        return response()->file($attemptedPath);
    }

    protected function abortIf(bool $condition, int $status): void
    {
        if ($condition) {
            abort($status);
        }
    }
}
