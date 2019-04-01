@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="content">

        <form action="{{route('download')}}" method="POST">
            {{csrf_field()}}

            <select name="category">
                <option value="All">All</option>
             @foreach ($categories as $category )
                 <option value={{$category['name']}}>{{$category['name']}}</option>
             @endforeach
            </select>

            <select name="type" id="">Type
                @foreach ($types as $type )
                    <option value={{$type}}>{{$type}}</option>
                @endforeach
            </select>
            <select name="status">
            @foreach ($status as $value)
                <option value={{$value}}>{{$value}}</option>
            @endforeach
            </select>

            <select name="stock">
            @foreach ($stock as $value)
                <option value={{$value}}>{{$value}}</option>
            @endforeach
            </select>

            <input type="submit" value="Send">
        </form>
    </div>
</div>
@endsection