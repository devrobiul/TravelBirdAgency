@extends('errors.layout')

@section('title', 'Page Not Found')

@section('content')
    {{-- Illustration --}}
    <img src="https://cdn-icons-png.flaticon.com/512/201/201623.png" 
         alt="Airplane Icon" 
         class="illustration">

    <div class="error-code">404</div>
    <div class="error-message">Oops! Page Not Found</div>
    <div class="error-description">
        Looks like you’ve taken a wrong turn on your journey.  
        This page doesn’t exist in our travel map.
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-primary">🏠 Back</a>

@endsection
