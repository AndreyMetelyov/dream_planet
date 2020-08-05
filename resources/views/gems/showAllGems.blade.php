@extends('layouts.layout')
@section('content')

<div class="row">
    <div class="col-md-4">
        <h3>Filter</h3>
        <form action="{{ route('gems') }}">
            <div class="form-group">
                <label for="owner">Owner</label>
                <input type="text" class="form-control" name="owner" id="owner" value="{{$fillFields['owner']}}">
            </div>
            <div class="form-group">
                <label for="earner">Earner</label>
                <input type="text" class="form-control" name="earner" id="earner" value="{{$fillFields['earner']}}">
            </div>
            <div class="form-group">
                <label for="approver">Approver</label>
                <input type="text" class="form-control" name="approver" id="approver" value="{{$fillFields['approver']}}">
            </div>
            <div class="form-group">
                <label for="assign_date">Assign date</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="assign" id="inlineRadio1" value="All" checked>
                    <label class="form-check-label" for="inlineRadio1">All</label>
                </div>
                @foreach ($dates as $d)
                <div class="form-check form-check-inline">
                    @if ($fillFields['assign']==$d)
                    <input class="form-check-input" type="radio" name="assign" id="inlineRadio{{$loop->index+1}}" value="{{$d}}" checked>
                    @else
                    <input class="form-check-input" type="radio" name="assign" id="inlineRadio{{$loop->index+1}}" value="{{$d}}">
                    @endif
                    <label class="form-check-label" for="inlineRadio{{$loop->index+1}}">{{$d}}</label>
                </div>
                @endforeach
                <input type="date" class="form-control" name="assign_date" id="assign_date" value="{{$fillFields['assign_date']}}">
            </div>
            <div class="form-group">
                <label for="confirm_date">Confirm date</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="confirm" id="inlineRadio4" value="All" checked>
                    <label class="form-check-label" for="inlineRadio1">All</label>
                </div>
                @foreach ($dates as $d)
                <div class="form-check form-check-inline">
                    @if ($fillFields['confirm']==$d)
                    <input class="form-check-input" type="radio" name="confirm" id="inlineRadio{{$loop->index+1}}" value="{{$d}}" checked>
                    @else
                    <input class="form-check-input" type="radio" name="confirm" id="inlineRadio{{$loop->index+1}}" value="{{$d}}">
                    @endif
                    <label class="form-check-label" for="inlineRadio{{$loop->index+1}}">{{$d}}</label>
                </div>
                @endforeach
                <input type="date" class="form-control" name="confirm_date" id="confirm_date" value="{{$fillFields['confirm_date']}}">
            </div>
            <div class="form-group">
                <label for="gemType">Gem type</label>
                <input type="text" class="form-control" name="gemType" id="gemType" value="{{$fillFields['gemType']}}">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="status" id="status">
                    <option>All</option>
                    @foreach ($status as $s)
                    @if ($s == $fillFields['status'])
                    <option selected>{{$s}}</option>
                    @else
                    <option>{{$s}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Confirm filter</button>
        </form>
    </div>
    <div class="col-md-8">
        <a href="{{route('addGemFormView')}}"><button class="btn btn-success">Create new gem</button></a>
        <a href="{{route('assignGems')}}"><button class="btn btn-success">Assign gems</button></a>
        <h3>Gems list</h3>
        <table class="table table-bordered table-sm mt-1">
            <thead>
                <tr>
                    @foreach ($columnNames as $name)
                    <th scope="col">{{$name}}</th>
                    @endforeach
                    <th scope="col">Delete gem</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gems as $gem)
                <tr>
                    <th scope="row">{{$loop->index+1}}</th>
                    <td><a href="{{route('gemType', $gem->gemtype)}}">{{$gem->type}}</a></td>
                    <td>{{$gem->extract_date}}</td>
                    <td>{{$gem->assign_date}}</td>
                    <td>{{$gem->confirm_date}}</td>
                    <td><a href="{{route('user', $gem->earner)}}">{{$gem->ename}}</a></td>
                    @if (!is_null($gem->approver))<td><a href="{{route('user', $gem->approver)}}">{{$gem->aname}}</a></td>
                    @else<td>{{$gem->aname}}</td>
                    @endif
                    @if (!is_null($gem->owner))<td><a href="{{route('user', $gem->owner)}}">{{$gem->oname}}</a></td>
                    @else<td>{{$gem->oname}}</td>
                    @endif
                    <td>{{$gem->method}}</td>
                    <td>{{$gem->status}}</td>
                    <td><a href="{{route('deleteGem', $gem->id)}}"><button class="btn btn-danger">Delete</button></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection