@include('header')

<h1>Login</h1>

@include('formerror')

<form method="post" action="{{ url('/login') }}">
    @csrf
    <label for="name">Name:</label>
    <input type="text" maxlength="40" minlength="3" name="name" required>
    <label for="email">Password:</label>
    <input type="password" name="password" required>
    <input type="submit" name="submit" value="Login">
</form>

@include('footer')
