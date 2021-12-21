<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllNotification extends Model
{
    use HasFactory;
    protected $table = "all_notifications";
    protected $guarded = [];
}
