@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="content">

        <form action="{{route('download')}}" method="POST">
            {{csrf_field()}}

            <select name="category">
                @for ($i = 0 ; $i < count($categories) ; $i++)
            <option value={{$categories[$i]}}>{{$categories[$i]}}</option>
                @endfor
            </select>
            <input type="submit" value="Send">
        </form>
    </div>
</div>
@endsection