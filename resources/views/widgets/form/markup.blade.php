@php
    $uniqid = uniqid('form-', true);
@endphp

<section class="section section--padded wysiwyg wysiwyg--{{ $background_colour ?? '' }}">
    <div class="container">
        <div class="wysiwyg-container clearfix">
            <div class="row">
                @if(!empty($background_image))
                    <div class="col-md-5" data-mh="{{ $uniqid }}">
                        {!! wp_get_attachment_image($background_image, 'full')  !!}
                    </div>
                    <div class="col-md-7 form--vertical" data-mh="{{ $uniqid }}">
                        {!! apply_filters('the_content', '[gravityform title="true" id="' . $gravity_form_id . '"]') !!}
                    </div>
                @else
                    <div class="form--inline">
                        {!! apply_filters('the_content', '[gravityform title="true" id="' . $gravity_form_id . '"]') !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
