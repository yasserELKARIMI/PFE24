<div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto pb-4 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <p class="section-title bg-white text-center text-primary px-3">Our Services</p>
                <h1 class="mb-5">Services That We Offer For Entrepreneurs</h1>
            </div>
            <div class="row gy-5 gx-4">
              @php $delay = 0.1; @endphp
                @foreach ($items as $item)
                <div class="col-lg-4 col-md-6 pt-5 wow fadeInUp" data-wow-delay="{{ $delay }}s">
                    <div class="service-item d-flex h-100">
                        <div class="service-img">
                          @if(isset($item['image']) && !empty($item['image']))
                            <img class="img-fluid" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['url'] }}">
                          @endif
                        </div>
                        <div class="service-text p-5 pt-0">
                            <div class="service-icon">
                                <img class="img-fluid rounded-circle" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['url'] }}">
                            </div>
                            <h5 class="mb-3">{{ $item['title'] }}</h4>
                            <p class="mb-4">{{ $item['paragraph'] }}</p>
                            <a class="btn btn-square rounded-circle" href="{{ $item['link'] }}"><i class="bi bi-chevron-double-right"></i></a>
                        </div>
                    </div>
                </div>
                @php $delay += 0.2; @endphp
              @endforeach
            </div>
        </div>
    </div>
    <!-- Service End -->

    <!-- Gallery Start -->
    <div class="container-xxl py-5 px-0">
        <div class="row g-0">
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-0">
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-5.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-5.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-1.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-1.jpg') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="row g-0">
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-2.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-2.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-6.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-6.jpg') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="row g-0">
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-7.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-7.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-3.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-3.jpg') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                <div class="row g-0">
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-4.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-4.jpg') }}" alt="">
                        </a>
                    </div>
                    <div class="col-12">
                        <a class="d-block" href="{{ asset('images/gallery-8.jpg') }}" data-lightbox="gallery">
                            <img class="img-fluid" src="{{ asset('images/gallery-8.jpg') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Gallery End -->
