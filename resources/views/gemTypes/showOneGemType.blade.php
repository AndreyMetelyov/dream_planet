@extends('layouts.layout')
@section('content')
<div class="container">
    <p>gem type: {{$gemType->type}}</p>
    <a href="{{route('editGemTypeFormView', $gemType->id)}}"><button class="btn btn-warning">Edit</button></a>
    <a href="{{route('deleteGemType', $gemType->id)}} "><button class="btn btn-danger">Delete</button></a>
</div>
@endsection