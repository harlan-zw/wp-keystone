@php
    $uniqid = uniqid('cards-');
    $columns = 12 / $posts_per_row;
    $btn_class = count($buttons) >= 4 ? 'btn-sm' : '';
    $is_colour_class_dark = \App\is_colour_class_dark($background_colour);
@endphp
<section class="section section--padded  section--{{ $background_colour ?? '' }}">
    <div class="{{ $container_width }}">
        @if(!empty($header))
            <div class="section__header">
                <h2>{!! $header !!}</h2>
            </div>
        @endif

        <div class="row">
            @foreach($cards as $card)
                <div class="col-md-{{ $columns }} col-sm-6">
                    @include('widgets.card.card', ['card' => $card, 'uniqid' => $uniqid])
                </div>
            @endforeach
        </div>
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
</section>
