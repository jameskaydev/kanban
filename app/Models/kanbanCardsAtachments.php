<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kanbanCardsAtachments extends Model
{
    use HasFactory;
    protected $table = 'kanban_cards_atachments';
    protected $fillable = [
        'card_id',
        'file'
    ];
}
