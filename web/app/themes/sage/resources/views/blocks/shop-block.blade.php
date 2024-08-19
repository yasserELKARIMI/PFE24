<!-- This Block is not used for the moment, it displays 12 products per page -->
<!-- Product Start -->
<div class="container-xxl py-5">
  <div class="container">
      <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
          <p class="section-title bg-white text-center text-primary px-3">Our Products</p>
          <h1 class="mb-5">Our Dairy Products For Healthy Living</h1>
      </div>
      <div class="row mb-4">
          <div class="col-md-6">
              <p class="woocommerce-result-count">Showing all {{ $items['total_products'] }} results</p>
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
      <div class="row gx-4">
          @foreach ($items['items'] as $item)
              <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
                  <div class="product-item">
                      <div class="position-relative">
                          <img class="img-fluid" src="{{ $item['image'] }}" alt="{{ $item['title'] }}">
                          <div class="product-overlay">
                              <a class="btn btn-square btn-secondary rounded-circle m-1" href="{{ $item['cart_link'] }}"><i class="bi bi-cart"></i></a>
                          </div>
                      </div>
                      <div class="text-center p-4">
                          <a class="d-block h5" href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                          <span class="text-primary me-1">{!! $item['price'] !!}</span>
                      </div>
                  </div>
              </div>
          @endforeach
      </div>
      <!-- Pagination -->
      <div class="row mt-4">
          <div class="col-md-12">
              <nav class="woocommerce-pagination">
                  @if ($items['current_page'] > 1)
                      <a class="page-numbers" href="{{ add_query_arg(['paged' => $items['current_page'] - 1, 'orderby' => request('orderby')]) }}">Previous</a>
                  @endif
                  @for ($i = 1; $i <= $items['max_num_pages']; $i++)
                      <a class="page-numbers {{ $items['current_page'] == $i ? 'current' : '' }}" href="{{ add_query_arg(['paged' => $i, 'orderby' => request('orderby')]) }}">{{ $i }}</a>
                  @endfor
                  @if ($items['current_page'] < $items['max_num_pages'])
                      <a class="page-numbers" href="{{ add_query_arg(['paged' => $items['current_page'] + 1, 'orderby' => request('orderby')]) }}">Next</a>
                  @endif
              </nav>
          </div>
      </div>
  </div>
</div>
<!-- Product End -->
