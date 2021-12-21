<?php
namespace App\Exports;
use Illuminate\Database\Eloquent\Model;
class Bulk extends Model
{
    protected $table = 'bulk';
    protected $fillable = [
        'name', 'email',
    ];
}
