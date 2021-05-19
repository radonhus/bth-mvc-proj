<!doctype html>
<html>
<meta charset="utf-8">
<title>{{ $title }}</title>
<link rel="stylesheet" href="{{ url('/css/style.css') }}">
<link rel="icon" href="{{ url('/img/favicon.ico') }}">
<body>
<div class="header-wrapper">
    <header>
        <nav>
            @if( auth()->check() )
            <a href="{{ url('/gamemode') }}">New Game</a>
            <a href="{{ url('/highscores') }}">Highscores</a>
            <a href="{{ url('/myaccount') }}">My Account</a>
            @else
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/highscores') }}">Highscores</a>
            @endif
        </nav>
        <div class="header-right">
            @if( auth()->check() )
                <a href="{{ url('/myaccount') }}">Playing as: {{ auth()->user()->name }}</a> |
                <a href="{{ url('/logout') }}">Logout</a>
            @endif
        </div>
    </header>
</div>
<div class="line"></div>
<div class="main-wrapper">
<main>
