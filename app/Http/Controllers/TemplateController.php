<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;

class DocumentTemplateController extends Controller
{
    public function index(): JsonResponse
{
    $templatePath = resource_path('views/secretary/documents');

    if (!File::exists($templatePath)) {
        return response()->json(['message' => 'Template folder not found.'], 404);
    }

    $files = File::files($templatePath);

    $templates = [];

    foreach ($files as $file) {
        $filename = $file->getFilenameWithoutExtension();
        $title = ucwords(str_replace('_', ' ', $filename));

        $templates[] = [
            'key' => $filename,
            'title' => "Barangay $title",
            'template_path' => "secretary.documents.$filename",
        ];
    }

    return response()->json($templates);
}
}