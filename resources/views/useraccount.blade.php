@include('header')

<h1>My account</h1>

<pre>
@foreach ( auth()->user() as $key => $value)
    {{ $key }}: {{ $value }}<br>
@endforeach
</pre>

@include('footer')
