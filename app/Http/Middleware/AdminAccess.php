<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    private $vector = [
        'Users.index' => 'User List',
        'Users.show' => 'User List',
        'Users.filter' => 'User List',
        'Users.destroy' => 'Delete User',
        'Users.edit' => 'Edit user',
        'Users.update' => 'Edit user',
        'Users.status' => 'Edit user',
        'Users.from_last_month' => 'Edit user',
        'Users.create' => 'Add user',
        'Users.store' => 'Add user',
        'Users.Statistics' => 'Admin Statistics',
        'Users.Statistics_filter' => 'Admin Statistics',
        'Users.Edit_kpi_value' => 'Edit_kpi_value',
        'Users.save_kpi_admin' => 'Edit_kpi_value',
        'Users.date_controller' => 'KPI List',
        'Users.compensation_set' => 'Accounting Compensation',
        'Users.Summary_Report' => 'User List',
        'Users.Summary_Report_Ajax' => 'User List',
        'Users.SummaryReport' => 'Projects List', // user can see their own report
        'Users.SummaryReportAjax' =>'Projects List', // user can see their own report

        'Roles.index' => 'Role List',
        'Roles.show' => 'Role List',
        'Roles.destroy' => 'Delete Role',
        'Roles.edit' => 'Edit Role',
        'Roles.update' => 'Edit Role',
        'Roles.create' => 'Add Role',
        'Roles.store' => 'Add Role',
        'Roles.kpi_set' => 'KPI Assign Role',
        'Roles.Set_charts' => 'Edit Role',

        'MyKPIs.index' => 'KPI User List',
        'MyKPIs.show' => 'KPI User List',
        'MyKPIs.save_kpi' => 'KPI User List',
        'MyKPIs.go_to_date' => 'KPI User List',
        'MyKPIs.date_controller' => 'KPI User List',

        'KpIs.index' => 'KPI List',
        'KpIs.show' => 'KPI List',
        'KpIs.destroy' => 'Delete KPI',
        'KpIs.edit' => 'Edit KPI',
        'KpIs.update' => 'Edit KPI',
        'KpIs.create' => 'Add KPI',
        'KpIs.store' => 'Add KPI',
        'KpIs.reorder_set' => 'KPI List',

        'Statistics.MyStatistics' => 'User Statistics',
        'Statistics.Statistics' => 'Admin Statistics',
        'Statistics.filter' => 'User Statistics',


        'Vacations.index' => 'Vacations List',
        'Vacations.destroy' => 'Vacations Delete',
        'Vacations.create' => 'Vacations Add',
        'Vacations.store' => 'Vacations Add',
        'Vacations.Calender' => 'Vacations List',

        'Projects.index' => 'Projects List',
        'Projects.destroy' => 'Projects Delete',
        'Projects.create' => 'Projects Add',
        'Projects.store' => 'Projects Add',
        'Projects.edit' => 'Projects Edit',
        'Projects.update' => 'Projects Edit',
        'Projects.filter' => 'Projects List',
        'Projects.get_milestones' => 'Projects List',
        'Projects.set_milestones' => 'Projects List',
        'Projects.project_status' => 'Projects Edit',
        'Projects.get_users' => 'Projects Assign',
        'Projects.assign_project' => 'Projects Assign',
        'Projects.ownership_transfer' => 'Projects List',
        'Projects.view_project' => 'Projects List',

        'Platforms.index' => 'Platforms List',
        'Platforms.destroy' => 'Platforms Delete',
        'Platforms.create' => 'Platforms Add',
        'Platforms.store' => 'Platforms Add',
        'Platforms.edit' => 'Platforms Edit',
        'Platforms.update' => 'Platforms Edit',
        'Platforms.filter' => 'Platforms List',

        'Employrs.index' => 'Employrs List',
        'Employrs.destroy' => 'Employrs Delete',
        'Employrs.create' => 'Employrs Add',
        'Employrs.store' => 'Employrs Add',
        'Employrs.edit' => 'Employrs Edit',
        'Employrs.update' => 'Employrs Edit',
        'Employrs.filter' => 'Employrs List',
        'Employrs.Ajax_save' => 'Projects List',

        'Accounting.Exchange_store' => 'Accounting Exchange',
        'Accounting.Exchange_index' => 'Accounting Exchange',
        'Accounting.Get_Rate' => 'Accounting Exchange',
        'Accounting.Billing' => 'Accounting Billing',
        'Accounting.Statistics' => 'Accounting Statistics',
        'Accounting.Statistics_filter' => 'Accounting Statistics',
        'Accounting.GroupActionPay' => 'Accounting GroupActionPay',
        'Accounting.Date' => 'Accounting Billing',
        'Accounting.pay_salary' => 'Accounting Billing',
        'Accounting.pay_Debit' => 'Accounting Billing',
        'Debits.index' => 'Accounting List Debit',
        'Debits.create' => 'Accounting Add Debit',
        'Debits.store' => 'Accounting Add Debit',
        'Debits.edit' => 'Accounting Add Debit',
        'Debits.update' => 'Accounting Add Debit',
        'Debits.filter' => 'Accounting List Debit',
        'Debits.settle_up' => 'Accounting List Debit',
        'Debits.settle_up_debits' => 'Accounting List Debit',


        'AdminProject.index' => 'AdminProject List',
        'AdminProject.destroy' => 'AdminProject Delete',
        'AdminProject.create' => 'AdminProject Add',
        'AdminProject.store' => 'AdminProject Add',
        'AdminProject.edit' => 'AdminProject Edit',
        'AdminProject.update' => 'AdminProject Edit',
        'AdminProject.filter' => 'AdminProject List',
        'AdminProject.get_milestones' => 'AdminProject List',
        'AdminProject.set_milestones' => 'AdminProject List',
        'AdminProject.project_status' => 'AdminProject Edit',
        'AdminProject.get_percentages' => 'AdminProject Percentages',
        'AdminProject.set_percentages' => 'AdminProject Percentages',

        'Notifications.index' => 'Notifications List',
        'Notifications.destroy' => 'Notifications Send',
        'Notifications.create' => 'Notifications Send',
        'Notifications.store' => 'Notifications Send',
        'Notifications.edit' => 'Notifications Send',
        'Notifications.update' => 'Notifications Send',

        'ProjectsApproval.index' => 'Project_Approval_List',
        'ProjectsApproval.filter' => 'Project_Approval_List',
        'ProjectsApproval.approval' => 'Project_Approval_Approval',
        'ProjectsApproval.rejected' => 'Project_Approval_Reject',

        'transactions.list' => 'Transaction_User',
        'transactions.date' => 'Transaction_User',

        'admintransactions.list' => 'Transaction_Admin',
        'admintransactions.date' => 'Transaction_Admin',

        'callQueue.toggle_queue' => 'Call_Queue',
        'callQueue.spam_call' => 'Call_Queue',
        'callQueue.change_category_call' => 'Call_Queue',
        'CallLogs.index' => 'Call_Logs',
        'CallLogs.filter' => 'Call_Logs',
        'Call_Queue_List.index' => 'Call_Queue_List',
        'PenaltyManagement.QueuePenalty' => 'Call_Queue_Penalty',
        'PenaltyManagement.store' => 'Call_Queue_Penalty',
        'Call_Queue_List.Reorder' => 'Call_Queue_Reorder',
        'Call_Queue_List.Dismiss_Penalty' => 'Call_Queue_Penalty',
        'Call_Queue_List.setPenalty' => 'Call_Queue_Penalty',
        'callStatistics.index' => 'Call_Statistics',
        'callStatistics.filter' => 'Call_Statistics',

        'Accounts.index' => 'Accounts List',
        'Accounts.destroy' => 'Delete Accounts',
        'Accounts.create' => 'Edit Accounts',
        'Accounts.store' => 'Edit Accounts',
        'Accounts.edit' => 'Add Accounts',
        'Accounts.update' => 'Add Accounts',

        'Categories.index' => 'Categories List',
        'Categories.destroy' => 'Delete Categories',
        'Categories.create' => 'Edit Categories',
        'Categories.store' => 'Edit Categories',
        'Categories.edit' => 'Add Categories',
        'Categories.update' => 'Add Categories',

        'Kanbans.index' => 'Kanban',
        'Kanbans.storeBoard' => 'Create_Board',
        'Kanbans.updateBoard' => 'Kanban',
        'Kanbans.deleteBoard' => 'Kanban',
        'Kanbans.board' => 'Kanban',
        'Kanbans.AddList' => 'Kanban',
        'Kanbans.AddCard' => 'Kanban',
        'Kanbans.updateCard' => 'Kanban',
        'Kanbans.getLists' => 'Kanban',
        'Kanbans.MoveCard' => 'Kanban',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(@auth()->user()->can($this->vector[$request->route()->getName()])){
            return $next($request);
        }else{
            return redirect('Admin/Dashboard')->with('error',__('messages.access'));
        }
    }
}
