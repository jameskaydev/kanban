<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kanbanCardsAssigns extends Model
{
    use HasFactory;
    protected $table = 'kanban_cards_assigns';
    protected $fillable = [
        'card_id',
        'user_id'
    ];
}
