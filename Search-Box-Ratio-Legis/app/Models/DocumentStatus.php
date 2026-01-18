<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    protected $fillable = ['name', 'description'];

    public function documents()
    {
        return $this->hasMany(Document::class, 'document_status_id');
    }
}
