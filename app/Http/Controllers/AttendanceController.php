<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\Attendance\AttendanceRepositoryInterface AS Attendance;

//利用者の出席退席の時刻を記録する
class AttendanceController extends Controller
{

    //リポジトリークラスをコントローラークラスから使えるようにする
    //@param AttendanceRepository $attendance 出席退席を管理するリポジトリークラス
    public function __construct(Attendance $attendance)
    {
        $this->attendance=$attendance;
    }

    //打刻画面移動
    //@param Request $request 打刻画面への移動情報
    //@return blade attendance.blade.php
    public function move_attendance(Request $request){
        //@var int 学校id 0 = 本校, 1 = 本町二校
        $school_id = $request->school_id;
        //@var int ア行からワ行までの文字のインデックス
        $select = $request->select;
        //表示する生徒に、カナ文字で範囲指定なし、またはあり
        if($select == 0){
            //@var array Usertable 学校ごとの全生徒
            $users = $this->attendance->get_school_users($school_id);
        }else{
            //@var array Usertable 学校ごとの生徒(範囲してい)
            $users = $this->attendance->get_select_names($school_id, $select);
        }
        
        //@var array 今日出席している生徒の情報、打刻時間の出席中を表示する変数
        $attendance_today = $this->attendance->get_attendance_today($users);
        
        //@var string 学校名の取得
        $school_name = $this->attendance->get_school_name($school_id);
        //@var array 今日の年月日
        $years = $this->get_year_now();
        //@var array 今日の時間
        $times = $this->get_time_now();
        //@var string 今日の曜日
        $week = $this->get_week();
        //@var stirng 打刻時間の左側の表示を制御
        $flag = "list";

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
    //@param Request $request
    //@return blade attendance.blade.php
    public function in_attendance(Request $request){
        //@var string 入室時間
        $attendance_time = $this->get_attendance_time('in');
        //入室時間を記録する
        $this->attendance->in_attendance($request,$attendance_time);
        
        //画面の表示処理
        //@var Usertable 入室した利用者
        $user = $this->attendance->get_user($request->user_id);
        //@var int 学校id 0 = 本校, 1 = 本町二校
        $school_id = $user->school_id;
        //@var array Usertable 学校ごとの全生徒
        $users = $this->attendance->get_school_users($school_id);
        //@var array 今日出席している生徒の情報、打刻時間の出席中を表示する変数
        $attendance_today = $this->attendance->get_attendance_today($users);
        //@var string 学校名の取得
        $school_name = $this->attendance->get_school_name($school_id);
        //@var array 今日の年月日
        $years = $this->get_year_now();
        //@var array 今日の時間
        $times = $this->get_time_now();
        //@var string 今日の曜日
        $week = $this->get_week();
        //@var stirng 打刻時間の左側の表示を制御
        $flag = "list";

        return view('attendance',compact('school_name','school_id','users','attendance_today','years','times','week','flag'));
    }

    //打刻画面の出席の退室処理
    //@param Request $request
    //@return blade attendance.blade.php
    public function out_attendance(Request $request){
        //@var date 退席時間
        $attendance_time = $this->get_attendance_time('out');
        //退席時間を記録する
        $this->attendance->out_attendance($request, $attendance_time);
        
        //@var Usertable 入室した利用者
        $user = $this->attendance->get_user($request->user_id);
        //@var int 利用者の学校id
        $school_id = $user->school_id;
        //@var array Usertable 学校ごとの全生徒
        $users = $this->attendance->get_school_users($school_id);
        //@var array 今日出席している生徒の情報、打刻時間の出席中を表示する変数
        $attendance_today = $this->attendance->get_attendance_today($users);
        //@var string 学校名の取得
        $school_name = $this->attendance->get_school_name($school_id);
        //@var array 今日の年月日
        $years = $this->get_year_now();
        //@var array 今日の時間
        $times = $this->get_time_now();
        //@var string 今日の曜日
        $week = $this->get_week();
        //@var stirng 打刻時間の左側の表示を制御
        $flag = "list";

        return view('attendance',compact('school_name','school_id','users','attendance_today','years','times','week','flag'));
    }

    //今日の日付(年月日)を取得
    //@return array 今日の年月日
    public function get_year_now(){
        //@var date 今日の年月日
        $year = date('Y-m-d');
        //@var array 年月日を-で分ける
        $year_explode = explode('-',$year);
        return $year_explode;
    }

    //今の時間を取得
    //@return array 今日の時間
    public function get_time_now(){
        //@var date 今日の時間
        $time = date('H:i:s');
        //@var array 今日の時間を:を分ける
        $time_explode = explode(':',$time);
        return $time_explode;
    }

    //今日の曜日を取得
    //@return string 今日の曜日
    public function get_week(){
        //@var array 曜日の配列
        $week_kanji = ['日','月','火','水','木','金','土'];
        //@var string 今日の曜日
        $week = $week_kanji[date('w')];
        return $week;
    }

    //出席退席時間を取得,15分刻み
    //出席は現在時刻より15分プラスする
    //退席は現在時刻より15分マイナスする
    //@param string $in_out inまたはout
    //@return string 出席退席時間
    public function get_attendance_time($in_out){
        //@var date 時間と秒を取得する
        $time = date('H:i');
        //@var array 時間と秒を分ける
        $time_explode=explode(':',$time);
        //@var int 時間
        $hour=(int)$time_explode[0];
        //@var int 分
        $min=(int)$time_explode[1];
        //@var int 分を15で割る
        $q=(int)floor($min/15);
        //@var int 分を15で割った余り
        $r=$min%15;
        if($in_out=='in'){
            //@var string //出席時間+15分プラスする 
            $times = $this->get_time_up($hour,$min,$q,$r);
        }elseif($in_out=='out'){
            //@var string //退席時間-15分マイナスする
            $times=$this->get_time_down($hour,$min,$q,$r);
        }
        return $times;
    }

    //時間の繰り上げ
    //@param int $hour 時間の配列
    //@param int $min 分の配列
    //@param int $q 分を15で割った商
    //@param int $r 分を15で割った余り
    //@return date 時刻
    public function get_time_up($hour,$min,$q,$r){
        //9:30以前は、9:30に設定する
        if($hour <= 9){
            if($min <= 30){
                //@var date 時刻
                $times = date('09:30');
                return $times;
            }
        }

        //分の商3は45分以上
        if($q == 3){
            //45分より大きいなら45 + 15 = 60,1時間進める
            if($r > 0){
                //@var int 時間
                $hour_up = $hour + 1;
                $times = date($hour_up.":00");
                //45分なら、その時間を返す
            }elseif($r == 0){
                $times = date($hour.":".$min);
            }
            //分の商0は15分より小さい
        }else if($q == 0){
            //0分より大きな15分プラスする
            if($r > 0){
                //@var int 分
                $min_up = 15;
                $times = date($hour.":".$min_up);
                //0分ならその時間を返す
            }elseif($r == 0){
                $times = date($hour.":".$min);
            }
            //15分より大きく45より小さい
        }else if($q == 1 or $q == 2){
            //余りが0でないなら、15分プラスする
            if($r > 0){
                $min_up = ($q + 1)*15;
                $times = date($hour.":".$min_up);
            }elseif($r == 0){
                $times = date($hour.":".$min);
            }
        }
        return $times;
    }

    //時間の繰り下げ
    //@param int $hour 時間の配列
    //@param int $min 分の配列
    //@param int $q 分を15で割った商
    //@param int $r 分を15で割った余り
    //@return date 退席時間
    public function get_time_down($hour,$min,$q,$r){
        //16:00以降は16:00に設定する
        if($hour>=16){
            if($min>=0){
                //@var date 時刻
                $times = date('16:00');
                return $times;
            }
        }

        //15分より大きいなら
        if($r > 0){
            if($q == 0){
                //@var int 分
                $min_down = '00';
                $times = date($hour.":".$min_down);
            }elseif($q>0){
                $min_down = $q*15;
                $times = date($hour.":".$min_down);
            }
            //15分の場合はその時間を返す
        }elseif($r == 0){
            $times = date($hour.":".$min);
        }
        return $times;
    }
}
