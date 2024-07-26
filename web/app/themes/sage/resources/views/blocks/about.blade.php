<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="row g-0 about-bg rounded overflow-hidden">
                    <div class="col-sm-6 wow fadeIn" data-wow-delay="0.1s">
                        <div class="text-center bg-primary py-5 px-4">
                            <img class="img-fluid mb-4" src="{{ $about_image['url'] }}" alt="{{ $about_image['alt'] }}">
                            <h1 class="display-6 text-white" data-toggle="counter-up">{{ $experience_years }}</h1>
                            <span class="fs-5 fw-semi-bold text-secondary">{{ $experience_text }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
                <p class="section-title bg-white text-start text-primary pe-3">{{ $about_title }}</p>
                <h1 class="mb-4">{{ $about_content }}</h1>
            </div>
        </div>
    </div>
</div>
<!-- About End -->
