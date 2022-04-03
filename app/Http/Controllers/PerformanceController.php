<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePerformRequest;
use App\Http\Requests\NewPerformRequest;
use Illuminate\Http\Request;
use App\Repositories\perform\PerformRepositoryInterface AS Perform;
use Illuminate\Support\Facades\DB;
use App\Models\Usertable;
use App\Models\Performance;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerformExport;
use App\Http\Requests\OutputSelectExcelRequest;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border as Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use ZipArchive;

//出席情報を管理するコントローラークラス
class PerformanceController extends Controller
{

    public function __construct(Perform $perform)
    {
        //PerformRepositoryクラスをコントローラークラスに設定する
        $this->perform = $perform;
        $this->middleware('checklogin');
    }

    //実績管理画面の移動
    //@param Request $request
    //@return blade perform/manege_performance
    public function move_manege_performance(Request $request){
        //日付の選択がなければ、今日の日付
        if(isset($request->select_date)){
            $select_date = $request->select_date;
        }else{
            $select_date = date('Y-m-d');
        }
        //@var int 学校のid
        $school_id = $request->school_id;
        
        //@var array 学校の利用者
        $users = $this->perform->get_user_paginate($school_id);
        //@var array 利用者の出席情報
        $performances = $this->perform->get_users_performance($users, $select_date);
        //@var array 利用者の備考名
        $notes = $this->perform->get_users_note($performances);
        
        return view('perform.manege_performance',compact('school_id','select_date','users','performances','notes'));
    }

    //実績記録の変更画面の移動
    //@param Request $request
    //@return blade perform/change_perform_user
    public function move_change_perform_user(Request $request){
        $status = $request->status;
        //@var array 開始時間と終了時間の配列
        $attendance_date = $this->create_start_end();

        //実績記録がない場合変更画面ではなく実績記録画面に移動
        //@var Performance 利用者の出席情報
        $perform_user = $this->perform->get_user_performance($request->user_id, $request->select_date);
        if(isset($perform_user)){
            //表示のため、退席時間を設定する
            $perform_user->output_perform_user();
        }else{
            return redirect()->action('PerformanceController@move_perform_new_user',['user_id'=>$request->user_id,'select_date'=>$request->select_date,'status'=>$status]);
        }
        //@var string 学校名
        $school_name = $this->perform->get_school_name($perform_user->user_id);
        //@var array 学校の利用者
        $users = $this->perform->get_school_users($perform_user->user_id);
        
        return view('perform.change_perform_user',compact('school_name','users','perform_user','attendance_date','status'));
    }

    //実績記録の変更の実行
    //@param ChangePerformReques $request
    //@return blade performe/change_perform_user_complete
    public function change_perform_user(ChangePerformRequest $request){
        //@var Performance 利用者の出席情報
        $perform_user = $this->perform->change_perform_user($request);
        //@var stiring 備考名
        $note = $this->perform->get_note($perform_user->note_id);
        //@var int 学校のid
        $school_id = $this->perform->get_school_id($perform_user->user_id);
        //@var string タイトル
        $title = "実績記録変更完了";
        $status = $request->status;
        return view('perform.change_perform_user_complete',compact('title','perform_user','school_id','note','status'));
    }

    //実績記録を削除の実行
    //@param Request $request
    //@return blade perform/change_perform_user_complete
    public function delete_perform_user(Request $request){
        $status=$request->status;
        //@var Performance 出席情報の削除した利用者情報
        $perform_user = $this->perform->delete_perform_user($request->user_id, $request->select_date);
        //@var int 学校のid
        $school_id = $this->perform->get_school_id($perform_user->user_id);
        //@var string 学校名
        $school_name = $this->perform->get_school_name($perform_user->user_id);
        //@var string 備考名
        $note = $this->perform->get_note($perform_user->note_id);
        //@var string タイトル
        $title = "実績記録削除完了";
            
        return view('perform.change_perform_user_complete',compact('title','perform_user','school_name','school_id','note','status'));
    }
    //実績記録作成画面の移動
    //@param Request $request
    //@return blade performe/perform_new_user
    public function move_perform_new_user(Request $request){
        //1であれば、実績記録管理から移動。2であれば、Excel出力から移動。
        $status=$request->status;

        if($status==2){
            //@var array 日付の配列
            $date_array = explode('-',$request->select_date);
            //日を2桁にする
            $date_array[2] = sprintf('%02d', $date_array[2]);
            //@var date 日付をdate型にする
            $select_date = date($date_array[0].'-'.$date_array[1].'-'.$date_array[2]);
        }else{
            //@var date 選択した日付
            $select_date = $request->select_date;
        }

        //ユーザーが選択されていないなら、最初の生徒にする
        if(isset($request->user_id)){
            $user_id = $request->user_id;
        }else{
            $user_id = 1;
        }
        //@var string 学校名
        $school_name = $this->perform->get_school_name($user_id);
        //@var date 開始時間と終了時間の配列
        $attendance_date = $this->create_start_end();
        //@var array 利用者が所属する生徒数
        $users = $this->perform->get_school_users($user_id);
        return view('perform.perform_new_user',compact('status','school_name','select_date','user_id','users','attendance_date'));
    }

    //実績記録作成の実行
    //@param NewPerformeRequest $request
    //@return blade performe/change_perform_user_coplete
    public function perform_new_user(NewPerformRequest $request){
        //@var Usertable 出席情報を記録した利用者情報
        $perform_user = $this->perform->create_perform_user($request);
        
        $status = $request->status;
        //@var int 学校のid
        $school_id = $this->perform->get_school_id($perform_user->user_id);
        //@var string タイトル
        $title = "実績記録作成完了";
        //@var string 備考
        $note = $this->perform->get_note($perform_user->note_id);
        return view('perform.change_perform_user_complete',compact('title','perform_user','school_id','note','status'));
    }

    //開始時間と終了時間の配列を作成
    //@return arrray 開始時間(9:30)から終了時間の配列(16:30),15分くぎり
    public function create_start_end(){
        //@var array 開始時間から終了時間の配列,15分くぎり
        $attendance_date = [];
        //9:30,9:45を挿入する
        $attendance_date[] = date('9:30');
        $attendance_date[] = date('9:45');
        for($i = 10; $i < 16; $i++){
            for($j = 0; $j < 60; $j = $j + 15){
                if($j == 0){
                    //@var date 時間
                    $datetime = date($i.':00');
                }else{
                    $datetime = date($i.':'.$j);
                }
                
                $attendance_date[] = $datetime;
            }
        }
        $attendance_date[] = date('16:00');
        return $attendance_date;
    }

    //Excel出力プレビューの移動
    public function move_output_excel(Request $request){
        //入力処理
        if(isset($request->select_date)){
            $select_date=$request->select_date;
            $date_array=explode('-',$select_date);
            $year=$date_array[0];
            $month=$date_array[1];
            $select_date=date($date_array[0].'-'.$date_array[1]);
        }else{
            $year=date('Y');
            $month=date('m');
            $select_date=date('Y-m');
        }
        if(isset($request->school_id)){
            $school_id=intval($request->school_id);
        }else{
            $school_id=1;
        }
        //利用者配列とユーザーID
        $users=DB::table('usertables')->where('deleted_at',null)->where('school_id',$school_id)->get($columns=['id','last_name','first_name']);
        
        if(isset($request->user_id)){
            $user_id=$request->user_id;
        }else{
            $user_id=$users[0]->id;
        }
        //月の日数
        $count_month_day=date('t',strtotime($select_date));
        
        //月の実績記録配列
        $performances=$this->perform->get_month_performance($user_id,$select_date,$count_month_day);
        
        //備考
        $notes=DB::table('notes')->get($columns=['note']);
        //曜日
        $week=['日','月','火','水','木','金','土'];
        $weeks=[];
        for($i=1;$i<=$count_month_day;$i++){
            $weeks[]=$week[date('w',strtotime(date($select_date.'-'.$i)))];
        }
        
        return view('perform.output_excel',compact('school_id','select_date','user_id','users','weeks','performances','notes'));
    }

    //excel出力

    public function export(Request $request,Border $border){
        
        $week=['日','月','火','水','木','金','土'];

        $user_id=$request->user_id;
        $select_date=$request->select_date;
        $date_array=explode('-',$select_date);
        $year=$date_array[0];
        $month=$date_array[1];
        $count_month_day=date('t',strtotime($select_date));
        $user=$this->perform->get_user($user_id);
        $last_name=$user->last_name;
        $first_name=$user->first_name;
        $last_name_kana=$user->last_name_kana;
        $first_name_kana=$user->first_name_kana;
        $school_name=$this->perform->get_school_name($user_id);
        $month_performance=$this->perform->get_month_performance($user_id, $select_date, $count_month_day);
        
        //テンプレート読み込み
        $spreadsheet=IOFactory::load(public_path().'/excel/export_'.$count_month_day.'.xlsx');
        $sheet=$spreadsheet->getActiveSheet();

        //年、月、名前、所属校を書き込み
        $sheet->setCellValue('A1',$year);
        $sheet->setCellValue('A2',$month);
        $sheet->setCellValue('A4',$last_name.' '.$first_name);
        $sheet->setCellValue('J4','未来のかたち '.$school_name);
        
        //一日の実績記録を書き込み
        for($i=0;$i<$count_month_day;$i++){
            //日付と曜日
            $sheet->setCellValue('A'.(9+$i),($i+1).'日');
            $sheet->setCellValue('B'.(9+$i),$week[date('w',strtotime(date($select_date.'-'.($i+1))))]);
            //曜日の色付けと下の枠線
            if($week[date('w',strtotime(date($select_date.'-'.($i+1))))]=='土'){
                $sheet->getStyle('B'.(9+$i))->getFont()->getColor()->setARGB('FF0000FF');
                
                $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                //実績記録の書き込み
                if(isset($month_performance[$i])){
                    $note=$this->perform->get_note($month_performance[$i]->note_id);

                    $sheet->setCellValue('D'.(9+$i),date('H:i',strtotime($month_performance[$i]->start)));
                    $sheet->setCellValue('E'.(9+$i),date('H:i',strtotime($month_performance[$i]->end)));
            
                    if($month_performance[$i]->food_fg==1){
                        $sheet->setCellValue('G'.(9+$i),1);
                    }

                    if($month_performance[$i]->outside_fg==1){
                        $sheet->setCellValue('H'.(9+$i),'1');
                    }

                    if($month_performance[$i]->medical_fg==1){
                        $sheet->setCellValue('I'.(9+$i),1);
                    }

                    $sheet->setCellValue('J'.(9+$i),$note);
                }else{
                    $sheet->setCellValue('C'.(9+$i),'欠');
                }
            }elseif($week[date('w',strtotime(date($select_date.'-'.($i+1))))]=='日'){
                $sheet->getStyle('B'.(9+$i))->getFont()->getColor()->setARGB('FFFF0000');
                
                $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                $borders->getBottom()->setBorderStyle(Border::BORDER_THIN);
            }else{
                $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);

                //実績記録の書き込み
                if(isset($month_performance[$i])){
                    $note=$this->perform->get_note($month_performance[$i]->note_id);

                    $sheet->setCellValue('D'.(9+$i),date('H:i',strtotime($month_performance[$i]->start)));
                    $sheet->setCellValue('E'.(9+$i),date('H:i',strtotime($month_performance[$i]->end)));
                
                    if($month_performance[$i]->food_fg==1){
                        $sheet->setCellValue('G'.(9+$i),1);
                    }

                    if($month_performance[$i]->outside_fg==1){
                        $sheet->setCellValue('H'.(9+$i),'1');
                    }

                    if($month_performance[$i]->medical_fg==1){
                        $sheet->setCellValue('I'.(9+$i),1);
                    }

                    $sheet->setCellValue('J'.(9+$i),$note);
                }else{
                    $sheet->setCellValue('C'.(9+$i),'欠');
                }
            }
        }
        //月末の枠線、太枠
        $borders=$sheet->getStyle('A'.(8+$i).':L'.(8+$i))->getBorders();
        $borders->getBottom()->setBorderStyle(Border::BORDER_THICK);
        //ダウンロード用に一時的にエクセルファイルを書き込み
        $writer=new Xlsx($spreadsheet);
        $output_excel_name='perform_'.$year.'_'.$month.'_'.$last_name_kana.'_'.$first_name_kana;
        $writer->save(public_path().'/'.$output_excel_name.'.xlsx');
        //エクセルをダウンロードと書き込んだエクセルを削除
        return response()->download(public_path().'/'.$output_excel_name.'.xlsx', $output_excel_name.'.xlsx')->deleteFileAfterSend(true); 
    }

    //学校全体のエクセル出力
    public function school_export(Request $request){
        $users=$this->perform->get_users($request->school_id);
        
        $week=['日','月','火','水','木','金','土'];

        $select_date=$request->select_date;
        $date_array=explode('-',$select_date);
        $year=$date_array[0];
        $month=$date_array[1];
        $count_month_day=date('t',strtotime($select_date));
        
        //テンプレート読み込み
        $spreadsheet=IOFactory::load(public_path().'/excel/export_'.$count_month_day.'.xlsx');
        $copy_sheet=$spreadsheet->getActiveSheet();
        $new_spreadsheet=new Spreadsheet();
        $sheetIndex = $new_spreadsheet->getIndex(
            $new_spreadsheet->getSheetByName('Worksheet')
        );
        $new_spreadsheet->removeSheetByIndex($sheetIndex);
        
        for($j=0;$j< count($users);$j++){
            $last_name=$users[$j]->last_name;
            $last_name_kana=$users[$j]->last_name_kana;
            $first_name=$users[$j]->first_name_kana;
            $first_name_kana=$users[$j]->first_name_kana;
            $user_id=$users[$j]->id;
            $school_name=$this->perform->get_school_name($user_id);
            $month_performance=$this->perform->get_month_performance($user_id, $select_date, $count_month_day);
            
            //テンプレート読み込み
            $spreadsheet=IOFactory::load(public_path().'/excel/export_'.$count_month_day.'.xlsx');
            $copy_sheet=$spreadsheet->getActiveSheet();
            $sheet_title=$year.'_'.$month.'_'.$last_name_kana.'_'.$first_name_kana;
            $copy_sheet->setTitle($sheet_title);
            $new_spreadsheet->addExternalSheet($copy_sheet);
            $sheet_index=$new_spreadsheet->getIndex($new_spreadsheet->getSheetByName($sheet_title));
            
            $sheet=$new_spreadsheet->setActiveSheetIndex($sheet_index);
            //年、月、名前、所属校を書き込み
            $sheet->setCellValue('A1',$year);
            $sheet->setCellValue('A2',$month);
            $sheet->setCellValue('A4',$last_name.' '.$first_name);
            $sheet->setCellValue('J4','未来のかたち '.$school_name);
        
            //一日の実績記録を書き込み
            for($i=0;$i<$count_month_day;$i++){
                //日付と曜日
                $sheet->setCellValue('A'.(9+$i),($i+1).'日');
                $sheet->setCellValue('B'.(9+$i),$week[date('w',strtotime(date($select_date.'-'.($i+1))))]);
                //曜日の色付けと下の枠線
                if($week[date('w',strtotime(date($select_date.'-'.($i+1))))]=='土'){
                    $sheet->getStyle('B'.(9+$i))->getFont()->getColor()->setARGB('FF0000FF');
                
                    $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                    $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                    //実績記録の書き込み
                    if(isset($month_performance[$i])){
                        $note=$this->perform->get_note($month_performance[$i]->note_id);

                        $sheet->setCellValue('D'.(9+$i),date('H:i',strtotime($month_performance[$i]->start)));
                        $sheet->setCellValue('E'.(9+$i),date('H:i',strtotime($month_performance[$i]->end)));
                
                        if($month_performance[$i]->food_fg==1){
                            $sheet->setCellValue('G'.(9+$i),1);
                        }

                        if($month_performance[$i]->outside_fg==1){
                            $sheet->setCellValue('H'.(9+$i),'1');
                        }

                        if($month_performance[$i]->medical_fg==1){
                            $sheet->setCellValue('I'.(9+$i),1);
                        }

                        $sheet->setCellValue('J'.(9+$i),$note);
                    }else{
                        $sheet->setCellValue('C'.(9+$i),'欠');
                    }
                }elseif($week[date('w',strtotime(date($select_date.'-'.($i+1))))]=='日'){
                    $sheet->getStyle('B'.(9+$i))->getFont()->getColor()->setARGB('FFFF0000');
                
                    $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                    $borders->getBottom()->setBorderStyle(Border::BORDER_THIN);
                }else{
                    $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                    $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                    //実績記録の書き込み
                    if(isset($month_performance[$i])){
                        $note=$this->perform->get_note($month_performance[$i]->note_id);

                        $sheet->setCellValue('D'.(9+$i),date('H:i',strtotime($month_performance[$i]->start)));
                        $sheet->setCellValue('E'.(9+$i),date('H:i',strtotime($month_performance[$i]->end)));
                
                        if($month_performance[$i]->food_fg==1){
                            $sheet->setCellValue('G'.(9+$i),1);
                        }

                        if($month_performance[$i]->outside_fg==1){
                            $sheet->setCellValue('H'.(9+$i),'1');
                        }

                        if($month_performance[$i]->medical_fg==1){
                            $sheet->setCellValue('I'.(9+$i),1);
                        }

                        $sheet->setCellValue('J'.(9+$i),$note);
                    }else{
                        $sheet->setCellValue('C'.(9+$i),'欠');
                    }
                }
            }
            //月末の枠線、太枠
            $borders=$sheet->getStyle('A'.(8+$i).':L'.(8+$i))->getBorders();
            $borders->getBottom()->setBorderStyle(Border::BORDER_THICK); 
        }
    $writer=new Xlsx($new_spreadsheet);
    $output_excel_name='perform_'.$year.'_'.$month.'_'.$school_name;
    $writer->save(public_path().'/'.$output_excel_name.'.xlsx');
    //エクセルをダウンロードと書き込んだエクセルを削除
    return response()->download(public_path().'/'.$output_excel_name.'.xlsx', $output_excel_name.'.xlsx')->deleteFileAfterSend(true); 
    }

    //エクセル選択出力画面の移動
    public function move_output_select_excel(Request $request){
        $select_date=$request->select_date;
        $school_id=$request->school_id;
        $users=$this->perform->get_users($school_id);
        
        return view('perform.output_select_excel',compact('select_date','school_id','users'));
    }

    //複数のエクセルをzip出力
    public function output_select_excel(Request $request){
        if(!isset($request->user_id_list)){
            $select_date=$request->select_date;
            $school_id=$request->school_id;
            $users=$this->perform->get_users($school_id);
            return view('perform.output_select_excel',compact('select_date','school_id','users'));
        }else{
            //dd($request->user_id_list,$request->select_date,$request->school_id);
        
            $zip=new ZipArchive();
            //削除するエクセルファイルのパスを格納
            $unlink_files=[];
            $school_name=Usertable::get_school_name($request->school_id);
            $week=['日','月','火','水','木','金','土'];

            $select_date=$request->select_date;
            $date_array=explode('-',$select_date);
            $year=$date_array[0];
            $month=$date_array[1];
            $count_month_day=date('t',strtotime($select_date));
            $zip->open(public_path().'/'.$year.'_'.$month.'_'.$school_name.'.zip',ZipArchive::CREATE);
            foreach($request->user_id_list as $user_id){
                
                $user=$this->perform->get_user($user_id);
                $last_name=$user->last_name;
                $last_name_kana=$user->last_name_kana;
                $first_name=$user->first_name_kana;
                $first_name_kana=$user->first_name_kana;
                $user_id=$user->id;
                $school_name=$this->perform->get_school_name($user_id);
                $month_performance=$this->perform->get_month_performance($user_id, $select_date, $count_month_day);
                //テンプレート作成
                
                $new_spreadsheet=new Spreadsheet();
                $sheetIndex = $new_spreadsheet->getIndex(
                    $new_spreadsheet->getSheetByName('Worksheet')
                );
                $new_spreadsheet->removeSheetByIndex($sheetIndex);

                //テンプレート読み込み
                $spreadsheet=IOFactory::load(public_path().'/excel/export_'.$count_month_day.'.xlsx');
                $copy_sheet=$spreadsheet->getActiveSheet();
                $sheet_title=$year.'_'.$month.'_'.$last_name_kana.'_'.$first_name_kana;
                $copy_sheet->setTitle($sheet_title);
                $new_spreadsheet->addExternalSheet($copy_sheet);
                $sheet_index=$new_spreadsheet->getIndex($new_spreadsheet->getSheetByName($sheet_title));
            
                $sheet=$new_spreadsheet->setActiveSheetIndex($sheet_index);
                //年、月、名前、所属校を書き込み
                $sheet->setCellValue('A1',$year);
                $sheet->setCellValue('A2',$month);
                $sheet->setCellValue('A4',$last_name.' '.$first_name);
                $sheet->setCellValue('J4','未来のかたち '.$school_name);

                //一日の実績記録を書き込み
                for($i=0;$i<$count_month_day;$i++){
                    //日付と曜日
                    $sheet->setCellValue('A'.(9+$i),($i+1).'日');
                    $sheet->setCellValue('B'.(9+$i),$week[date('w',strtotime(date($select_date.'-'.($i+1))))]);
                    //曜日の色付けと下の枠線
                    if($week[date('w',strtotime(date($select_date.'-'.($i+1))))]=='土'){
                        $sheet->getStyle('B'.(9+$i))->getFont()->getColor()->setARGB('FF0000FF');
                
                        $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                        $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                        //実績記録の書き込み
                        if(isset($month_performance[$i])){
                            $note=$this->perform->get_note($month_performance[$i]->note_id);

                            $sheet->setCellValue('D'.(9+$i),date('H:i',strtotime($month_performance[$i]->start)));
                            $sheet->setCellValue('E'.(9+$i),date('H:i',strtotime($month_performance[$i]->end)));
                
                            if($month_performance[$i]->food_fg==1){
                                $sheet->setCellValue('G'.(9+$i),1);
                            }

                            if($month_performance[$i]->outside_fg==1){
                                $sheet->setCellValue('H'.(9+$i),'1');
                            }

                            if($month_performance[$i]->medical_fg==1){
                                $sheet->setCellValue('I'.(9+$i),1);
                            }

                            $sheet->setCellValue('J'.(9+$i),$note);
                        }else{
                            $sheet->setCellValue('C'.(9+$i),'欠');
                        }
                    }elseif($week[date('w',strtotime(date($select_date.'-'.($i+1))))]=='日'){
                        $sheet->getStyle('B'.(9+$i))->getFont()->getColor()->setARGB('FFFF0000');
                
                        $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                        $borders->getBottom()->setBorderStyle(Border::BORDER_THIN);
                    }else{
                        $borders=$sheet->getStyle('A'.(9+$i).':L'.(9+$i))->getBorders();
                        $borders->getBottom()->setBorderStyle(Border::BORDER_DOTTED);
                        //実績記録の書き込み
                        if(isset($month_performance[$i])){
                            $note=$this->perform->get_note($month_performance[$i]->note_id);

                            $sheet->setCellValue('D'.(9+$i),date('H:i',strtotime($month_performance[$i]->start)));
                            $sheet->setCellValue('E'.(9+$i),date('H:i',strtotime($month_performance[$i]->end)));
                
                            if($month_performance[$i]->food_fg==1){
                                $sheet->setCellValue('G'.(9+$i),1);
                            }

                            if($month_performance[$i]->outside_fg==1){
                                $sheet->setCellValue('H'.(9+$i),'1');
                            }

                            if($month_performance[$i]->medical_fg==1){
                                $sheet->setCellValue('I'.(9+$i),1);
                            }

                            $sheet->setCellValue('J'.(9+$i),$note);
                        }else{
                            $sheet->setCellValue('C'.(9+$i),'欠');
                        }
                    }
                }   
                //月末の枠線、太枠
                $borders=$sheet->getStyle('A'.(8+$i).':L'.(8+$i))->getBorders();
                $borders->getBottom()->setBorderStyle(Border::BORDER_THICK);
                $writer=new Xlsx($new_spreadsheet);
                $output_excel_name='perform_'.$year.'_'.$month.'_'.$last_name_kana.'_'.$first_name_kana.'.xlsx';
                $writer->save(public_path().'/'.$output_excel_name);
                $zip->addFile(public_path().'/'.$output_excel_name,$output_excel_name);
                $unlink_files[]=$output_excel_name;
            }
            $zip->close();
            foreach($unlink_files as $unlink_excel){
                unlink(public_path().'/'.$unlink_excel);
            }
            return response()->download(public_path().'/'.$year.'_'.$month.'_'.$school_name.'.zip')->deleteFileAfterSend(true);
        }
    }
}
