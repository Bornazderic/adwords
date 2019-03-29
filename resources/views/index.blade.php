@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="content">

        <form action="{{route('download')}}" method="POST">
            {{csrf_field()}}

            <select name="category">
             @foreach ($categories as $category )
                 <option value={{$category['name']}}>{{$category['name']}}</option>
             @endforeach
            </select>
            <input type="submit" value="Send">
        </form>
    </div>
</div>
@endsection