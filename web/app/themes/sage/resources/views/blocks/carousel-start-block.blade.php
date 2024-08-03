<div class="container-fluid px-0 mb-5">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($items as $item)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    @if(isset($item['background']) && !empty($item['background']))
                        <img class="w-100" src="{{ $item['background']['url'] }}" alt="{{ $item['background']['alt'] }}">
                    @endif
                    <div class="carousel-caption">
                        <div class="container">
                            <div class="row justify-content-{{ $item['alignment'] }}">
                                <div class="col-lg-8 text-{{ $item['text_alignment'] }}">
                                    <p class="fs-4 text-white">{{ $item['paragraph'] }}</p>
                                    <h1 class="display-1 text-white mb-5 animated slideInRight">{{ $item['title'] }}</h1>
                                    <a href="{{ $item['explore_more_link'] }}" class="btn btn-secondary rounded-pill py-3 px-5 animated slideInRight">Explore More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
