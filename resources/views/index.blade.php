@extends('layouts.app')

@section('content')
<div class="container">
    <div class="content">
        <select name="cat" id="cat">
            @for ($i = 0 ; $i < count($categories) ; $i++)
        <option value={{$categories[$i]}}>{{$categories[$i]}}</option>
        @endfor
        </select>
    </div>
    <a href="{{route('download')}}">Download</a>
</div>



@endsection