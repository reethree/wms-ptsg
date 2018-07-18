@extends('layout')

@section('content')

    Welcome, {{ Auth::getUser()->name }}
    
@endsection

@section('custom_js')

@endsection