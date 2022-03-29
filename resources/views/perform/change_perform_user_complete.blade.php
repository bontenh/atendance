@extends('layouts.change_perform_complete_layout')

@section('manege')
    <div class="complete">
    <h1>{{$title}}</h1>
    <table>
        <tr>
            <th>日付</th>
            <td><?php echo (new DateTime($perform_user->insert_date))->format('n/d');?></td>
        </tr>
        <tr>
            <th>開始時間</th>
            <td><?php echo date('H:i',strtotime($perform_user->start));?></td>
        </tr>
        <tr>
            <th>終了時間</th>
            <td><?php echo date('H:i',strtotime($perform_user->end));?></td>
        </tr>
        <tr>
            <th>食事提供加算</th>
            <td>
            @if($perform_user->food_fg==1)
                〇
            @else
                -
            @endif
            </td>
        </tr>
        <tr>
            <th>施設外支援</th>
            <td>
            @if($perform_user->outside_fg==1)
                〇
            @else
                -
            @endif
            </td>
        </tr>
        <tr>
            <th>医療連携体制加算</th>
            <td>
            @if($perform_user->medical_fg==1)
                〇
            @else
                -
            @endif
            </td>
        </tr>
        <tr>
            <th>備考</th>
            <td>{{$note}}</td>
        </tr>
    </table>
    <div class="foot">
    @if($status==1)
        <a href="/manegement/manege_performance?school_id={{$school_id}}&select_date={{$perform_user->insert_date}}">戻る</a>
    @elseif($status==2)
        <a href="/manegement/output_excel?school_id={{$school_id}}&select_date={{$perform_user->insert_date}}&user_id={{$perform_user->user_id}}">戻る</a>
    @endif
    </div>
    </div>
@endsection