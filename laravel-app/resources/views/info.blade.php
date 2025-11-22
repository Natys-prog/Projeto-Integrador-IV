@extends('layouts.app')

@section('title', 'PHP Info')

@section('content')
<section class="card">
    <h2>PHP Information</h2>
    <p>PHP Version: {{ PHP_VERSION }}</p>
    <p>Laravel Version: {{ app()->version() }}</p>
    
    <div style="background:#f8f9fa; padding:1rem; border-radius:4px; margin-top:1rem;">
        <h3>Server Information</h3>
        <p><strong>PHP Version:</strong> {{ PHP_VERSION }}</p>
        <p><strong>Server Software:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</p>
        <p><strong>Document Root:</strong> {{ $_SERVER['DOCUMENT_ROOT'] ?? 'N/A' }}</p>
        <p><strong>Server Name:</strong> {{ $_SERVER['SERVER_NAME'] ?? 'N/A' }}</p>
    </div>
    
    <p style="margin-top:1rem;">
        <small>For full phpinfo(), you can still access it directly if needed, but this provides the essential information in a cleaner format.</small>
    </p>
</section>
@endsection