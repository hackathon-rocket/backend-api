<?php

namespace App\Contracts;

interface Orchestrator
{
    public function extract(string $documentId, string $ocrText, string $language = 'slk+eng'): array;
}