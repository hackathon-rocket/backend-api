<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\OcrClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessDocumentOcr implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * Seconds to wait before retrying: 10s after the 1st failure, 60s after the 2nd.
     */
    public $backoff = [10, 60];

    /**
     * Max seconds the job may run (must be greater than the OCR HTTP timeout).
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $documentId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(OcrClient $ocrClient): void
    {
        $document = Document::findOrFail($this->documentId);
        $document->update(['status' => 'processing']);

        $content = Storage::disk('local')->get($document->storage_path);
        $result = $ocrClient->recognize($content, $document->original_filename);

        $document->extractions()->create([
            'engine' => 'ocr-api',
            'language' => $result['language'] ?? null,
            'filename' => $document->original_filename,
            'source' => $result['source'] ?? null,
            'text' => $result['text'] ?? '',
            'raw' => $result,
        ]);

        $document->update(['status' => 'ocr_completed', 'error_message' => null]);
        ProcessDocumentExtraction::dispatch($this->documentId);
    }

    public function failed(Throwable $exception): void
    {
        Document::find($this->documentId)?->update([
            'status' => 'ocr_failed',
            'error_message' => $exception->getMessage(),
        ]);
    }
}
