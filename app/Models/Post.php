<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    function creator() : BelongsTo {
        return $this->belongsTo(User::class, 'created_by');
    }

    function deleter() : BelongsTo {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
