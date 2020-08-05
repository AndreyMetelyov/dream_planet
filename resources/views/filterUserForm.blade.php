<div class="col-6 col-md-4">
    <form>
        <div class="form-group">
            <label for="name">Filter by name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{$fillFields['name']}}">
        </div>
        <div class=" form-group">
            <label for="status">Filter by status</label>
            <select class="form-control" name="status" id="status">
                <option>All</option>
                <option @if ($fillFields['status']=='Active' ) selected @endif>Active</option>
                <option @if ($fillFields['status']=='Deleted' ) selected @endif>Deleted</option>
            </select>
        </div>
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
</div>