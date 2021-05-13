@include('header')

<h1>Register new user</h1>

@include('formerror')

<form method="post" action="{{ url('/register') }}">
    @csrf
    <label for="name">Name:</label>
    <input type="text" maxlength="40" minlength="3" name="name" required>
    <label for="password">Password:</label>
    <input type="password" name="password" required>
    <label for="password_confirmation">Repeat password:</label>
    <input type="password" name="password_confirmation" required>
    <input type="submit" name="submit" value="Register">
</form>

@include('footer')
