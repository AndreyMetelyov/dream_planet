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
                <div class="form-group row">
                    <div class="col-sm-2">Is master gnome</div>
                    <div class="col-sm-10">
                        <div class="form-check">
                            @if ($user->is_master_gnome)
                            <input class="form-check-input" type="checkbox" name="checkboxMG" id="master" checked>
                            @else
                            <input class="form-check-input" type="checkbox" name="checkboxMG" id="master">
                            @endif
                        </div>
                    </div>
                </div>
                <p class="card-text">
                    @foreach ($extractedGems as $gem)
                    Gem type: {{$gem->type}}; Count: {{$gem->count}} <br>
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