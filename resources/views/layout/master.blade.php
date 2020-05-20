@extends('layout.base')

@section('body')
    <div>
        @include('layout.navbar')
    </div>

    <div>
        @yield('content')
    </div>

@endsection
