<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extraction extends MongoModel
{
    protected $fillable = [
        'document_id',
        'engine',
        'language',
        'filename',
        'source',
        'text',
        'raw',
        'tables',
        'result',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
