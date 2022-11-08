@extends('layouts.app')

@section('content')
<div class="container">
    <br/>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Új jelszó megadása</div>
                <div class="panel-body">
					@include('errors.list')
					
                    <form method="POST" action="{{url('profilom/jelszocsere')}}" accept-charset="UTF-8" class="form">

                        @csrf

						<div class="form-group">
                            <label for="password">Jelszó:</label>
                            <input class="form-control" name="password" type="password" value="">
						</div>
						
						<div class="form-group">
							<label for="password_confirmation">Jelszó megerősítése:</label>
                            <input class="form-control" name="password_confirmation" type="password" value="">
						</div>
						
						<div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Mentés">
							<a href="{{url('profil')}}/{{Auth::user()->id}}/{{Auth::user()->slug}}" type="submit" class="btn btn-primary">Mégse</a>
						</div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection