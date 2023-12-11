@extends('layouts.app')

@section('content')
<div class="container">
    <br/>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Meghívó küldése</div>
                <div class="panel-body">
					@include('errors.list')

                    <form method="POST" action="{{url('meghivo')}}/uj" accept-charset="UTF-8">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email:</label> <i>(Amennyiben több email címet adsz meg, egymástól vesszővel válaszd el őket)</i>
                            <input class="form-control" required="required" name="email" type="text" value="{{old('email')}}" id="email">
                        </div>

                        <div class="form-group">
                            <label for="message">Üzenet:</label>
                            <textarea class="form-control" required="required" rows="6" name="message" cols="50">{{old('message')}}</textarea>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" value="Elküld">
                            <a href="{{url('tarsak')}}" type="submit" class="btn btn-primary">Mégse</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection