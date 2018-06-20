@php
  if (!empty ($related_post)) {
    $post = $related_post;
  }

  $uniqid = uniqid('blog-');
  $primary_category = \App\get_primary_category($post);
@endphp
<article class="card blog-listing ">
  <div class="blog-listing__img" data-mh="{{ $uniqid }}">
    <a class="blog-listing__img-bg lazyload" data-bgset="{{ wp_get_attachment_image_src(get_post_thumbnail_id($post), ['300', '600'])[0]  }}" href="{{ get_permalink() }}"></a>
  </div>
  <div class="blog-listing__inner card_inner" data-mh="{{ $uniqid }}">
    <h4 class="blog-listing__title">
      <a href="{{ get_permalink($post) }}">
        {{ get_the_title($post) }}
      </a>
    </h4>
    <div class="blog__meta">
      @if(!empty($primary_category))
      <a class="blog__meta-link" href="{{ get_category_link($primary_category->cat_ID) }}">
        <i class="blog-listing__icon far fa-tag" aria-hidden="true"></i>
        {{ $primary_category->name }}
      </a>
      @endif
      <div class="blog__meta-link">
        <i class="blog-listing__icon far fa-calendar" aria-hidden="true"></i>
        {{ get_the_date('F d, Y', $post) }}
      </div>
    </div>
    {{--<a class="blog-listing__meta" href="{{ the_author_posts_link() }}">--}}
        {{--<i class="blog-listing__icon fa fa-user" aria-hidden="true"></i>--}}
        {{--{{ get_the_author() }}--}}
    {{--</a>--}}
    <p class="blog-listing__copy">
     {{ get_the_excerpt() }}
    </p>
    <a class="blog-listing__more" href="{{ get_permalink($post) }}">Read more</a>
  </div>
</article>