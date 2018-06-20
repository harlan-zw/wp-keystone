<!doctype html>
<html @php(language_attributes())>
@include('partials.head')
<body @php(body_class())>
@php(do_action('get_header'))
@include('partials.header')
<main class="main content">
  @yield('content')
</main>
@php(do_action('get_footer'))
@include('partials.footer')
@include('partials.sub-menu')

@php(wp_footer())
</body>
</html>
