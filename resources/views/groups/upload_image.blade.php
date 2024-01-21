@extends('layouts.app')

@section('content')
<div class="container">
    <br/>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Csoport képének beállítása</div>
                <div class="panel-body">
                	@include('errors.list')
					<p>Fájlméret max. 2MB, megengedett formátum: .jpg, .png, .gif</p>

                    <form method="POST" action="{{url('csoport')}}/{{$id}}/{{$name}}/kepfeltoltes" accept-charset="UTF-8" class="form" novalidate="novalidate" enctype="multipart/form-data">

                        @csrf

                         <div class="form-group">
                            <input id="upload_image" class="form-control" name="image" type="file">
                        </div>
					
						<div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Feltölt">
							<a href="{{url('csoport')}}/{{$id}}/{{$name}}" type="submit" class="btn btn-primary">Mégse</a>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection