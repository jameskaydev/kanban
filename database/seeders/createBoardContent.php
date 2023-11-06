<?php

namespace Database\Seeders;

use App\Models\kanbanBoards;
use App\Models\KanbanCards;
use App\Models\kanbanLists;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class createBoardContent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $board_1 = kanbanBoards::create([
            'user_id' => 1,
            'title' => 'board 1',
            'description' => 'a test board',
        ]);

        $label = [
            'success' => 'Completed',
            'warning' => 'High Priority',
            'info' => 'Progress',
            'danger' => 'Urgent',
            'secondary' => 'Pending',
            'primary' => 'Important'
        ];

        $board_2 = kanbanBoards::create([
            'user_id' => 1,
            'title' => 'board 2',
            'description' => 'a test board',
            'lables' => json_encode($label,JSON_UNESCAPED_UNICODE),
        ]);

        //create list for board 1
        $list_board_1_todo = $this->createList($board_1['id'], 'To Do');
        $list_board_1_doing = $this->createList($board_1['id'], 'Doing');
        $list_board_1_done = $this->createList($board_1['id'], 'Done');

        //create list for board 2
        $list_board_2_todo = $this->createList($board_2['id'], 'To Do');
        $list_board_2_doing = $this->createList($board_2['id'], 'Doing');
        $list_board_2_done = $this->createList($board_2['id'], 'Done');

        //create card for board 1
        $this->creaeCard(
            $board_1['id'],
            $list_board_1_todo,
            'test card for list To do',
            'this is a test board so this is a test task!'
        );

        //create cards for board 1
        $this->creaeCard(
            $board_1['id'],
            $list_board_1_todo,
            'test card for list To do',
            'this is a test board so this is a test task!'
        );

        $this->creaeCard(
            $board_1['id'],
            $list_board_1_todo,
            'test card for list To do(2)',
            'this is a test board so this is a test task!'
        );

        $this->creaeCard(
            $board_1['id'],
            $list_board_1_doing,
            'test card for list Doing(3)',
            'this is a test board so this is a test task!'
        );


        $this->creaeCard(
            $board_1['id'],
            $list_board_1_done,
            'test card for list Done(3)',
            'this is a test board so this is a test task!'
        );

        //create ccars for board 2
        $this->creaeCard(
            $board_2['id'],
            $list_board_2_todo,
            'Clean the Garage',
            'Dedicate time to organize and clean the garage. This may involve decluttering, sweeping, and reorganizing',
            'warning'
        );

        $this->creaeCard(
            $board_2['id'],
            $list_board_2_todo,
            'Review Monthly Budget',
            'Go over your financial statements and create a budget for the upcoming month, considering income and expenses.',
            'danger'
        );

        $this->creaeCard(
            $board_2['id'],
            $list_board_2_doing,
            'Read a Chapter of a Book',
            'Set aside time to read a chapter of a book you\'ve been wanting to finish.',
            'info'
        );

        $this->creaeCard(
            $board_2['id'],
            $list_board_2_doing,
            'Plan a Workout Routine',
            'Develop a workout plan for the week, specifying the type of exercises, duration, and schedule.',
            'success'
        );
       
        $this->creaeCard(
            $board_2['id'],
            $list_board_2_done,
            'Organize Digital Files',
            'Declutter your computer or cloud storage by arranging files and deleting unnecessary documents.',
            'primary'
        );
    }

    private function createList($board_id, $title)
    {
        $list = kanbanLists::create([
            'kanban_id' => $board_id,
            'title' => $title,
            'list_order' => kanbanLists::where('kanban_id',$board_id)->count() + 1
        ]);

        return $list['id'];
    }

    private function creaeCard($board_id, $list_id, $title, $description, $label = '')
    {
        KanbanCards::create([
            'board_id' => $board_id,
            'list_id' => $list_id,
            'title' => $title,
            'description' => $description,
            'card_order' => KanbanCards::count() + 1,
            'label' => $label
        ]);
    }
}
