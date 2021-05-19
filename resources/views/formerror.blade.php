@if(count($errors))
    <div class="error-box">
        @foreach($errors->all() as $error)
            {{$error}}<br>
        @endforeach
    </div>
@endif
