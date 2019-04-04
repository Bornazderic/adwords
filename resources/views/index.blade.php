@extends('layouts.app') 
@section('content')
<div class="container">
        @if(Session::has('delete'))
            <div class="alert alert-info">{{ Session::get('delete') }}</div>
        @endif
    <div class="content">


        <form action="{{route('download')}}" method="POST">
            {{csrf_field()}}

<h1 id="spinner" style="display:none;">DELA</h1>
   
            <select name="site" onchange="this.options[this.selectedIndex].value && $('#spinner').show() && (window.location = '?site=' + this.options[this.selectedIndex].value);">
                    @foreach ($sites as $site )
                         <option value={{$site->id}} @if($site->id == request('site')) selected @endif >{{$site->name}}</option>
                    @endforeach
               </select>        

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
                <th scope="col">Category</th>
                <th scope="col">Status</th>
                <th scope="col">Type</th>
                <th scope="col">Stock</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($exports as $value)
            <tr>
            <th scope="row">{{$value->created_at->format('H:i:s')}}</th>
                <td>{{$value->created_at->format('d.m.Y')}}</td>
                <td>{{$value->category}}</td>
                <td>{{$value->status}}</td>
                <td>{{$value->type}}</td>
                <td>{{$value->stock}}</td>
            <td>
            <form action="{{route('delete', ['id' => $value] )}}" method="POST">
                @method('DELETE') 
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-trash fa-xs"></i>
                </button>

                <a href="{{route('pull', ['id' => $value] )}}" class="btn btn-success">
                        <i class="fa fa-download fa-xs"></i>
                </a>
            </form>
           
            </tr>
        @endforeach
    </tbody>
    </table>
</div>

@endsection

