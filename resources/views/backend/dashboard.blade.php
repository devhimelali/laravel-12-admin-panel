@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-2">
        <div class="mb-3">
            <h1 class="mb-1">Welcome, {{ucwords(auth()->user()->name)}}</h1>
            <p class="fw-medium">You have <span class="text-primary fw-bold">200+</span> Orders, Today</p>
        </div>
    </div>
@endsection
