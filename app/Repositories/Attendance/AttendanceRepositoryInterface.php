<?php

namespace App\Repositories\Attendance;

interface AttendanceRepositoryInterface{
    public function get_school_users($school_id);
    public function get_select_names($school_id,$select);
    public function get_attendance_today($users);
    public function get_school_name($school_id);
    public function get_user($user_id);
    public function get_perform_user($user_id,$select_date);
    public function in_attendance($request,$attendance_time);
    public function out_attendance($request,$attendance_time);
}

?>