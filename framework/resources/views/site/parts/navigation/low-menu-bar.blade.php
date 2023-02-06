<header>
  <nav class="low-bar">
    <div class="container-fluid">
      <div class="d-flex align-items-center justify-content-between" id="low-bar-menu">
        <ul class="d-flex group-top-menu">
          <li>
            <a class="{{ (request()->is('/')) ? 'active' : '' }}" href="{{ route('site.home') }}">Home</a>
          </li>
          <li>
            <a class="{{ (request()->is('products')) ? 'active' : '' }}" href="{{ route('site.products.index') }}">Browse E-Books</a>
          </li>
          <li>
            <a class="{{ (request()->is('gift-card')) ? 'active' : '' }}" href="{{ route('site.gift-card.index') }}">Gift cards</a>
          </li>
          <li>
            <a class="{{ (request()->is('testimonials')) ? 'active' : '' }}" href="{{ route('site.testimonials.index') }} ">Testimonials</a>
          </li>
          <li>
            <a target="_blank" href="https://forwardchess.com/blog/">Blog</a>
          </li>
          <li>
            <a class="{{ (request()->is('faq') || request()->is('faq/*')) ? 'active' : '' }}" href="{{ route('site.faq.index') }}">FAQ</a>
          </li>
          <li>
            <a class="{{ (request()->is('contact')) ? 'active' : '' }}" href="{{ route('site.contact.show') }}">Contact us</a>
          </li>
          <li>
            <a class="{{ (request()->is('affiliate')) ? 'active' : '' }}" href="{{ route('site.affiliate') }}">Affiliate</a>
          </li>
        </ul>
        <div class="top-search-holder">
          <div class="search-area">
            <search-form
              initial-value="{{ isset($headerSearch) ? $headerSearch : '' }}"
              action="{{ productsPageUrl() }}"
              environment="{{ env('APP_ENV') }}"></search-form>
          </div><!-- /.search-area -->
        </div>
      </div>
    </div>
  </nav>
</header>
