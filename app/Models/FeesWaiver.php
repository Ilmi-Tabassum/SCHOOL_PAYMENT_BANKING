<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesWaiver extends Model
{
    use HasFactory;
    protected $table = "fees_waivers";
    protected $guarded = [];
}
