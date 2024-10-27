@extends('adminlte::page')

@section('title', 'Admin')

@section('content_header')
    <h1>Admin</h1>
@stop

@section('content')
    <p>Pene.</p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @vite('resources/css/app.css')
@stop

@section('js')
    @vite('resources/js/app.js')
@stop