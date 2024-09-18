@extends('layouts.master')
@section('title')
@lang('translation.dashboards')
@endsection
@section('content')
{{ $slot }}
@endsection
@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showToast', (message) => {
                Toastify({
                    text: message,
                    duration: 3000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "right", // `left`, `center` or `right`
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                    },
                }).showToast();
            });
        });
    </script>
@endsection