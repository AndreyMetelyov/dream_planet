<div class="container mt-5 mr-5">
    <div class="card border-dark mb-3" style="max-width: 21rem;">
        <div class="card-header">{{$user->name}}</div>
        <div class="card-body text-dark">
            <h5 class="card-title">{{$user->email}}</h5>
            <p class="card-text">
                Date of registration: {{$user->created_at}}<br>
                Date of last login: ???<br>
                Date of deletion: ???<br>
                Active: {{$user->active}}
            </p>
        </div>
    </div>
</div>