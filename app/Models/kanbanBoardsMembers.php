<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kanbanBoardsMembers extends Model
{
    use HasFactory;
    protected $table = 'kanban_boards_members';

    protected $fillable = [
        'user_id',
        'board_id'
    ];
}
