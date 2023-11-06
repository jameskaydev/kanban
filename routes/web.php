<?php

use Carbon\Carbon;
use App\Models\Logs;
use App\Models\User;
use App\Models\Casts;
use App\Models\Projects;
use App\Models\CallQueue;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\callback;
use App\Http\Controllers\Admin\KPIController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserKpiController;
use App\Http\Controllers\Admin\VacationsController;

use App\Http\Controllers\main\sendNotificationController;
use App\Models\Transactions;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/',"login",301);

Route::get('logout', 'App\Http\Controllers\CustomAuthController@logout')->name('logout');


Route::group(['middleware' => 'web','middleware' => 'userChecker'], function () {
    Route::auth();
    //Admin
    Route::prefix('Admin')->group(function () {
            Route::namespace("App\Http\Controllers\Admin")->group(function () {
                Route::get('Dashboard', 'KanbanController@index');

                Route::post('Kanbans/Boards','KanbanController@storeBoard')->name('Kanbans.storeBoard');
                Route::post('Kanbans/Boards/{board_id}','KanbanController@updateBoard')->name('Kanbans.updateBoard');
                Route::delete('Kanbans/Boards/{board_id}','KanbanController@deleteBoard')->name('Kanbans.deleteBoard');
                Route::get('Kanbans/Boards/{board_id}','KanbanController@board')->name('Kanbans.board');
                Route::post('Kanbans/Boards/{board_id}/List','KanbanController@AddList')->name('Kanbans.AddList');
                Route::post('Kanbans/Boards/{board_id}/List/{List_id}/Card','KanbanController@AddCard')->name('Kanbans.AddCard');
                Route::put('Kanbans/Boards/Card/{id}','KanbanController@updateCard')->name('Kanbans.updateCard');
                Route::get('Kanbans/Boards/{board_id}/Lists/{archive?}','KanbanController@getLists')->name('Kanbans.getLists');
                Route::post('Kanbans/Boards/{board_id}/List/{List_id}/Card/{card_id}/Move','KanbanController@MoveCard')->name('Kanbans.MoveCard');
                Route::get('Kanbans/getCardsActivities/{card_id}','KanbanController@getCardsActivities')->name('Kanbans.getCardsActivities');
                Route::get('Kanbans/BoardsList/{id}/Remove','KanbanController@RemoveBoard')->name('Kanbans.RemoveBoard');
                Route::post('BoardsList/{list_id}/edit','KanbanController@EditList')->name('Kanbans.EditList');
                Route::post('Kanbans/Boards/Card/{id}/Comment','KanbanController@sendComment')->name('Kanbans.sendComment');
                Route::get('Kanbans/getCardsComments/{card_id}','KanbanController@getCardsComments')->name('Kanbans.getCardsComments');
                Route::delete('Kanbans/Card/{card_id}/delete','KanbanController@deleteCard')->name('Kanbans.deleteCard');
                Route::post('Kanbans/Boards/{board_id}/List/Reorder','KanbanController@reorder_list')->name('Kanbans.reorder_list');
                Route::post('Kanbans/Boards/{board_id}/Card/Reorder','KanbanController@reorder_list')->name('Kanbans.reorder_list');
                Route::post('Kanbans/Card/{card_id}/archive','KanbanController@archiveCard')->name('Kanbans.archiveCard');
                Route::post('Kanbans/Card/{card_id}/restore','KanbanController@restoreCard')->name('Kanbans.restoreCard');
                Route::get('Kanbans/Boards/{board_id}/archived','KanbanController@archivedBoard')->name('Kanbans.archivedBoard');

                //notification
                Route::patch('fcm_token','UsersNotifController@fcm_token')->name('fcmToken');

            });
        Route::get('Lang/Change/{lang}','App\Http\Controllers\HomeController@change_Language');
        Route::post('Upload', 'App\Http\Controllers\Admin\UploadController@upload');
        Route::post('Delete/{name}', 'App\Http\Controllers\Admin\UploadController@delete');
        Route::get('Download', 'App\Http\Controllers\Admin\UploadController@download');
    });
});




