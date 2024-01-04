<div class="panel panel-default">
    <div class="panel-body">
        <div id="motivation_update_block" class="collapse">
            <input type="hidden" id="group_id" value="{{$group->id}}">
            <div class="form-group">
                <b>Azért vagyok a csoport tagja, mert … *</b>
                <a href="#motivation_info" data-toggle="collapse"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                <div id="motivation_info" class="collapse info">
                    Kérjük, fogalmazd meg a szándékodat, hogy miért csatlakoztál a csoporthoz.  Mi az, amit a csoporthoz szeretnél-tudsz hozzátenni és mit szeretnél kapni ebben a csoportban?
                </div>
                <textarea class="form-control" rows="4" id="motivation" cols="50">@if (isset($my_profile)){{$my_profile->pivot->motivation}}@endif</textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" onclick="update()">Mentés</button>
            </div>
            <hr>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th colspan="2">Azért vagyok a csoport tagja, mert ...  <a class="btn btn-default btn-sm" href="#motivation_update_block" data-toggle="collapse"><i class="fa fa-btn fa-edit"></i>Módosít</a></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <?php
                    $html_image = '';
                    if(file_exists(public_path('images/profiles/k_'.$user->id.'.jpg'))) {
                        $html_image = '<img src="'.url('/images/profiles').'/k_'.$user->id.'.jpg?'.$user->photo_counter.'">';
                    }
                    ?>
                    <td width="60px" class="img_column">
                        <a href="{{ url('profil',$user->id) }}/{{$user->slug}}">
                            {!! $html_image  !!}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('profil',$user->id) }}/{{$user->slug}}"><span class="list_profile_image">{!!$html_image!!}</span>{{ $user->name }}</a>
                        {{ $user->full_location }}
                        <br>
                        {{$user->pivot->motivation}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>

@section('footer')
    <script>
        function update() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var group_id = $("#group_id").val();
            var motivation = $("#motivation").val();

            $.ajax({
                type: "POST",
                url: '{{ url('motivation_update') }}',
                data: {
                    _token: CSRF_TOKEN,
                    group_id: group_id,
                    motivation: motivation
                },
                success: function(data) {
                    if(data['status']=='success') {
                        location.reload();
                    }
                },
                error: function(error){
                    console.log(error.responseText);
                }
            });
        }
    </script>
@endsection