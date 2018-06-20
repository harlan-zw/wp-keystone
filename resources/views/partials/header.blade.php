<header class="header" data-headroom data-offset="145">
    <div class="container">
        <div class="header__main">
            <a href="/">
                {!! App\get_main_logo_markup('header__logo') !!}
            </a>
            <button class="header__trigger collapsed" type="button" data-toggle="collapse" data-target="#js-header-menu" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="header__trigger-bar"></span>
                <span class="header__trigger-bar"></span>
                <span class="header__trigger-bar"></span>
            </button>
        </div>

        <nav class="header__nav header__nav--desktop">
            {!! wp_nav_menu(['theme_location' => \App\PRIMARY_NAV_SLUG, 'menu_class' => 'header__links', 'desktop' => true ]) !!}
        </nav>

        <nav class="header__nav collapse header__nav--mobile" id="js-header-menu">
            {!! wp_nav_menu(['theme_location' => \App\PRIMARY_NAV_SLUG, 'menu_class' => 'header__links', 'desktop' => false ]) !!}
        </nav>


        @if(!empty(\App\get_option_page_value('header_buttons')))
            <div class="header__actions header__actions--desktop">
                @foreach(\App\get_option_page_value('header_buttons') as $button)
                    <a class="{{ $button['button_classes'] }}" href="{{ $button['button_link'] }}">{!! $button['button_title'] !!}</a>
                @endforeach
            </div>
            <div class="header__actions header__actions--mobile">
                @foreach(\App\get_option_page_value('header_buttons') as $button)
                    <a class="{{ $button['button_classes'] }}" href="{{ $button['button_link'] }}">{!! $button['button_title'] !!}</a>
                @endforeach
            </div>
        @endif

    </div>
</header>
