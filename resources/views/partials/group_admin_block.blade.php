<?php $text = ['article'=>'az írás','commendation'=>'az ajánló', 'project'=>'a kezdeményezés'] ?>
<p>
@if(count($group_where_admin_have_post_url)>0)
    Ez {{$text[$post_type]}} ezekben csoportokban szerepel, ahol csoportkezelő vagy: {!! implode(', ',$group_where_admin_have_post_url) !!}
@else
    Nem szerepel {{$text[$post_type]}} egyetlen csoportodban sem, ahol csoportkezelő vagy.
@endif
</p>
@if(count($delete_groups)>0)
    Törlöm ebből a csoportból:
    <select id="delete_from_group">
        @foreach($delete_groups as $key => $val)
            <option value="{{ $key }}">{{ $val }}</option>
        @endforeach
    </select>
    <button class="btn btn-default" type="button" onclick="delete_post_from_group()">Töröl</button><br>
@endif
@if(count($add_groups)>0)
    Felveszem ebbe a csoportba:
    <select id="add_to_group">
        @foreach($add_groups as $key => $val)
            <option value="{{ $key }}">{{ $val }}</option>
        @endforeach
    </select>
    <button class="btn btn-default" type="button" onclick="add_post_to_group()">Felvesz</button>
@endif