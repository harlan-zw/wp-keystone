<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col-md-9">

        {!! wp_nav_menu(['theme_location' => \App\FOOTER_NAV_SLUG, 'menu_class' => 'footer__links' ]) !!}

        <ul class="list-icon">
          <li class="list-icon__item">
            <i class="list-icon__icon far fa-phone" aria-hidden="true"></i>
            <a href="tel:{!! \App\get_URI_formatted_phone_number(\App\get_option_page_value('contact_telephone')) !!}">{{ \App\get_option_page_value('contact_telephone') }}</a>
          </li>
          <li class="list-icon__item">
            <i class="list-icon__icon far fa-envelope" aria-hidden="true"></i>
            <a href="mailto:{!! \App\get_option_page_value('contact_email') !!}">{!! \App\get_option_page_value('contact_email') !!}</a>
          </li>
          <li class="list-icon__item">
            <i class="list-icon__icon far fa-map-marker" aria-hidden="true"></i>
            {!! \App\get_option_page_value('street') !!}, {!! \App\get_option_page_value('suburb') !!},<br>
            {!! \App\get_option_page_value('state') !!} {!! \App\get_option_page_value('postcode') !!}, Australia
          </li>
        </ul>
      </div>
      <div class="col-md-3">
        <ul class="social">
          @foreach(\App\get_social_medias() as $sm)
            <li class="social__icon">
              <a href="{{ $sm['url'] }}" class="fab {{ $sm['icon'] }}"></a>
            </li>
          @endforeach
        </ul>
        <p>
          {!! \App\get_option_page_value(\App\OPTION_PAGE_FOOTER) !!}
        </p>
      </div>
    </div>
  </div>
</footer>

@include('partials/campaign-popup')
