<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTable extends MongoModel
{
    protected $fillable = [
        'table_index',
        'title',
        'page_number',
        'columns',
        'rows',
    ];

    protected $casts = [
        'table_index' => 'integer',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
