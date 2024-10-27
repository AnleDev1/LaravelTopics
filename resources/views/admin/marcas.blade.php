@extends('adminlte::page')

@section('title', 'Marcas')

@section('content_header')
    <h1>Marcas</h1>
@stop

@section('content')
    <div id="app">
        <marca-component></marca-component>
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