@extends('layouts.themed')

@push('theme-css')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('js')
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
