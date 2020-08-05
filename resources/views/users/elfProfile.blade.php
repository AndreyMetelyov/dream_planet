@extends('layouts.layout')
@section('content')
<div class="container mt-5 mr-5">
    <div class="card border-dark mb-3" style="max-width: 39rem;">
        <form action="{{ route('editUser', $user->id) }}" method="post">
            @csrf
            <div class="card-header">{{$user->group}} card</div>
            <div class="card-body text-dark">
                <h5 class="card-title">Info</h5>

                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="login" class="col-sm-2 col-form-label">Login</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="login" id="login" value="{{$user->email}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password">
                    </div>
                </div>

                <h5 class="card-title">Gems prefer</h5>
                @foreach ($userGemtypes as $key=>$value)
                <div class="form-group row">
                    <label for="gems" class="col-sm-2 col-form-label">{{$key}}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="gem-{{$userGemtypes[$key]['gemId']}}" id="gems" value="{{$userGemtypes[$key]['coeff']}}">
                    </div>
                </div>
                @endforeach

                <p class="card-text">
                    Received gems: <br>
                    @foreach ($receivedGems as $gem)
                    Gem type: {{$gem->type}}; Count: {{$gem->count}} <br>
                    @endforeach
                </p>
                <p class="card-text">
                    Incoming gems:<br>
                    @foreach ($unconfirmedGems as $gem)
                    Gem type: {{$gem->type}} <a href="{{route('acceptGem', $gem->id)}}">Accept</a><br>
                    @endforeach

                </p>

                <p class="card-text">
                    Date of registration: {{$user->created_at}}<br>
                    Date of last login: {{$user->last_login_at}}<br>
                    Date of deletion: {{$user->deleted_at}}<br>
                    Active: {{$user->active}}
                </p>
                <button type="submit" class="btn btn-warning">Edit</button>
            </div>
        </form>
    </div>
</div>
@endsection