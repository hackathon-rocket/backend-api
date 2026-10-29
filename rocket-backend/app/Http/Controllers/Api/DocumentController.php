<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Jobs\ProcessDocumentOcr;
use Illuminate\Http\Request;
use App\Http\Resources\DocumentResource;

class DocumentController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Document::query(); 

        if ($status = $request->query('status')) {
            $query->whereIn('status', Document::PUBLIC_STATUS_MAP[$status] ?? [$status]);
        }


        $documents = $query->with(['extractions'])->latest('created_at')->paginate(50);

        return DocumentResource::collection($documents);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::with(['extractions'])->findOrFail($id);
        return new DocumentResource($document);
    }

    /**
     * Confirm the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request, $id)
    {
        $document = Document::with(['extractions'])->findOrFail($id);
        if (! in_array($document->status, ['completed', 'confirmed'])) {
            return response()->json(['message' => 'Document is not in a confirmable state.'], 422);
        }
        $request->validate([
            'values' => ['present', 'array'],
            'tables' => ['present', 'array'],
            'tables.*' => ['array'],
        ]);

        $document->update([
            'confirmed_data' => [
                'values' => $request->input('values'),
                'tables' => $request->input('tables'),
            ],
            'confirmed_at' => now(),
            'status' => 'confirmed',
        ]);

        return new DocumentResource($document);

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
