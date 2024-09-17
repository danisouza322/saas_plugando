@extends('layouts.master')
@livewireStyles
@section('title')
@lang('translation.dashboards')
@endsection
@section('content')
{{ $slot }}
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@livewireScripts
@endsection