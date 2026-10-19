<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OcrClient
{
    public function recognize(string $contents, string $filename, ?string $lang = null): array
    {
        return Http::timeout(config('services.ocr.timeout'))
            ->attach('file', $contents, $filename)
            ->withQueryParameters([
                'lang' => $lang ?? config('services.ocr.lang'),
            ])
            ->post(config('services.ocr.url') . '/ocr-upload')
            ->throw()
            ->json();
    }
}