<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ApiDocumentationController extends Controller
{
    public function index()
    {
        $apiBaseUrl = config('app.url') . '/api';
        
        // Read the Swagger JSON file
        $apiDocsPath = storage_path('api-docs/api-docs.json');
        $apiDocs = [];
        
        if (File::exists($apiDocsPath)) {
            $apiDocsContent = File::get($apiDocsPath);
            $apiDocs = json_decode($apiDocsContent, true);
        }
        
        return view('dashboard.api-documentation.index', compact('apiBaseUrl', 'apiDocs'));
    }
}
