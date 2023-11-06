<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanCards extends Model
{
    use HasFactory;
    protected $table = 'kanban_cards';
    protected $fillable = [
        'board_id',
        'list_id',
        'title',
        'description',
        'due_date',
        'label',
        'status',
        'card_order'
    ];
}
