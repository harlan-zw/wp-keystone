<div class="{{ $container_width }}">
    <section
            @if($background_type === 'parallax-image')
            class="banner banner--image banner--{{ $container_width }}" data-parallax="scroll" style="height: {{ $background_image_height ?? '100' }}vh" data-image-src="{!! wp_get_attachment_image_src($background_image, 'full')[0] !!}"
            @elseif($background_type === 'image')
            class="banner lazyload banner--image banner--{{ $container_width }}" style="height: {{ $background_image_height }}vh" data-bgset="{!! wp_get_attachment_image_srcset($background_image, 'full') !!}"
            @else
            class="banner banner--slider banner--{{ $container_width }}"
            @endif
    >
        @if($background_type === 'slider')
            {!! putRevSlider($slider_alias) !!}
        @endif
        <div class="container">
            <div class="row">
                @if(!empty($title))
                    <h1 class="title">{{ $title }}</h1>
                @endif
                @if(!empty($sub_title))
                    <p class="sub-title">
                        {{ $sub_title }}
                    </p>
                @endif
                @if(!empty($buttons))
                    <div class="col-md-8 col-md-offset-2 banner__buttons">
                        @foreach($buttons as $button)
                            <a
                                    data-aos="fade-up"
                                    data-aos-offset="300"
                                    data-aos-duration="1000"
                                    href="{{ $button['link'] }}" class="btn {{ $background_type === 'slider' ? 'btn-light' : 'btn-solid'  }}" target="{{ $button['link_type'] == 'same_window' ? '_self' : '_blank' }}">{{ $button['button_text'] }}</a>
                        @endforeach
                    </div>
                @endif
                @if($show_scroll_helper)
                    <div class="mouse-scroll">
                        <div class="mouse-scroll__mouse">
                            <span class="mouse-scroll__scroller"></span>
                        </div>
                        Scroll
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
