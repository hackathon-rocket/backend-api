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
        $payload = [
                'document_id' => $documentId,
                'content' => [
                    'language' => $language,
                    'pages' => [
                        ['page' => null, 'text' => $ocrText],
                    ]
                ]
            ];
        if ($formId = config('services.orchestrator.form_id')) {
            $payload['form'] = [
                'id' => $formId,
                'mapping_id' => config('services.orchestrator.mapping_id'),
            ];
        }
        
        return Http::withToken(config('services.orchestrator.token'))
            ->connectTimeout(3)
            ->timeout(config('services.orchestrator.timeout'))
            ->acceptJson()
            ->post(config('services.orchestrator.url') . '/v1/analyze', $payload)
            ->throw()
            ->json();
    }
}
