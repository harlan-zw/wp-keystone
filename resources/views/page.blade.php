@extends('layouts.app')

@section('content')

    <h1>hi</h1>

    @php
        do_action('acf-widget/render');
    @endphp

@endsection
