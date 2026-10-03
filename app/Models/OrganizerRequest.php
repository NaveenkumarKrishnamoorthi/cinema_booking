<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizerRequest extends Model
{
    protected $fillable = [
        'user_id',
        'reason',
        'status',
        'reviewed_at',
        'reviewed_by',
   