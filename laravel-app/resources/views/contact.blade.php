@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<section class="card">
    <h2>Contact</h2>
    <form method="post" action="{{ route('contact.submit') }}" novalidate>
        @csrf
        <label>
            Name
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </label>
        <label>
            Email
            <input type="email" name="email" value="{{ old('email') }}">
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </label>
        <label>
            Message
            <textarea name="message" rows="5">{{ old('message') }}</textarea>
            @error('message')
                <div class="error">{{ $message }}</div>
            @enderror
        </label>
        <button type="submit">Send</button>
    </form>
</section>
@endsection