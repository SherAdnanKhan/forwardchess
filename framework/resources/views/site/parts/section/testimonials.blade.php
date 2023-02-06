<!-- ========================================= TOP BRANDS ========================================= -->
@if (isset($testimonials))
    <section class="testimonials">
        <div class="container">
            <div class="title-nav">
                <h1>Testimonials</h1>
            </div>

            <div class="row">
                @foreach ($testimonials as $testimonial)
                    <div class="col-md-4">
                        <div class="testimonial">
                            <img alt="{{ $testimonial['name'] }}" src="{{ $testimonial['image'] }}" class="image"/>
                            <div class="name">{{ $testimonial['name'] }}</div>
                            <div class="description">{{ $testimonial['description'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
<!-- ========================================= TOP BRANDS : END ========================================= -->
