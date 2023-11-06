<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kanbanBoards extends Model
{
    use HasFactory;
    protected $table = 'kanban_boards';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'lables'
    ];

    protected $appends = [
        'label_array',
    ];

    public function getLabelArrayAttribute()
    {
        $Content = [];
        if($this->lables != '' && $this->lables != 'null' && $this->lables != null){
            $Content = json_decode($this->lables,1);
        }
        if(count($Content) > 0){
            return $Content;
        }else{
            return [
                'success' => '',
                'warning' => '',
                'info' => '',
                'danger' => '',
                'secondary' => '',
                'primary' => '',
            ];
        }
    }
}
