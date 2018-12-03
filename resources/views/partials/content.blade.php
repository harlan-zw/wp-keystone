<div class="card">
    <div class="card-header">
        <h2 class="card-title"><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h2>
    </div>
    <div class="card-body">
        <div class="card-text">
            @include('partials/entry-meta')

            @php the_excerpt() @endphp
        </div>
    </div>
</div>
