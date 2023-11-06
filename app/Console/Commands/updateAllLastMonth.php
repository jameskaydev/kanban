<?php

namespace App\Console\Commands;

use App\Models\kpi_values;
use App\Models\User;
use App\Models\Vacations;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Morilog\Jalali\Jalalian;

class updateAllLastMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:updateAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'calculate all leaves';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() 
    {
        $current_month = Jalalian::fromCarbon(new Carbon());
        $current_month = (new Jalalian($current_month->getYear(),$current_month->getMonth(),1));
        $current_month = $current_month->subDays(1);


        $users = User::get();

        foreach($users as $u){
           

            if($u->last_leave_update != ''){
                $this_month_ = Jalalian::fromDateTime($u['last_leave_update']);
                $this_month_ = (new Jalalian($this_month_->getYear(),$this_month_->getMonth(),1));
                echo $this_month_;
            }else{
                $this_month_ = (new Jalalian(1401,10,1));
            }
            $leave_time = 0;
            while(true){
                if($this_month_->lessThanOrEqualsTo($current_month)){
                    $leave_time += $this->this_month($u,$this_month_) + 19 * 60;
                    $this_month_ = $this_month_->addMonths(1);
                }else{
                    break;
                }
            }
            if($u->last_leave_update != ''){
                $u->leave_minute = $leave_time;
            }else{
                $u->leave_minute = $leave_time;
            }
            $u->save();
        }

        return Command::SUCCESS;
    }

    private function this_month($user,Jalalian $lastmonth){
        $from = (new Jalalian($lastmonth->getYear(),$lastmonth->getMonth(),1))->toCarbon();
        $to = (new Jalalian($lastmonth->getYear(),$lastmonth->getMonth(),$lastmonth->getMonthDays()))->toCarbon();

        $to = $to->addDays(1);


        $total_work_time = kpi_values::where('user_id',$user->id)->whereBetween('created_at',[$from,$to])->orderBy("created_at")->where('kpi_id','6')->get();
       
        $sum_minutes = 0;
        $vacations_til_now = Vacations::whereBetween('date',[$from,$to])->count();
        foreach($total_work_time as $time){
            $explodedTime = array_map('intval' , explode(":",$time['value']));
            $sum_minutes += @$explodedTime[0] *60 + @$explodedTime[1];
        }
        $work_time_till_now = $sum_minutes;
      
        $target_time_till_now = ($lastmonth->getMonthDays() - $vacations_til_now) * 60 * 7.5;

        return $work_time_till_now - $target_time_till_now;
    }
}
