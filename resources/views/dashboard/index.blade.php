@extends('layouts.master')
@section('title') {{$titulo}} @endsection
@section('content')
    @livewire('dashboard.index')
@endsection
