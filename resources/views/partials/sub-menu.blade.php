@if(!get_field('show_sub_header'))
    <a class="return-top return-top--attached" href="#return-top">
        <i class="far fa-arrow-up" aria-hidden="true"></i>
    </a>
@else
    <div class="submenu">
        <ul>
            @foreach(get_field('sub_menu_links') as $link)
                <li><a href="{{ $link['link'] }}">{{ $link['title'] }}</a></li>
            @endforeach
        </ul>
    </div>
@endif