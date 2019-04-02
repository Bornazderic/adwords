@extends('layouts.app') 
@section('content')
@if(Session::has('success'))
{
    <p>{{Session::get('success')}}</p>
}
@endif
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
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">File name</th>
                <th scope="col">Type</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($exports as $value)
            <tr>
            <th scope="row">{{$value->date}}</th>
                <td>{{$value->time}}</td>
                <td>{{$value->file_name}}</td>
                <td>{{$value->type}}</td>
            <td><form action="{{route('delete', ['id' => $value] )}}" method="POST">@method('DELETE') @csrf<button type="submit" class="btn btn-danger"><i class="fa fa-trash fa-xs"></i></button></form></td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>
@endsection