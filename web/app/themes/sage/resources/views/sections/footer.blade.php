
    <div class="container-fluid bg-dark footer mt-5 py-5 wow fadeIn" data-wow-delay="0.1s">

        <div class="container py-5">
                <div class="row g-5">
                    @php(dynamic_sidebar('sidebar-footer'))
                </div>
            </div>
        </div>

    </div>
     <!-- Copyright Start -->
    <div class="container-fluid bg-secondary text-body copyright py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <?php echo date("Y");?>  &copy; <a class="fw-semi-bold">{!! $siteName !!}</a>, All Right Reserved.
                </div>
                <div class="col-md-6 text-center text-md-end">

                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->
