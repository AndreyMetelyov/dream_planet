@extends('layouts.layout')
@section('content')
<div class="container">
    <h3>Gems list</h3>

    <form action="{{ route('assignGemsSubmit') }}" method="post">
        @csrf
        <table class="table table-bordered table-sm mt-1">
            <thead>
                <tr>
                    @foreach ($columnNames as $name)
                    <th scope="col">{{$name}}</th>
                    @endforeach

                </tr>
            </thead>
            <tbody>
                @foreach ($gems as $gem)
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td><input type="text" class="form-control" name="gemId-{{$loop->index+1}}" value="{{$gem->id}}" readonly></td>
                    <td><a href="{{route('user', $gem->gemtype)}}">{{$gem->type}}</a></td>
                    <td>{{$gem->extract_date}}</td>
                    <td><a href="{{route('user', $gem->earner)}}">{{$gem->ename}}</a></td>
                    <td><input type="text" class="form-control" name="oname-{{$loop->index+1}}" value="{{$gem->oname}}" readonly></td>

                    <td><select name="newOname-{{$loop->index+1}}">
                            <option></option>
                            @foreach ($elfs as $elf)
                            <option value="{{$elf->name}}">{{$elf->name}}</option>
                            @endforeach
                        </select>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
        <button type=" submit" class="btn btn-success">Confirm assign</button></a>
    </form>
</div>
@endsection