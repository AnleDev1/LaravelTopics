@extends('adminlte::page')

@section('title', 'Modelos')

@section('content_header')
    <h1>Modelos</h1>
@stop

@section('content')
    <div id="app">
        <modelo-component></modelo-component>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @vite('resources/css/app.css')
@stop

@section('js')
    @vite('resources/js/app.js')
@stop