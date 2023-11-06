<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kanbanLists extends Model
{
    use HasFactory;
    protected $table = 'kanban_lists';
    protected $fillable = [
        'kanban_id',
        'title',
        'list_order'
    ];
}
