<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Jobs\ProcessDocumentOcr;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::with(['pages', 'extractions'])->findOrFail($id);

        return response()->json($document);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:pdf,png,jpg,jpeg,tif,tiff,bmp,webp',
                'max:20480', // 20MB
            ]
        ]);

        $file = $request->file('file');

        $path = $file->store('documents', 'local');

        $document = Document::create([
            'original_filename' => $file->getClientOriginalName(),
            'storage_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'document_type' => 'unknown', // You can implement logic to determine the document type based on the file extension or content
            'status' => 'uploaded',
            'page_count' => null, // You can implement logic to determine the page count for supported file types
        ]);

        ProcessDocumentOcr::dispatch((string) $document->id);

        return response()->json(
            [
                'id' => (string) $document->id,
                'status' => $document->status,
                'message' => 'Document uploaded successfully',
            ],
            201
        );
    }
}
