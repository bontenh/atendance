<?php

namespace App\Repositories\perform;

interface PerformRepositoryInterface{
    public function get_user_paginate($school_id);
    public function get_users_performance($users,$select_date);
    public function get_users_note($performances);
    public function get_user_performance($user_id,$select_date);
    public function get_school_name($user_id);
    public function get_school_users($user_id);
    public function get_users($school_id);
    public function change_perform_user($request);
    public function get_note($note_id);
    public function get_school_id($user_id);
    public function delete_perform_user($user_id,$select_date);
    public function get_month_performance($user_id,$select_date,$count_month_day);
    public function create_perform_user($request);
    public function get_user($user_id);
}

?>