<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesCollection extends Model
{
    use HasFactory;
    protected $table = "fees_collections";
    protected $guarded = [];
}
