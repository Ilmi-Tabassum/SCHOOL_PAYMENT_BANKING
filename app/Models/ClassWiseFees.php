<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassWiseFees extends Model
{
    use HasFactory;
    protected $table = "class_wise_fees";
    protected $guarded = [];
}
