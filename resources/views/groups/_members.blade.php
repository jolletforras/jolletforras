@unless($group->members->isEmpty())
    <p>
        <?php $members_html = array(); ?>
        @foreach($group->members as $member)
            @if($member->status==3)
                <?php $members_link[] = '<a href="'.url('profil',$member->id).'/'.$member->slug.'">'.$member->name.'</a>' ?>
            @endif
        @endforeach
        <b>Tagok: </b>{!! implode(', ',$members_link) !!}
    </p>
@endunless