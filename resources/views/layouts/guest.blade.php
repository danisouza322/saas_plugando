@extends('layouts.master-without-nav')
@section('title')
@lang('translation.signup')
@endsection
@section('content')

    
    @livewireStyles
</head>
<body>
    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <div class="auth-page-content">
            {{ $slot }}
        </div>
    </div>
    
    <!-- Velzon Scripts -->
    @endsection
        @section('script')
            <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/form-validation.init.js') }}"></script>
            <script src="{{ URL::asset('build/js/pages/passowrd-create.init.js') }}"></script>
        @endsection
    
    @livewireScripts
</body>
</html>