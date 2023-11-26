<?php
    $logged_in = Auth::check();
    libxml_use_internal_errors(true);
    $dom_obj = new DOMDocument();
?>
@for ($i = 0; $i < $num=$commendations->count(); $i++)
    <?php
        $commendation = $commendations[$i];
        $my_post = $logged_in && Auth::user()->id==$commendation->user->id ? true : false;
        $admin = $logged_in && Auth::user()->admin;
    ?>
    @if(isset($commendation->user->id) && ($commendation->active && $commendation->approved || $my_post || $admin))
        <h3><a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->title }}</a></h3>
        <p>
            @if($my_post || $admin)
                <a href="{{url('ajanlo')}}/{{$commendation->id}}/{{$commendation->slug}}/modosit">módosít</a>
                @if(!$commendation->active) <i>/inaktív/</i>@endif
                @if(!$commendation->approved) <i>/nincs engedélyezve/</i>@endif
            @endif
        </p>
        {!! nl2br($commendation->body) !!}<br>
        @if(!empty($commendation->url))
        <?php
        $page_content = file_get_contents($commendation->url);
        $dom_obj->loadHTML($page_content);
        $image = $title = $description = $site_name = null;
        $xpath = new DOMXPath($dom_obj);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        //foreach($dom_obj->getElementsByTagName('meta') as $meta) {
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            if($property=='og:image') $image = $content;
            if($property=='og:title') $title = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            if($property=='og:description') $description = is_numeric(strpos($content,'Ã'))? utf8_decode($content) : $content;
            if($property=='og:site_name') $site_name = $content;
        }
        ?>
        <div class="inner_box" style="background-color: #fbfbfb">
            <p><a href="{{ $commendation->url }}" target="_blank">{{ $title }}</a></p>
            <p><a href="{{ $commendation->url }}" target="_blank"><img src="{{$image}}" height="300px;" style="display: block; margin-left: auto; margin-right: auto;"></a></p>
            <p>@if(strlen($description)>300){{ mb_substr($description,0,300) }} ... @else {{ $description }} @endif</p>
        </div>

        @endif
        <span class="author"><a href="{{ url('profil',$commendation->user->id) }}/{{$commendation->user->slug}}">{{ $commendation->user->name }}</a>,	{{ $commendation->created_at }}</span><br>
        @if ($logged_in)
            <a href="{{ url('ajanlo',$commendation->id) }}/{{$commendation->slug}}" type="submit" class="btn btn-default">Hozzászólok</a>
            @if( $commendation->counter>0)
                &nbsp;&nbsp;<a href="{{ url('commendation',$commendation->id) }}/{{$commendation->slug}}">{{ $commendation->counter }} hozzászólás</a>
            @endif
        @endif
        @if($i!=$num-1)<hr>@endif
    @endif
@endfor