<div class="card card--center card--primary {{ !empty($card['buttons']) ? 'card--with-buttons' : '' }}">
    <div class="card__inner">
        @if(!empty($card['image']))
            <div class="card__img card__img--fixed lazysizes__container">
                {!! wp_get_attachment_image($card['image'], ['500', '250'], '', [ 'class' => 'lazysizes__img' ]) !!}
            </div>
        @endif
        <div class="card__content"  data-mh="{{ $uniqid }}">
            @if(!empty($card['title']))
                <h4 class="card__title">{!! $card['title'] !!}</h4>
            @endif
            @if (!empty($card['description']))
                <div class="card__desc">
                    {!! $card['description'] !!}
                </div>
            @endif
            <div class="card__buttons">
                @if(!empty($card['buttons']))
                    @foreach($card['buttons'] as $button)
                        <a href="{{ $button['link'] }}" class="btn btn-primary btn-sm"
                           target="{{ $button['link_type'] == 'same_window' ? '_self' : '_blank' }}">
                            {{ $button['button_text'] }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
