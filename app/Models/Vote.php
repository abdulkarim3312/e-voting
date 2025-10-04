<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'category_id',
        'candidate_id',
    ];
}
