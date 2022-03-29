<?php

namespace App\Repositories\Manege;

interface ManegeRepositoryInterface{
    public function get_school_user_paginate($school_id);
    public function get_users_school_name($users);
    public function create_new_user($request);
    public function get_school_name($school_id);
    public function get_user($user_id);
    public function change_manege_user($request);
    public function softdelete_manege_user($user_id);
    public function get_softdelete_users($school_id);
    public function manege_re_register_user($user_id_list);
    public function manege_delete_user($user_id_list);
}

?>