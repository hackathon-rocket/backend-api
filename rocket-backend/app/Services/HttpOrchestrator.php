<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Contracts\Orchestrator;

class HttpOrchestrator implements Orchestrator
{
    /**
     * Extract data from the given document.
     */
    public function extract(string $documentId, string $ocrText, string $language = 'slk+eng'): array
    {
        $response = Http::withToken(config('services.orchestrator.token'))->connectTimeout(3)->timeout(config('services.orchestrator.timeout'))->acceptJson()
            ->post(config('services.orchestrator.url') . '/v1/analyze', [
                'document_id' => $documentId,
                'content' => [
                    'language' => $language,
                    'pages' => [
                        ['page' => null, 'text' => $ocrText],
                    ]
                ]
            ])
            ->throw()->json();
        return $response;
    }
}
