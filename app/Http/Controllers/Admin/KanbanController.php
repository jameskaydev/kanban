<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\main\sendNotificationController;
use App\Models\kanbanBoards;
use App\Models\kanbanBoardsMembers;
use App\Models\KanbanCards;
use App\Models\kanbanCardsAssigns;
use App\Models\kanbanCardsAtachments;
use App\Models\KanbanCardsComments;
use App\Models\kanbanEvents;
use App\Models\kanbanLists;
use App\Models\User;
use App\Traits\MRValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    use MRValidation;

    private $notify = '';
    public function __construct()
    {
        $this->notify =  new sendNotificationController();
        $this->middleware('auth');
    }

    /** 
     * Display the specified resource.
     *
     * @param
     * @return view auth.Admin.Roles.List
     *
     * GET Admin/KPIs
     */
    public function index()
    {
        $users = User::where('id', '!=', Auth::user()->id)
            ->orderBy('fullname')
            ->get();

        $boards = $this->creatArrayContentForBoards();

        return view('auth.Admin.Kanban.Kanban', [
            'users' => $users,
            'boards' => $boards,
            "pageName" => __('admin.Kanban'),
        ]);
    }

    /**
     * Store data
     *
     * @param
     * @return view auth.Admin.KPIs.Add
     *
     * POST Admin/KPIs
     */
    public function storeBoard(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $kanbanBoards = kanbanBoards::create([
            'user_id' => Auth::user()->id,
            'title' => $request->post('title'),
            'description' => $request->post('description')
        ]);

        kanbanLists::create([
            'kanban_id' => $kanbanBoards['id'],
            'title' => "To Do",
            'list_order' => 1
        ]);
        kanbanLists::create([
            'kanban_id' => $kanbanBoards['id'],
            'title' => 'Doing',
            'list_order' => 2
        ]);
        kanbanLists::create([
            'kanban_id' => $kanbanBoards['id'],
            'title' => 'Done',
            'list_order' => 3
        ]);

        $this->saveKanbanEvents("created", " created the baord", $kanbanBoards['id']);

        $this->saveKarbanMembers($request->post('members'), $kanbanBoards['id']);

        return redirect()->back()->with('success', __('messages.Successfully_Added'));
    }

    /**
     * update the resource
     *
     * @param
     * @return view auth.Admin.KPIs.Add
     *
     * PUT/PATCH Admin/KPIs/{KPI}
     */
    public function updateBoard(Request $request, $board_id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $kanban = kanbanBoards::where('id', $board_id)->first();
        if ($kanban['user_id'] != Auth::user()->id) {
            abort(403);
        }
        $lables = [];
        if ($kanban['lables'] != '' && $kanban['lables'] != 'null') {
            $lables = json_decode($kanban['lables'], 1);
        }
        $lables = $this->createLablelist($request->post("label"), $request->post('label_title'), $lables);
        kanbanBoards::where('id', $board_id)->update([
            'user_id' => Auth::user()->id,
            'title' => $request->post('title'),
            'description' => $request->post('description'),
            'lables' => $lables
        ]);

        $changed_content = [];
        if ($kanban['title'] != $request->post('title')) {
            $changed_content[] = "Title from " . $kanban['title'] . " to " . $request->post('title');
        }
        if ($kanban['description'] != $request->post('description')) {
            $changed_content[] = "Description from " . $kanban['description'] . " to " . $request->post('description');
        }

        if (count($changed_content) > 0) {
            $this->saveKanbanEvents("created", " updated " . $request->post('title'), $board_id, "", $changed_content);
        }

        $this->saveKarbanMembers($request->post('members'), $board_id);

        return redirect()->back()->with('success', __('messages.Successfully_Added'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return Redirect
     *
     * DELETE /Admin/KPIs/{KPI}
     */
    public function deleteBoard($id)
    {
        $kanban = kanbanBoards::where('id', $id)->first();
        if ($kanban['user_id'] != Auth::user()->id) {
            abort(403);
        }

        kanbanBoards::where('id', $id)->delete();
        $this->saveKanbanEvents("created", " deleted the baord", $id);
        return redirect()->back()->with('success', __('messages.Successfully_Deleted'));
    }

    /**
     * Add card to Board
     *
     * @param  $id
     * @return Redirect
     *
     * DELETE /Admin/KPIs/{KPI}
     */
    public function AddList(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
        ]);
        $list_order = kanbanLists::where('kanban_id', $id)->count() + 1;
        kanbanLists::create([
            'kanban_id' => $id,
            'title' => $request->post('title'),
            'list_order' => $list_order
        ]);

        $this->saveKanbanEvents("created", " added " . $request->post('title') . " to this board", $id);
        return redirect()->back()->with('success', __('messages.Successfully_Added'));
    }

    /**
     * Add card to Board
     *
     * @param  $id
     * @return Redirect
     *
     * DELETE /Admin/KPIs/{KPI}
     */
    public function AddCard(Request $request, $board_id, $list_id)
    {
        $request->validate([
            'title' => 'required',
        ]);
        
        $card_order = KanbanCards::where('list_id', $list_id)->count() + 1;

        $card = KanbanCards::create([
            'board_id' => $board_id,
            'list_id' => $list_id,
            'title' => $request->post('title'),
            'card_order' => $card_order
        ]);

        $lanbanList = kanbanLists::where('id', $list_id)->first();

        $this->saveKanbanEvents("created", " added " . $request->post('title') . " to " . $lanbanList['title'], $board_id, $card['id']);

        return redirect()->back()->with('success', __('messages.Successfully_Added'));
    }

    /**
     * Add card to Board
     *
     * @param  $id
     * @return Redirect
     *
     * DELETE /Admin/KPIs/{KPI}
     */
    public function updateCard(Request $request, $card_id)
    {
        $request->validate([
            'title' => 'required',
        ]);
        $card = KanbanCards::where('id', $card_id)->first();
        $check_access = $this->check_access($card['board_id']);
        if ($check_access == '') {
            abort(404);
        }

        if ($card == '') {
            abort(403);
        }

        KanbanCards::where('id', $card_id)->update([
            'title' => $request->post('title'),
            'due_date' => $request->post('due_date'),
            'description' => $request->post('description'),
            'label' => $request->post('label'),
        ]);

        $this->updateCardsAssigns($card_id, $request->post('assign'), $request->post('notif_via_sms'));
        $this->updateCardsAtachments($card_id, $request->post('atachments'));


        $changed_content = [];
        if ($card['title'] != $request->post('title')) {
            $changed_content[] = "Title from " . $card['title'] . " to " . $request->post('title');
        }
        if ($card['description'] != $request->post('description')) {
            $changed_content[] = "Description from " . $card['description'] . " to " . $request->post('description');
        }
        if (date("Y-m-d", strtotime($card['due_date'])) != date("Y-m-d", strtotime($request->post('due_date')))) {
            $changed_content[] = "Due date from " . $card['due_date'] . " to " . $request->post('due_date');
        }
        if ($card['label'] != $request->post('label')) {
            $changed_content[] = "label from " . $card['label'] . " to " . $request->post('label');
        }
        if (count($changed_content) > 0) {
            $this->saveKanbanEvents("created", " updated " . $request->post('title'), $card['board_id'], $card_id, $changed_content);
        }
        return redirect()->back()->with('success', __('messages.Successfully_Added'));
    }

    public function MoveCard(Request $request, $board_id, $list_id, $card_id)
    {
        $check_access = $this->check_access($board_id);
        if ($check_access == '') {
            abort(404);
        }

        $card_old = KanbanCards::where('id', $card_id)->first();
        KanbanCards::where('id', $card_id)->update([
            'list_id' => $list_id
        ]);

        $this->reorder_card($request->post('data'),$board_id);

        $card = KanbanCards::where('id', $card_id)->first();
        $list_old = kanbanLists::where('id', $card_old['list_id'])->first();
        $list_new = kanbanLists::where('id', $card['list_id'])->first();


        $this->saveKanbanEvents("created", " moved " . $card['title'] . " from " . $list_old['title'] . " to " . $list_new['title'], $card['board_id'], $card_id);

        return response()->json([
            'success' => __('messages.Successfully_Added')
        ]);
    }
    /**
     * get Cards
     *
     * @param  $id
     * @return Redirect
     *
     * DELETE /Admin/KPIs/{KPI}
     */
    public function getLists($board_id, $archive = '')
    {
        $check_access = $this->check_access($board_id);
        if ($check_access == '') {
            return response()->json([
                'success' => false,
                'message' => 'you do not have access to this board'
            ], 403);
        }

        $Content = $this->createCardsDetails($board_id, $archive);
        return response()->json($Content, 200);
    }

    private function saveKarbanMembers($members, $board_id)
    {
        kanbanBoardsMembers::where('board_id', $board_id)->delete();
        if ($members != '') {
            foreach ($members as $c) {
                kanbanBoardsMembers::create([
                    'board_id' => $board_id,
                    'user_id' => $c
                ]);
            }
        }
    }

    public function board($board_id, $archive = '')
    {
        $check_access = $this->check_access($board_id);
        if ($check_access == '') {
            abort(404);
        }
        $board = kanbanBoards::where('id', $board_id)->first();
        $users = User::join('kanban_boards_members', 'kanban_boards_members.user_id', '=', 'users.id')
            ->where('board_id', $board_id)
            ->orderBy('users.fullname')
            ->get([
                'users.fullname',
                'users.id'
            ]);

        $allUsers = User::where('id', '!=', Auth::user()->id)
            ->orderBy('fullname')
            ->get();

        $events = kanbanEvents::join('users', 'users.id', '=', 'kanban_events.user_id')
            ->where('board_id', $board_id)
            ->orderBy('id', 'desc')
            ->get([
                'kanban_events.*',
                'users.fullname'
            ]);

        $kanban_members = kanbanBoardsMembers::join('kanban_boards', 'kanban_boards.id', '=', 'kanban_boards_members.board_id')
            ->where('board_id', $board_id)
            ->pluck('kanban_boards_members.user_id')
            ->toArray();



        return view('auth.Admin.Kanban.board', [
            "pageName" => __('admin.Board') . " - " . $board['title'],
            'board_id' => $board_id,
            'users' => $users,
            'events' => $events,
            'board' => $board,
            'kanban_members' => $kanban_members,
            'allUsers' => $allUsers,
            'archive' => $archive
        ]);
    }

    private function creatArrayContentForBoards()
    {
        $boards = kanbanBoards::leftJoin('kanban_boards_members', 'kanban_boards_members.board_id', '=', 'kanban_boards.id')
            ->join('users', 'kanban_boards.user_id', '=', 'users.id')
            ->where('kanban_boards.user_id', Auth::user()->id)
            ->orWhere('kanban_boards_members.user_id', Auth::user()->id)
            ->get([
                'kanban_boards.*',
                'users.avatar',
                'users.id as user_id',
                'users.fullname'
            ]);

        $Content = [];

        foreach ($boards as $b) {
            $Content[$b['id']]['title'] = $b['title'];
            $Content[$b['id']]['description'] = $b['description'];
            $Content[$b['id']]['id'] = $b['id'];
            $Content[$b['id']]['user_id'] = $b['user_id'];

            $members = kanbanBoardsMembers::join('users', 'users.id', '=', 'kanban_boards_members.user_id')
                ->where('board_id', $b['id'])->get([
                    'users.avatar',
                    'users.id',
                    'users.fullname',
                ]);

            $Content[$b['id']]['members'] = $members;
            $Content[$b['id']]['members'][] = [
                'avatar' => $b['avatar'],
                'id' => $b['user_id'],
                'fullname' => $b['fullname'],
            ];

            $member_ids = [];
            foreach ($members as $m) {
                $member_ids[] = $m['id'];
            }
            $Content[$b['id']]['members_ids'] = $member_ids;
        }

        return $Content;
    }

    private function createCardsDetails($board_id, $archive)
    {
        $lists = kanbanLists::where('kanban_id', $board_id)->orderBy('list_order')->get();

        $Content = [];
        $num = 0;
        foreach ($lists as $l) {
            $Content[$num] = [
                'id' => 'list-' . $l['id'],
                'title' => $l['title']
            ];

            if ($archive == "") {
                $status = "active";
            } else {
                $status = "archived";
            }
            $cards = KanbanCards::where('board_id', $board_id)
                ->where('list_id', $l['id'])
                ->where('status', $status)
                ->orderBy('card_order')
                ->get();

            foreach ($cards as $c) {
                $Content[$num]['item'][] = $this->getCardsContent($c);
            }
            $num++;
        }

        if ($archive != '') {
            $cards = KanbanCards::where('board_id', $board_id)
                ->where('list_id', "-1")
                ->where('status', $status)
                ->orderBy('card_order')
                ->get();
            foreach ($cards as $c) {
                $Content[0]['item'][] = $this->getCardsContent($c);
            }
        }

        return $Content;
    }

    private function getCardsContent($c)
    {
        $Content = [];
        $num = 0;
        $assigned = User::join('kanban_cards_assigns', 'kanban_cards_assigns.user_id', '=', 'users.id')
            ->where('kanban_cards_assigns.card_id', $c['id'])->get([
                'users.*'
            ]);

        foreach ($assigned as $a) {
            if ($a['avatar_image']['type'] == "text") {
                $Content[$num]['assigned'][] = "text-" . $a['avatar_image']['text'];
            } else {
                $Content[$num]['assigned'][] = "image-" . $a['avatar_image']['image'];
            }
            $Content[$num]['members'][] = $a['fullname'];
            $Content[$num]['members_id'][] = $a['id'];
        }

        $atachmentFiles = kanbanCardsAtachments::where('card_id', $c['id'])->get();

        foreach ($atachmentFiles as $a) {
            $Content[$num]['files'][] = $a['file'];
        }

        return [
            'id' => 'item-' . $c['id'],
            'title' => $c['title'],
            'longtitle' => $c['title'],
            'description' => $c['description'],
            'due-date' => ($c['due_date'] != '') ? date("Y-m-d", strtotime($c['due_date'])) : "",
            'badge' => $c['label'],
            'attachments' => kanbanCardsAtachments::where('card_id', $c['id'])->count(),
            'comments' => KanbanCardsComments::where('card_id', $c['id'])->count(),
            'assigned' => @$Content[$num]['assigned'],
            'members' => @$Content[$num]['members'],
            'members_id' => @$Content[$num]['members_id'],
            'file' => @$Content[$num]['file'],
        ];
    }
    private function updateCardsAssigns($card_id, $assigns, $sms)
    {
        $card = KanbanCards::where('id', $card_id)->first();
        $last_member_id = kanbanCardsAssigns::where('card_id', $card_id)->pluck('user_id')->toArray();
        kanbanCardsAssigns::where('card_id', $card_id)->delete();
        $added_people = [];
        $added_people_id = [];
        if ($assigns != '') {
            foreach ($assigns as $c) {
                if (!in_array($c, $last_member_id)) {
                    $user = User::where('id', $c)->first();
                    $added_people[] = $user['fullname'];
                    $added_people_id[] = $user['id'];
                }
                kanbanCardsAssigns::create([
                    'card_id' => $card_id,
                    'user_id' => $c
                ]);
            }
        }

        if (count($added_people) > 0) {
            foreach ($added_people_id as $ap) {
                $user = User::where('id', $ap)->first();
                $message = "You have been added to " . $card['title'] . " card by " . Auth::user()->fullname;
                $this->send_notif($user, $message, $message, $sms);
            }
            $event_description = " added these people to this card:";
            $this->saveKanbanEvents("add_people", $event_description, $card['board_id'], $card_id, $added_people);
        }
    }
    private function updateCardsAtachments($card_id, $files)
    {
        $card = KanbanCards::where('id', $card_id)->first();
        $last_files = kanbanCardsAtachments::where('card_id', $card_id)->pluck('file')->toArray();

        kanbanCardsAtachments::where('card_id', $card_id)->delete();

        $added_files = [];
        if ($files != '') {
            foreach ($files as $c) {
                if (!in_array($c, $last_files)) {
                    $added_files[] = $c;
                }
                kanbanCardsAtachments::create([
                    'card_id' => $card_id,
                    'file' => $c
                ]);
            }
        }

        if (count($added_files) > 0) {
            $s = (count($added_files) > 1) ? "s" : "";
            $event_description = " added " . count($added_files) . " file" . $s;
            $this->saveKanbanEvents("add_people", $event_description, $card['board_id'], $card_id);
        }
    }

    private function saveKanbanEvents($title, $description, $board_id, $card_id = "", $array_content = [])
    {
        kanbanEvents::create([
            'title' => $title,
            'description' => $description,
            'board_id' => $board_id,
            'user_id' => Auth::user()->id,
            'card_id' => $card_id,
            'array_content' => json_encode($array_content, JSON_UNESCAPED_UNICODE)
        ]);
    }

    public function getCardsActivities($card_id)
    {
        $card = KanbanCards::where('id', $card_id)->first();

        $check_access = $this->check_access($card['board_id']);
        if ($check_access == '') {
            abort(404);
        }

        $events = kanbanEvents::join('users', 'users.id', '=', 'kanban_events.user_id')
            ->where('card_id', $card_id)
            ->orderBy('id', 'desc')
            ->get([
                'kanban_events.*',
                'users.fullname'
            ]);

        return response()->json([
            'success' => true,
            'Content' => $events
        ]);
    }

    public function getCardsComments(Request $request, $card_id)
    {
        $card = KanbanCards::where('id', $card_id)->first();

        $check_access = $this->check_access($card['board_id']);
        if ($check_access == '') {
            abort(404);
        }

        $comments = KanbanCardsComments::join('users', 'users.id', '=', 'kanban_card_comments.user_id')
            ->where('card_id', $card_id)
            ->orderBy('id', 'desc')
            ->get([
                'kanban_card_comments.*',
                'users.fullname'
            ]);

        return response()->json([
            'success' => true,
            'Content' => $comments
        ]);
    }

    private function check_access($board_id)
    {
        $board = kanbanBoards::leftJoin('kanban_boards_members', 'kanban_boards_members.board_id', '=', 'kanban_boards.id')
            ->where('kanban_boards.id', $board_id)
            ->where('kanban_boards.user_id', Auth::user()->id)
            ->orWhere('kanban_boards_members.user_id', Auth::user()->id)
            ->first([
                'kanban_boards.*'
            ]);

        if ($board == '') {
            return false;
        } else {
            return true;
        }
    }

    public function RemoveBoard($list_id)
    {
        $list = kanbanLists::where('id', $list_id)->first();
        $check_access = $this->check_access($list['kanban_id']);
        if ($check_access == '') {
            abort(404);
        }

        kanbanLists::where('id', $list_id)->delete();

        $cards = KanbanCards::where('list_id', $list_id)->where('status','active')->pluck('id')->toArray();
        KanbanCards::where('list_id', $list_id)->where('status','active')->delete();
        KanbanCards::where('list_id', $list_id)->update([
            'list_id' => "-1"
        ]);
        kanbanCardsAssigns::whereIn('card_id', $cards)->delete();
        kanbanCardsAtachments::whereIn('card_id', $cards)->delete();

        return response()->json([
            'success' => true,
            'message' => 'list has been deleted'
        ]);
    }


    public function EditList(Request $request, $list_id)
    {
        $request->validate([
            'title' => 'required'
        ]);

        kanbanLists::where('id', $list_id)->update([
            'title' => $request->post('title')
        ]);

        return redirect()->back()->with('success', __('messages.edited_successfully'));
    }

    public function sendComment(Request $request, $card_id)
    {
        $request->validate([
            'message' => 'required'
        ], MRValidation::GENERAL());
        $card = KanbanCards::where('id', $card_id)->first();

        $check_access = $this->check_access($card['board_id']);
        if ($check_access == '') {
            abort(404);
        }

        KanbanCardsComments::create([
            'card_id' => $card_id,
            'message' => $request->post('message'),
            'user_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Comment has been send');
    }
    public function deleteCard(Request $request, $card_id)
    {
        $card = KanbanCards::where('id', $card_id)->first();
        if (Auth::user()->id == $card['user_id']) {
            return redirect()->back()->with('error', __('messages.access'));
        }
        KanbanCards::where('id', $card_id)->delete();

        kanbanCardsAssigns::where('card_id', $card_id)->delete();
        kanbanCardsAtachments::where('card_id', $card_id)->delete();

        return redirect()->back()->with('success', __('messages.Successfully_Deleted'));
    }
    public function reorder_list(Request $request, $board_id)
    {
        $data = json_decode($request->post('data'), 1);

        $baord = kanbanBoards::where('id', $board_id)->first();

        if ($baord['user_id'] != Auth::user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'you don\'t have access to reorder lists in this board'
            ]);
        }

        foreach ($data as $d) {
            kanbanLists::where('id', $d['id'])->update([
                'list_order' => $d['order']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reordered'
        ]);
    }

    public function reorder_card($data, $board_id)
    {
        $data = json_decode($data, 1);

        foreach ($data as $d) {
            KanbanCards::where('id', $d['id'])->update([
                'card_order' => $d['order']
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reordered'
        ]);
    }

    private function send_notif($user, $message, $fa_message, $sms)
    {

        $param = [
            'providers' => [
                'notif',
                'push_notification'
            ],
            'message' => $message,
            'fa_message' => $fa_message,
            'fa_title' => 'پرداخت حقوق',
            'send_push_notification' => true,
            'title' => 'Payment Alert',
            'icon' => 'ti ti-credit-card',
            'link' => url('Admin/Transactions'),
            'send_sms' => true,
            'send_notif' => true,
            'sended_by' => 'system',
            'receivers' => $user['fullname']
        ];
        if ($sms) {
            $param['providers'][] = 'sms';
        }
        $this->notify->send($user['id'], $param);
    }

    private function createLablelist($lable, $lable_title, $lables)
    {
        $lables_list = [
            'success' => '',
            'warning' => '',
            'info' => '',
            'danger' => '',
            'secondary' => '',
            'primary' => '',
        ];
        $content = [];
        foreach ($lables_list as $key => $ll) {
            $content[$key] = @$lables[$key];
        }

        $content[$lable] = $lable_title;

        return json_encode($content, JSON_UNESCAPED_UNICODE);
    }
    public function archiveCard(Request $request, $card_id)
    {
        $card = KanbanCards::where('id', $card_id)->first();
        if (Auth::user()->id == $card['user_id']) {
            return redirect()->back()->with('error', __('messages.access'));
        }
        KanbanCards::where('id', $card_id)->update([
            'status' => 'archived',
        ]);
        return redirect()->back()->with('success', "Archived");
    }

    public function restoreCard(Request $request, $card_id)
    {
        $card = KanbanCards::where('id', $card_id)->first();
        if (Auth::user()->id == $card['user_id']) {
            return redirect()->back()->with('error', __('messages.access'));
        }
        $list = kanbanLists::where('id',$card['list_id'])->first();
        if($list == ''){
            $first_list = kanbanLists::where('kanban_id',$card['board_id'])->first();
            $list_id = $first_list['id'];
        }else{
            $list_id = $list['id'];
        }
        KanbanCards::where('id', $card_id)->update([
            'status' => 'active',
            'list_id' => $list_id
        ]);
        return redirect()->back()->with('success', "Archived");
    }

    public function archivedBoard($board_id)
    {
        return $this->board($board_id, 'archive');
    }
}
