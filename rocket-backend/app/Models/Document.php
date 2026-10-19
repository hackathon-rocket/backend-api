<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends MongoModel
{
    protected $fillable = [
        'original_filename',
        'storage_path',
        'mime_type',
        'file_size',
        'document_type',
        'status',
        'page_count',
        'extracted_fields',
        'metadata',
        'error_message',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'page_count' => 'integer',
    ];

    public function pages()
    {
        return $this->hasMany(DocumentPage::class, 'document_id');
    }

    public function extractions()
    {
        return $this->hasMany(Extraction::class, 'document_id');
    }

}
