@extends('layouts.layout')
@section('content')

<main role="main" class="container mt-5">

    <form action="{{route('editGemTypeFormSubmit',$gemType->id)}}" method="post">
        @csrf
        <div class="form-group">
            <label for="type">Gem type</label>
            <input type="text" name="type" id="type" class="form-control" value="{{$gemType->type}}">
        </div>

        <button type="submit" class="btn btn-success">Confirm edit</button>


    </form>

</main>


@endsection