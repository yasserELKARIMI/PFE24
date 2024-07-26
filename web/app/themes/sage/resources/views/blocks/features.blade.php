<!-- Features Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            @foreach ($items as $item)
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                    <p class="section-title bg-white text-start text-primary pe-3">{{ $item['title'] }}</p>
                    <h1 class="mb-4">{{ $item['heading'] }}</h1>
                    <p class="mb-4">{{ $item['paragraph'] }}</p>
                    @foreach ($item['features'] as $feature)
                        <p><i class="fa fa-check text-primary me-3"></i>{{ $feature['text'] }}</p>
                    @endforeach
                    <a class="btn btn-secondary rounded-pill py-3 px-5 mt-3" href="{{ $item['button_link'] }}">{{ $item['button_text'] }}</a>
                </div>
                <div class="col-lg-6">
                    <div class="rounded overflow-hidden">
                        <div class="row g-0">
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                                <div class="text-center bg-primary py-5 px-4">
                                    <img class="img-fluid mb-4" src="{{ $item['experience_img']['url'] }}" alt="{{ $item['experience_img']['alt'] }}">
                                    <h1 class="display-6 text-white" data-toggle="counter-up">{{ $item['experience_years'] }}</h1>
                                    <span class="fs-5 fw-semi-bold text-secondary">{{ $item['experience_text'] }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.3s">
                                <div class="text-center bg-secondary py-5 px-4">
                                    <img class="img-fluid mb-4" src="{{ $item['award_img']['url'] }}" alt="{{ $item['award_img']['alt'] }}">
                                    <h1 class="display-6" data-toggle="counter-up">{{ $item['award_number'] }}</h1>
                                    <span class="fs-5 fw-semi-bold text-primary">{{ $item['award_text'] }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="text-center bg-secondary py-5 px-4">
                                    <img class="img-fluid mb-4" src="{{ $item['animal_img']['url'] }}" alt="{{ $item['animal_img']['alt'] }}">
                                    <h1 class="display-6" data-toggle="counter-up">{{ $item['animal_number'] }}</h1>
                                    <span class="fs-5 fw-semi-bold text-primary">{{ $item['animal_text'] }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6 wow fadeIn" data-wow-delay="0.7s">
                                <div class="text-center bg-primary py-5 px-4">
                                    <img class="img-fluid mb-4" src="{{ $item['client_img']['url'] }}" alt="{{ $item['client_img']['alt'] }}">
                                    <h1 class="display-6 text-white" data-toggle="counter-up">{{ $item['client_number'] }}</h1>
                                    <span class="fs-5 fw-semi-bold text-secondary">{{ $item['client_text'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Features End -->
