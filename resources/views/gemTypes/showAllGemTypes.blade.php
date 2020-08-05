@extends('layouts.layout')
@section('content')
<div class="container">
    <a href="{{route('addGemTypeFormView')}} "><button class="btn btn-success">Create new gemtype</button></a>
    <ul>
        @foreach ($gemTypes as $gemType)
        <li>
            <a href="{{ route('gemType', $gemType->id)}}">{{$gemType->type}}</a>
        </li>
        @endforeach
    </ul>
</div>
<main role="main" class="container mt-5">

    <form action="{{ route('coeffsSubmit') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="coeff">First coeff</label>
            <input type="number" step="any" name="coeff_1" class="form-control" value="{{$coeffs->coeff_1}}">
        </div>
        <div class="form-group">
            <label for="coeff">Second coeff</label>
            <input type="number" step="any" name="coeff_2" class="form-control" value="{{$coeffs->coeff_2}}">
        </div>
        <div class="form-group">
            <label for="coeff">Third coeff</label>
            <input type="number" step="any" name="coeff_3" class="form-control" value="{{$coeffs->coeff_3}}">
        </div>
        <button type="submit" class="btn btn-success">Send</button>
    </form>

</main>

@endsection