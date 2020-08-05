<h3>Table</h3>
<table class="table table-bordered table-sm mt-1">
    <thead>
        <tr>
            @foreach ($columnNames as $name)
            <th scope="col">{{$name}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $el)
        <tr>
            <th scope="row">{{$loop->index+1}}</th>
            <td>{{$el}}</td>
        </tr>
        @endforeach
    </tbody>
</table>