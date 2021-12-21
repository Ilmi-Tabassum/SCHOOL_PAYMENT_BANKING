<?php

namespace App\Models\officerpanel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends Model
{
    use HasFactory;
    protected $table = "users";
    protected $guarded = [];
}
