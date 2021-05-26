@include('header')

<h1>¥atzyBonanza!</h1>

<div class="form-box">

    <div class="width-wrapper">

    <h1>Log in to play</h1>

    @include('formerror')

    <form method="post" action="{{ url('/login') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" maxlength="40" minlength="3" name="name" required>
        <label for="email">Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="submit">Login</button>
    </form>

    <h2>Not yet registered? <a href="{{ url('/register') }}">Click to register</a></h2>

    </div>

</div>

@include('footer')
