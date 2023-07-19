@if(file_exists(public_path('images/profiles/n_'.$user->id.'.jpg')))
    <img class="profile_image" src="{{ url('/images/profiles') }}/n_{{ $user->id}}.jpg?{{ $user->photo_counter}}" data-toggle="modal" data-target="#photo">

    <!-- Modal -->
    <div id="photo" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <img src="{{ url('/images/profiles') }}/n_{{ $user->id}}.jpg?{{ $user->photo_counter}}">
                </div>
            </div>

        </div>
    </div>
@elseif ($myprofile)
    <a href="{{ url('/profilom') }}/feltolt_profilkep">
        <img class="profile_image" src="{{ url('/images/profiles') }}/n_feltolt_nincs_kep.png">
    </a>
@endif