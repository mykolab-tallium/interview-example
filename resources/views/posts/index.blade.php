@extends('layouts.app')

@section('content')
    @if(session()->has('error'))
        <div class="alert alert-danger">{{ session()->get('error') }}</div>
    @enderror
    <div class="container">
        <div class="row justify-content-center">
        </div>
    </div>
@endsection
