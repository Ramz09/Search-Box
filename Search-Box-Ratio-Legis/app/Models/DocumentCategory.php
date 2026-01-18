<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    protected $fillable = ['name', 'description'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'document_category_id');
    }
}
