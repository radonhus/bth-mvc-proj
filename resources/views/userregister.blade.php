@include('header')

<h1>¥atzyBonanza!</h1>

<div class="form-box">

    <div class="width-wrapper">

    <h1>Register</h1>

    @if(count($errors))
        <div class="error-box">
            @foreach($errors->all() as $error)
                {{$error}}<br>
            @endforeach
        </div>
    @endif

    <form method="post" action="{{ url('/register') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" maxlength="40" minlength="3" name="name" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <label for="password_confirmation">Repeat password:</label>
        <input type="password" name="password_confirmation" required>
        <button type="submit" name="submit">Register</button>
    </form>

    <h2>Already registered? <a href="{{ url('/login') }}">Click to log in</a></h2>

    </div>

</div>

@include('footer')
