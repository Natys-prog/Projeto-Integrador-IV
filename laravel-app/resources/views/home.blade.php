@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="card">
    <h2>Welcome</h2>
    <p>This is your Laravel home page. Use the navigation to explore.</p>
    <p>Server time: {{ $serverTime }}</p>
    <p><a href="{{ route('init-db') }}" style="color:#0b72b9;">Initialize Database</a></p>
</section>
@endsection