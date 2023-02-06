<section class="section-top-bar">
    <!-- ============================================================= TOP NAVIGATION ============================================================= -->
    <nav class="top-bar">
        <div class="container-fluid">

            <div class="d-flex align-items-center justify-content-between" id="top-bar-menu">

                <div id="menuToggle" class="d-flex flex-column">
                    <input type="checkbox" data-toggle="modal" class="d-flex" data-target="#nav-top-header"/>
                    <svg width="20" height="15" viewBox="0 0 20 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 13.813C0 13.1534 0.510195 12.626 1.13082 12.626H10C10.6206 12.626 11.1308 13.1534 11.1308 13.813C11.1308 14.4726 10.6206 15 10 15H1.13082C0.510195 15 0 14.4726 0 13.813Z"
                              fill="black"/>
                        <path d="M0 7.37116C0 6.71159 0.510195 6.18415 1.13082 6.18415H18.8692C19.4898 6.18415 20 6.71159 20 7.37116C20 8.03074 19.4898 8.55818 18.8692 8.55818H1.13082C0.510195 8.55818 0 8.03074 0 7.37116Z"
                              fill="black"/>
                        <path d="M0 1.18701C0 0.527438 0.510195 0 1.13082 0H18.8692C19.4898 0 20 0.527438 20 1.18701C20 1.84659 19.4898 2.37403 18.8692 2.37403H1.13082C0.510195 2.37403 0 1.84659 0 1.18701Z"
                              fill="black"/>
                    </svg>
                </div>

                <!-- Modal -->
                <div class="modal fade nav-top-header" id="nav-top-header" tabindex="-1" role="dialog"
                     aria-labelledby="nav-top-header">
                    <div class="modal-dialog h-100" role="document">
                        <div class="modal-content h-100">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span>
                                </button>
                                <div class="wrapper-modal-title d-flex flex-column">
                                    <a href="{{ route('site.home') }}">Forward chess</a>
                                    <span>Your Partner in Improving Your Play</span>
                                </div>
                            </div>
                            <div class="modal-body">
                                <ul id="modal-top" class="modal-top">
                                    <li class="modal-top">
                                        <a class="active"
                                           href="{{ route('site.home') }}">Home</a>
                                    </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.products.index') }}">Browse E-Books</a>
                                    </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.gift-card.index') }}">Gift cards</a>
                                    </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.testimonials.index') }} ">Testimonials</a>
                                    </li>
                                     <li class="modal-top">
                                         <a target="_blank" href="https://forwardchess.com/blog/">Blog</a>
                                     </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.faq.index') }}">FAQ</a>
                                    </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.contact.show') }}">Contact us</a>
                                    </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.affiliate') }}">Affiliate</a>
                                    </li>
                                    <li class="modal-top">
                                        <a href="{{ route('site.shoppingCart') }}">My Cart</a>
                                    </li>
                                    @guest
                                        <li class="modal-top">
                                            <a class="sign-up w-100 modal-top d-flex justify-content-center align-items-center" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                        </li>
                                        <li class="modal-top">
                                            <a class="sign-in w-100 modal-top d-flex justify-content-center align-items-center" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                                        </li>
                                    @else
                                        @if (Auth::user()->isAdmin)
                                            <li class="modal-top" role="presentation">
                                                <a role="menuitem" tabindex="-1"
                                                   href="{{ url(env('APP_BACKEND_PATH')) }}">
                                                    Backend
                                                </a>
                                            </li>
                                        @endif

                                        <li class="modal-top">
                                            <a href="{{ route('site.profile.index') }}">My Account</a>
                                        </li>

                                        <li class="modal-top">
                                            <a class="sign-up w-100 modal-top d-flex justify-content-center align-items-center"
                                               href="{{ route('site.user-guide') }}">
                                                <svg class="m-r-10 m-b-2" width="12" height="15" viewBox="0 0 12 15" fill="currentColor"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.1758 6.14251C11.4838 5.8255 11.2592 5.29412 10.8172 5.29412H9.07143C8.79529 5.29412 8.57143 5.07026 8.57143 4.79412V0.5C8.57143 0.223858 8.34757 0 8.07143 0H3.92857C3.65243 0 3.42857 0.223858 3.42857 0.5V4.79412C3.42857 5.07026 3.20471 5.29412 2.92857 5.29412H1.18279C0.740825 5.29412 0.516197 5.8255 0.824153 6.14251L5.64136 11.1014C5.83773 11.3035 6.16227 11.3035 6.35864 11.1014L11.1758 6.14251ZM0.5 13.2353C0.223858 13.2353 0 13.4592 0 13.7353V14.5C0 14.7761 0.223858 15 0.5 15H11.5C11.7761 15 12 14.7761 12 14.5V13.7353C12 13.4592 11.7761 13.2353 11.5 13.2353H0.5Z"/>
                                                </svg>
                                                Download App
                                            </a>
                                        </li>
                                    @endguest

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="logo d-flex align-items-center">
                    <a class="logo-bolt logo-span"
                       href="{{ route('site.home') }}">Forward chess</a>
                    <span class="logo-line logo-span">-</span><span class="logo-text logo-span">Your Partner in Improving Your Play</span>
                </div>

                <div class="d-flex align-items-center">
                    <div class="download-app d-flex align-items-center">
                        <a href="{{ route('site.user-guide') }}" class="d-flex align-items-center">
                            <div class="icon d-flex align-items-center">
                                <svg width="12" height="15" viewBox="0 0 12 15" fill="currentColor"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.1758 6.14251C11.4838 5.8255 11.2592 5.29412 10.8172 5.29412H9.07143C8.79529 5.29412 8.57143 5.07026 8.57143 4.79412V0.5C8.57143 0.223858 8.34757 0 8.07143 0H3.92857C3.65243 0 3.42857 0.223858 3.42857 0.5V4.79412C3.42857 5.07026 3.20471 5.29412 2.92857 5.29412H1.18279C0.740825 5.29412 0.516197 5.8255 0.824153 6.14251L5.64136 11.1014C5.83773 11.3035 6.16227 11.3035 6.35864 11.1014L11.1758 6.14251ZM0.5 13.2353C0.223858 13.2353 0 13.4592 0 13.7353V14.5C0 14.7761 0.223858 15 0.5 15H11.5C11.7761 15 12 14.7761 12 14.5V13.7353C12 13.4592 11.7761 13.2353 11.5 13.2353H0.5Z"/>
                                </svg>
                            </div>
                            <span class="text">
                                Download app
                            </span>
                        </a>

                        <span class="m-l-10 m-r-10 hide-responsive">or</span>

                        <a href="{{ env('WEB_READER_URL') }}?token={{ Session::get('firebase_token')['token']  ?? '' }}" target="_blank" class="d-flex align-items-center hide-responsive">
                            <div class="icon d-flex align-items-center">
                                <svg class="rotate-90" width="12" height="15" viewBox="0 0 12 15" fill="currentColor"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.1758 6.14251C11.4838 5.8255 11.2592 5.29412 10.8172 5.29412H9.07143C8.79529 5.29412 8.57143 5.07026 8.57143 4.79412V0.5C8.57143 0.223858 8.34757 0 8.07143 0H3.92857C3.65243 0 3.42857 0.223858 3.42857 0.5V4.79412C3.42857 5.07026 3.20471 5.29412 2.92857 5.29412H1.18279C0.740825 5.29412 0.516197 5.8255 0.824153 6.14251L5.64136 11.1014C5.83773 11.3035 6.16227 11.3035 6.35864 11.1014L11.1758 6.14251ZM0.5 13.2353C0.223858 13.2353 0 13.4592 0 13.7353V14.5C0 14.7761 0.223858 15 0.5 15H11.5C11.7761 15 12 14.7761 12 14.5V13.7353C12 13.4592 11.7761 13.2353 11.5 13.2353H0.5Z"/>
                                </svg>
                            </div>
                            <span class="text">
                                Go to the web app
                            </span>
                        </a>
                    </div>

                    @guest
                        <a class="sign-up" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                        <a class="sign-in" href="{{ route('login') }}">{{ __('Sign In') }}</a>
                    @else
                        @if (Auth::user()->isAdmin)
                            <li class="admin-menu" role="presentation">
                                <a role="menuitem" tabindex="-1" href="{{ url(env('APP_BACKEND_PATH')) }}">
                                    Backend
                                </a>
                            </li>
                        @endif
                        <li class="dropdown top-hide">
                            <a class="dropdown-toggle"
                               data-toggle="dropdown"
                               href="#">{{ Auth::user()->fullName }}</a>
                            <ul class="dropdown-menu" role="menu">
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ route('site.profile.index') }}">
                                        Profile
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a role="menuitem" tabindex="-1" href="{{ route('site.orders.index') }}">
                                        Order history
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                    <div class="top-cart-row">
                        <div class="top-cart-row-container">
                            {{--<!-- ============================================================= SHOPPING CART DROPDOWN ============================================================= -->--}}
                            <top-cart-holder
                                    icon="{{ asset('images/icon-cart.png') }}"
                                    cart-url="{{ route('site.shoppingCart') }}"
                                    checkout-url="{{ route('site.checkout') }}"
                            ></top-cart-holder>
                        </div>
                    </div>
                    @if(\Route::currentRouteName() === 'site.home' || \Route::currentRouteName() === 'site.products.index')
                        <div class="top-cart-row floating">
                            <div class="top-cart-row-container">
                                <top-cart-holder
                                    icon="{{ asset('images/icon-cart.png') }}"
                                    cart-url="{{ route('site.shoppingCart') }}"
                                    checkout-url="{{ route('site.checkout') }}"
                                    floating-cart="true"
                                ></top-cart-holder>
                            </div>
                        </div>
                    @endif
                    <div class="top-wishlist-row">
                        <div class="top-wishlist-row-container">
                            <top-wishlist-holder
                                    icon="{{asset('images/icon-heart.png')}}"
                                    wishlist-url="{{ route('wishlist.index') }}"
                            >
                            </top-wishlist-holder>
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->
        </div>
    </nav><!-- /.top-bar -->
    <!-- ============================================================= TOP NAVIGATION : END ============================================================= -->
</section>
