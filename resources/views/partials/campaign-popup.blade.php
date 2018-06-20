@if(\App\get_option_page_value('campaign_enabled') && get_field('page_pop_up'))
    <div class="campaign hidden fixed" data-campaign="{!! \App\get_option_page_value('campaign_title') !!}">
        <a class="campaign__close" href="#"><i class="far fa-times" aria-hidden="true"></i></a>
        <div class="campaign__inner">
            {!! wp_get_attachment_image(\App\get_option_page_value('campaign_image')['id'], ['500', '400'], "", [ "class" => "campaign__img" ]) !!}
            <div class="campaign__content">
                <h4 class="campaign__title">{!! \App\get_option_page_value('campaign_header') !!}</h4>
                <div class="campaign__desc">
                    {!! \App\get_option_page_value('campaign_text') !!}
                </div>
                <a target="_blank" href="{!! \App\get_option_page_value('campaign_cta_link') !!}"
                   class="btn btn-primary btn-xs">
                    {!! \App\get_option_page_value('campaign_cta_title') !!}
                </a>
            </div>
            <svg class="campaign__speech" viewBox="0 0 45 32">
                <path class="cls-1"
                      d="M41.66,32A3.39,3.39,0,0,0,45,28.57V0H0C18.07,0,33.45,12.47,38.47,29.47A3.38,3.38,0,0,0,41.66,32"/>
            </svg>
        </div>
        <div class="campaign__footer">
            <a target="_blank" href="{!! \App\get_option_page_value('campaign_cta_link') !!}">
                {!! wp_get_attachment_image(\App\get_option_page_value('campaign_circle_image')['id'], ['150', '150'], "", [ "class" => "campaign__profile" ]) !!}
            </a>
        </div>
    </div>
@endif
