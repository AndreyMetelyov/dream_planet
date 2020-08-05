@extends('layouts.layout')
@section('content')

<main role="main" class="container mt-5">

    <form class="form-row" action="{{route('addGemFormSubmit')}}" method="post">
        @csrf

        <div class="form-group col-md-6">
            <label for="count">Gem type</label>
            @foreach ($gemTypes as $gemType)
            <input type="text" name="type{{$loop->index}}" id="type{{$loop->index}}" class="form-control" value="{{$gemType->type}}" readonly>
            @endforeach
        </div>

        <div class="form-group col-md-6">
            <label for="count">Count</label>
            @foreach ($gemTypes as $gemType)
            <input type="number" name="count{{$loop->index}}" id="count{{$loop->index}}" class="form-control">
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Send</button>


    </form>

</main>

@endsection