@php
    $btn_class = count($buttons) >= 4 ? 'btn-sm' : '';
    $is_colour_class_dark = \App\is_colour_class_dark($background_colour);
@endphp
<section class="section section--padded wysiwyg wysiwyg--{{ $background_colour ?? '' }}">
    <div class="{{ is_singular('post') ? 'small-container' : $container_width }}">
        <div
            class="wysiwyg-container clearfix">
            @if(!empty($header))
                <div class="section__header">
                    <h2>{!! $header !!}</h2>
                </div>
            @endif
            {!! apply_filters('the_content', $wysiwyg_content) !!}
            @if(!empty($buttons))
                <div class="text-center mt-3 wysiwyg__buttons">
                    @foreach($buttons as $button)
                        <a href="{{ $button['link'] }}" class="btn {{ $is_colour_class_dark ? 'btn-light' : 'btn-primary' }} {{ $btn_class }}"
                           target="{{ $button['link_type'] == 'same_window' ? '_self' : '_blank' }}">
                            {{ $button['button_text'] }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
