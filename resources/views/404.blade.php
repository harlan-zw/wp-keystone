@extends('layouts.app')

@section('content')

    {{-- Very Simple Page for 404 --}}

    @include('widgets.wysiwyg.markup', [
        'header' => 'Page Not Found',
        'wysiwyg_content' => '
            <p class="text-center">Sorry about that.</p>
        ',
        'container_width' => 'small-container'
    ])

@endsection
