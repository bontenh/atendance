@extends('layouts.change_manege_complete_layout')

@section('manege')
    <div class="complete">
    <h1>{{$title}}</h1>
    <table>
        <tr>
            <th>ID</th>
            <td>{{$user->id}}</td>
        </tr>
        <tr>
            <th>氏名</th>
            <td>{{$user->last_name}} {{$user->first_name}}</td>
        </tr>
        <tr>
            <th>カナ名</th>
            <td>{{$user->last_name_kana}} {{$user->first_name_kana}}</td>
        </tr>
        <tr>
            <th>所属</th>
            <td>{{$school_name}}</td>
        </tr>
    </table>
    <div class="foot">
    <a href="/manegement/manege_user?school_id=0">戻る</a>
    </div>
    </div>
@endsection