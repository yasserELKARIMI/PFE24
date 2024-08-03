<div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.1s">
  <div class="product-item">
    <div class="position-relative">
      <img class="img-fluid" src="{{ get_the_post_thumbnail_url() }}" alt="{{ get_the_title() }}">
      <div class="product-overlay">
        <a class="btn btn-square btn-secondary rounded-circle m-1" href="{{ get_permalink() }}"><i class="bi bi-cart"></i></a>
      </div>
    </div>
    <div class="text-center p-4">
      <a class="d-block h5" href="{{ get_permalink() }}">{{ get_the_title() }}</a>
      @php
        $price = get_post_meta(get_the_ID(), '_price', true);
        $formatted_price = $price ? wc_price($price) : __('N/A', 'sage');
      @endphp
      <span class="text-primary me-1">{!! $formatted_price !!}</span>
    </div>
  </div>
</div>
