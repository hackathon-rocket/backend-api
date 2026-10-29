<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $extraction = $this->extractions->sortByDesc('created_at')->first();
        $result = $extraction?->result;
        $counts = collect($result['fields'] ?? [])->countBy('status');
        [$status, $stage] = $this->publicStatus();  
        return [
            'document' => [
                'id' => (string) $this->id,
                'filename' => $this->original_filename,
                'mime_type' => $this->mime_type,
                'file_size' => $this->file_size,
                'created_at' => $this->created_at,
                'status' => $status,
                'stage' => $stage,
                'error_message' => $this->error_message,
            ],

            
            'ocr' => $this->when($request->query('include') === 'ocr' && $extraction, fn () => [
                'text' => $extraction->text,
                'source' => $extraction->source,
                'language' => $extraction->language,
            ]),

            'extracted' => $result ? [
                'document_type' => $result['document_type'] ?? null,
                'fields' => $result['fields'] ?? [],
                'tables' => $result['tables'] ?? [],
                'extracted_data' => $result['extracted_data'] ?? null,
            ] : null,

            'validation' => $result ? [
                'status' => ($result['requires_review'] ?? false) ? 'needs_review' : 'ok', 
                'mapping_status' => $result['mapping_status'] ?? null, 
                'warnings' => $result['warnings'] ?? [],
                'counts' => [
                    'verified' => $counts['verified'] ?? 0,
                    'needs_review' => $counts['needs_review'] ?? 0,
                    'not_found' => $counts['not_found'] ?? 0,
                ]
            ] : null,
        ];
    }

    private function publicStatus(): array
    {
        return match ($this->status) {
            'uploaded' => ['uploaded', null],
            'processing' => ['processing', 'ocr'],
            'ocr_completed', 'extracting' => ['processing', 'extraction'],
            'completed' => ['extracted', null],
            'failed', 'ocr_failed' => ['failed', null],
            default => [$this->status, null],
        };
    }


}   