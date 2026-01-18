<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_name',
        'extracted_content',
        'document_type_id',
        'document_status_id',
        'document_category_id',
    ];

    // Relations
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function documentStatus()
    {
        return $this->belongsTo(DocumentStatus::class, 'document_status_id');
    }

    public function documentCategory()
    {
        return $this->belongsTo(DocumentCategory::class, 'document_category_id');
    }

    // Accessors for backward compatibility
    public function getTypeAttribute()
    {
        return $this->documentType?->name;
    }

    public function getStatusAttribute()
    {
        return $this->documentStatus?->name;
    }

    public function getCategoryAttribute()
    {
        return $this->documentCategory?->name;
    }
}

