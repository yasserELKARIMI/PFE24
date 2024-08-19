    <!-- Topbar Start -->
    <div class="container-fluid bg-dark px-0">
      <div class="row g-0 d-none d-lg-flex">
          <div class="col-lg-6 ps-5 text-start">
              <div class="h-100 d-inline-flex align-items-center text-light">
                  <span>Follow Us:</span>
                  <a class="btn btn-link text-light" href=""><i class="fab fa-facebook-f"></i></a>
                  <a class="btn btn-link text-light" href=""><i class="fab fa-twitter"></i></a>
                  <a class="btn btn-link text-light" href=""><i class="fab fa-linkedin-in"></i></a>
                  <a class="btn btn-link text-light" href=""><i class="fab fa-instagram"></i></a>
              </div>
          </div>
          <div class="col-lg-6 text-end">
              <div class="h-100 bg-secondary d-inline-flex align-items-center text-dark py-2 px-4">
                  <span class="me-2 fw-semi-bold"><i class="fa fa-phone-alt me-2"></i>Call Us:</span>
                  <span>+012 345 6789</span>
              </div>
          </div>
      </div>
  </div>
  <!-- Topbar End -->

  <!-- Headerbar End -->
  @if (has_nav_menu('primary_navigation'))
  <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5">
      <a href="{{ home_url('/') }}" class="navbar-brand d-flex align-items-center">
          <h1 class="m-0">{!! $siteName !!}</h1>
      </a>
      <button type="button" class="navbar-toggler me-0" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav ms-auto p-4 p-lg-0">
              {!! wp_nav_menu([
                  'theme_location' => 'primary_navigation',
                  'container' => 'div',
                  'container_class' => 'menu-header-container',
                  'menu_class' => 'nav',
                  'walker' => new \App\Walkers\Custom_Nav_Walker(),
                  'echo' => false
              ]) !!}
              <!-- Search Bar -->
              <div class="position-relative search-bar" style="margin-top: 10px;">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                  <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="search" placeholder="Search products..." name="s" value="<?php echo get_search_query(); ?>" />
                  <input type="hidden" name="post_type" value="product" />
                  <button type="submit" class="btn btn-secondary py-2 position-absolute top-0 end-0 mt-2 me-2"><i class="fa fa-search"></i></button>
              </form>
              </div>
          </div>
          <!-- Search icon -->
          <div class="border-start ps-4 d-none d-lg-block">
              <button type="button" class="btn btn-sm p-0 toggle-search"><i class="fa fa-search"></i></button>
          </div>
      </div>
  </nav>
@endif

  <script>
      document.addEventListener('DOMContentLoaded', function () {
          const searchButton = document.querySelector('.toggle-search');
          const searchBar = document.querySelector('.search-bar');

          // Check the initial width of the window and adjust the display accordingly
          function initializeSearchBarDisplay() {
              if (window.innerWidth < 992) {
                  // For small screens, always show the search bar
                  searchBar.style.display = 'block';
              } else {
                  // For large screens, use the CSS-defined state
                  searchBar.style.display = getComputedStyle(searchBar).display === 'none' ? 'none' : 'block';
              }
          }

          initializeSearchBarDisplay();  // Initialize on document load

          searchButton.addEventListener('click', function () {
              searchBar.style.display = searchBar.style.display === 'none' ? 'block' : 'none';
          });

          // Optional: Adjust visibility on window resize
          window.addEventListener('resize', initializeSearchBarDisplay);
      });

  </script>
  <!-- Headerbar End -->
