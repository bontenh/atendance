<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Repositories\Attendance\AttendanceRepositoryInterface AS Attendance;
use App\Models\Performance;

class AttendanceController extends Controller
{

    public function __construct(Attendance $attendance)
    {
        $this->attendance=$attendance;
    }

    //打刻画面移動
    public function move_attendance(Request $request){
        $school_id=$request->school_id;
        $select=$request->select;
        if($select==0){
            $users=$this->attendance->get_school_users($school_id);
        }else{
            $users=$this->attendance->get_select_names($school_id,$select);
        }
        
        $attendance_today=$this->attendance->get_attendance_today($users);
        
        $school_name=$this->attendance->get_school_name($school_id);
        $years=$this->get_year_now();
        $times=$this->get_time_now();
        $week=$this->get_week();
        
        $flag="list";

        return view('attendance',compact('school_name','school_id','users','attendance_today','years','times','week','flag'));
    }

    //打刻画面の当日出席前と当日出席中に移動
    public function move_doattendance(Request $request){
        $user=$this->attendance->get_user($request->user_id);
        $school_id=$user->school_id;
        
        $perform_user=$this->attendance->get_perform_user($request->user_id,date('Y-m-d'));
        
        if(isset($perform_user)){
            if(isset($perform_user->end)){
                //本日はお疲れさまでした画面
                $flag="end";
            }else{
                //退出画面
                $flag="out";
            }
        }else{
            //出席画面
            $flag="in";
        }

        $users=$this->attendance->get_school_users($school_id);
        $attendance_today=$this->attendance->get_attendance_today($users);
        
        $school_name=$this->attendance->get_school_name($school_id);
        $years=$this->get_year_now();
        $times=$this->get_time_now();
        $week=$this->get_week();

        return view('attendance',compact('school_name','school_id','user','users','attendance_today','years','times','week','flag'));
    }

    //打刻画面の出席の入室処理
    public function in_attendance(Request $request){
        $attendance_time=$this->get_attendance_time('in');
        $this->attendance->in_attendance($request,$attendance_time);
        $user=$this->attendance->get_user($request->user_id);
        $school_id=$user->school_id;
        $users=$this->attendance->get_school_users($school_id);
        $attendance_today=$this->attendance->get_attendance_today($users);
        
        $school_name=$this->attendance->get_school_name($school_id);
        $years=$this->get_year_now();
        $times=$this->get_time_now();
        $week=$this->get_week();
        $flag="list";

        return view('attendance',compact('school_name','school_id','users','attendance_today','years','times','week','flag'));
    }

    //打刻画面の出席の退室処理
    public function out_attendance(Request $request){
        $attendance_time=$this->get_attendance_time('out');
        $this->attendance->out_attendance($request,$attendance_time);
        $user=$this->attendance->get_user($request->user_id);
        $school_id=$user->school_id;
        $users=$this->attendance->get_school_users($school_id);
        $attendance_today=$this->attendance->get_attendance_today($users);
        
        $school_name=$this->attendance->get_school_name($school_id);
        $years=$this->get_year_now();
        $times=$this->get_time_now();
        $week=$this->get_week();
        $flag="list";

        return view('attendance',compact('school_name','school_id','users','attendance_today','years','times','week','flag'));
    }

    //今日の日付(年月日)を取得
    public function get_year_now(){
        
        $year=date('Y-m-d');
        $year_explode=explode('-',$year);
        return $year_explode;
    }

    //今の時間を取得
    public function get_time_now(){
        
        $time=date('H:i:s');
        $time_explode=explode(':',$time);
        return $time_explode;
    }

    //今日の曜日を取得
    public function get_week(){
        
        $week_kanji=['日','月','火','水','木','金','土'];
        $week=$week_kanji[date('w')];
        return $week;
    }

    //出席時間を取得
    public function get_attendance_time($in_out){
        
        $time=date('H:i');
        $time_explode=explode(':',$time);
        $hour=(int)$time_explode[0];
        $min=(int)$time_explode[1];
        $q=(int)floor($min/15);
        $r=$min%15;
        if($in_out=='in'){
            $times=$this->get_time_up($hour,$min,$q,$r);
        }elseif($in_out=='out'){
            $times=$this->get_time_down($hour,$min,$q,$r);
        }
        return $times;
    }

    //時間の繰り上げ
    public function get_time_up($hour,$min,$q,$r){
        if($hour<=9){
            if($min<=30){
                return $times=date('09:30');
            }
        }

        if($q==3){
            if($r>0){
                $hour_up=$hour+1;
                $times=date($hour_up.":00");
            }elseif($r==0){
                $times=date($hour.":".$min);
            }
        }elseif($q==0){
            if($r>0){
                $min_up=15;
                $times=date($hour.":".$min_up);
            }elseif($r==0){
                $times=date($hour.":".$min);
            }
        }elseif($q==1 or $q==2){
            if($r>0){
                $min_up=($q+1)*15;
                $times=date($hour.":".$min_up);
            }elseif($r==0){
                $times=date($hour.":".$min);
            }
        }
        return $times;
    }

    //時間の繰り下げ
    public function get_time_down($hour,$min,$q,$r){
        if($hour>=16){
            if($min>=0){
                return $times=date('16:00');
            }
        }

        if($r>0){
            if($q==0){
                $min_down='00';
                $times=date($hour.":".$min_down);
            }elseif($q>0){
                $min_down=$q*15;
                $times=date($hour.":".$min_down);
            }
        }elseif($r==0){
            $times=date($hour.":".$min);
        }
        return $times;
    }
}
