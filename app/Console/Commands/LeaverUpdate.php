<?php

namespace App\Console\Commands;

use App\Models\kpi_values;
use App\Models\Settings;
use App\Models\User;
use App\Models\Vacations;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Morilog\Jalali\Jalalian;

class LeaverUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leave tracker';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lastmonth = Settings::where('seti_key','last_leave_update_month')->value('seti_value');
        $lastyear = Settings::where('seti_key','last_leave_update_year')->value('seti_value');
        $last_month = new Jalalian($lastyear,$lastmonth,1);
        $last_month = new Jalalian($lastyear,$lastmonth,$last_month->getMonthDays());

        $this_month = Jalalian::fromCarbon(new Carbon());
        if($this_month->greaterThan($last_month)){
            Settings::where('seti_key','last_leave_update_month')->update(['seti_value' => $this_month->getMonth()]);
            Settings::where('seti_key','last_leave_update_year')->update(['seti_value' => $this_month->getYear()]);

            $users = User::get();
            foreach($users as $u){
                $last_month_leave = $this->this_month($u,$last_month);
                User::where('id',$u->id)->update([
                    'leave_minute' => $u->leave_minute + $last_month_leave + 19 * 60
                ]);
            }
        }
    }

    private function this_month($user,Jalalian $lastmonth){
        $from = (new Jalalian($lastmonth->getYear(),$lastmonth->getMonth(),1))->toCarbon();
        $to = (new Jalalian($lastmonth->getYear(),$lastmonth->getMonth(),$lastmonth->getMonthDays()))->toCarbon();

        $to = $to->addDays(1);


        $total_work_time = kpi_values::where('user_id',$user->id)->whereBetween('created_at',[$from,$to])->where('kpi_id','6')->get();
       
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
