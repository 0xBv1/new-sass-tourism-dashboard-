@if(session()->has('message'))
    <div class="alert-{{ session('type', 'info') }} alert">
        {{ session('message') }}
    </div>
@endif
@if($errors->any())
    <div class="alert-danger alert">
        <ul>
            @foreach($errors->all() as $error)
                <li class="d-block">
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
