<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    protected $fillable = [
        'keyword',
        'type',
        'status',
        'chip',
        'sort',
        'results_count',
        'ip',
        'user_agent',
    ];
}
