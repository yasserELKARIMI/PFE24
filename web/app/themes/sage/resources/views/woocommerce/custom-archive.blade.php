@php
  global $wp_query;
@endphp
<div class="container-xxl py-5">
  <div class="container">
    <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
      @if (have_posts())
      <h1 class="mb-5">Our Dairy Products For Healthy Living</h1>
      <h2 class="mb-5">{{ $wp_query->found_posts }} Products Found</h2>
      @endif
      @if (! have_posts())
      {{-- {!! __('Sorry, no results were found', 'sage') !!} --}}
      <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
@php
  global $wp_query;
@endphp
<div class="container-xxl py-5">
  <div class="container">
    <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
      @if (have_posts())
      <h1 class="mb-5">Our Dairy Products For Healthy Living</h1>
      <h2 class="mb-5">{{ $wp_query->found_posts }} Products Found</h2>
      @endif
      @if (! have_posts())
      {{-- {!! __('Sorry, no results were found', 'sage') !!} --}}
      <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
          <h2 class="mb-5">Sorry, no results were found<br>:'( </h2>
      @endif
    </div>
    @if (have_posts())
    <div class="row mb-4">
      <div class="col-md-6">
          <p class="woocommerce-result-count">Showing all {{ $wp_query->found_posts }} results</p>
      </div>
      <div class="col-md-6 text-md-end">
          <form class="woocommerce-ordering" method="get">
              <select name="orderby" class="orderby" aria-label="Shop order" onchange="this.form.submit()">
                  <option value="menu_order" {{ request('orderby') == 'menu_order' ? 'selected' : '' }}>Default sorting</option>
                  <option value="popularity" {{ request('orderby') == 'popularity' ? 'selected' : '' }}>Sort by popularity</option>
                  <option value="rating" {{ request('orderby') == 'rating' ? 'selected' : '' }}>Sort by average rating</option>
                  <option value="date" {{ request('orderby') == 'date' ? 'selected' : '' }}>Sort by latest</option>
                  <option value="price" {{ request('orderby') == 'price' ? 'selected' : '' }}>Sort by price: low to high</option>
                  <option value="price-desc" {{ request('orderby') == 'price-desc' ? 'selected' : '' }}>Sort by price: high to low</option>
              </select>
              <input type="hidden" name="paged" value="{{ $items['current_page'] }}">
          </form>
      </div>
    </div>
    @endif
    <div class="row gx-4">
      @while(have_posts()) @php(the_post())
        @include('partials.content-search')
      @endwhile
    </div>
    {!! get_the_posts_navigation() !!}
  </div>
</div>

      </div>
      @endif
    </div>
    @if (have_posts())
    <div class="row mb-4">
      <div class="col-md-6">
          <p class="woocommerce-result-count">Showing all {{ $wp_query->found_posts }} results</p>
      </div>
      <div class="col-md-6 text-md-end">
          <form class="woocommerce-ordering" method="get">
              <select name="orderby" class="orderby" aria-label="Shop order" onchange="this.form.submit()">
                  <option value="menu_order" {{ request('orderby') == 'menu_order' ? 'selected' : '' }}>Default sorting</option>
                  <option value="popularity" {{ request('orderby') == 'popularity' ? 'selected' : '' }}>Sort by popularity</option>
                  <option value="rating" {{ request('orderby') == 'rating' ? 'selected' : '' }}>Sort by average rating</option>
                  <option value="date" {{ request('orderby') == 'date' ? 'selected' : '' }}>Sort by latest</option>
                  <option value="price" {{ request('orderby') == 'price' ? 'selected' : '' }}>Sort by price: low to high</option>
                  <option value="price-desc" {{ request('orderby') == 'price-desc' ? 'selected' : '' }}>Sort by price: high to low</option>
              </select>
              <input type="hidden" name="paged" value="{{ $items['current_page'] }}">
          </form>
      </div>
    </div>
    @endif
    <div class="row gx-4">
      @while(have_posts()) @php(the_post())
        @include('partials.content-search')
      @endwhile
    </div>
    {!! get_the_posts_navigation() !!}
  </div>
</div>
