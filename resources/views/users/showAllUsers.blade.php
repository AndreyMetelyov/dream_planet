@extends('layouts.layout')
@section('content')
<div class="row">
    @include('filterUserForm')
    <div class="col-6 col-md-4">
        <h3>Elfs list</h3>
        <table class="table table-bordered table-sm mt-1">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Total gems received</th>
                    <th scope="col">Top 3 gems</th>
                    <th scope="col">View user</th>
                    <th scope="col">Delete user</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                @if ($user->group == 'elf')
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$user->name}}</td>
                    @if ($user->active)
                    <td>active</td>
                    @else
                    <td>deleted</td>
                    @endif
                    <td>{{$user->ownedGemsCount}}</td>
                    <td>
                        @foreach($user->top3 as $top3)
                        #{{$loop->index+1}} - {{$top3}}<br>
                        @endforeach
                    </td>
                    <td><a href="{{route('user', $user->id)}}"><button class="btn btn-info">View</button></a></td>
                    <td><a href="{{route('deleteUser', $user->id)}}"><button class="btn btn-danger">Delete</button></a></td>
                </tr>

                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-6 col-md-4">
        <h3>Gnomes list</h3>
        <table class="table table-bordered table-sm mt-1">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Total extracted gems</th>
                    <th scope="col">Is master gnome</th>
                    <th scope="col">View user</th>
                    <th scope="col">Delete user</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                @if ($user->group == 'gnome')
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td>{{$user->name}}</td>

                    @if ($user->active)
                    <td>active</td>
                    @else
                    <td>deleted</td>
                    @endif

                    <td> {{$user->earnedGemsCount}} </td>
                    @if ($user->is_master_gnome)
                    <td><input type="checkbox" id="master" disabled checked></td>
                    @else
                    <td><input type="checkbox" id="master" disabled></td>
                    @endif
                    <td><a href="{{route('user', $user->id)}}"><button class="btn btn-info">View</button></a></td>
                    <td><a href="{{route('deleteUser', $user->id)}}"><button class="btn btn-danger">Delete</button></a></td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@endsection