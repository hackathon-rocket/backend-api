<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentPage extends MongoModel
{
    protected $fillable = [ 
        'page_number',
        'ocr_text',
        'status',
        'errror_message',
    ];

    protected $casts = [
        'page_number' => 'integer',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
    
    public function tables()
    {
        return $this->hasMany(DocumentTable::class, 'document_page_id');
    }
}
