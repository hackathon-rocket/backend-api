<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Contracts\Orchestrator;
use App\Models\Document;
use Throwable;

class ProcessDocumentExtraction implements ShouldQueue
{
    use Queueable;

    
    public int $tries = 2;
    public array $backoff = [30];
    public int $timeout = 150;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $documentId)
    {

    }


    /**
     * Execute the job.
     */
    public function handle(Orchestrator $orchestrator): void
    {
        $document = Document::findOrFail($this->documentId);
        $document->update(['status' => 'extracting']);

        $extraction = $document->extractions()->latest()->firstOrFail();
        $result = $orchestrator->extract((string) $document->id, $extraction->text);

        $extraction->update(['result' => $result]);
        $document->update(['status' => 'completed', 'error_message' => null]);
    }

    public function failed(Throwable $exception): void
    {
        $document = Document::find($this->documentId)?->update(['status' => 'failed', 'error_message' => $exception->getMessage()]);
    }
}
