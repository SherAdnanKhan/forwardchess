@extends('layout.app')

@section('content')

    <section id="top-banner-download-app" class="section-top-banner-download-app">
        <div class="mouse">
            <img src="{{ asset('images/background-images/mouse-bg/Mouse-gray.png') }}" alt="">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-home m-b-15">
                        our free e-book app
                    </div>
                    <div class="description-white m-b-10">
                        An eBook app that not only enables the reading of text, but provides an interactive chess board
                        display for playing through and analyzing games and variations. You can test out
                        the app by using the free eBook samples.
                    </div>
                    <div class="description-white m-b-60">
                        We offer e-versions of chess books from numerous <a class="link" href="{{ productsPageUrl() }}">leading publishers</a>
                    </div>
                    <div class="wrapper-download d-flex m-b-45">
                        <div class="wrapper-download-mobile">
                            <a href="https://itunes.apple.com/us/app/forwardchess/id543005909?mt=8" target="_blank">
                                <img src="{{ asset('images/download-image/IOS.png') }}" alt="">
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.forwardchess" target="_blank">
                                <img src="{{ asset('images/download-image/Android.png') }}" alt="">
                            </a>
                        </div>
                        <div class="wrapper-download-pc">
                            <a class="d-flex align-items-center justify-content-center h-100" href="https://storage.googleapis.com/fchess-installers/windows/ForwardChess-Latest.exe" target="_blank">
                                <img src="{{ asset('images/download-image/windows-two.svg') }}" alt="">
                                Get it on Windows
                            </a>
                        </div>
                        <div class="wrapper-download-pc">
                            <a class="d-flex align-items-center justify-content-center h-100" href="https://storage.googleapis.com/fchess-installers/mac/ForwardChess-Latest.pkg" target="_blank">
                                <img src="{{ asset('images/download-image/mac.svg') }}" alt="">
                                Get it on MacOS
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-column">
                        <span class="description-white">To Sign In use Email and Password you’ve indicated when you signed up on</span>
                        <a class="description-white" href="{{ route('site.home') }}">Forwardchess.com</a>
                    </div>

                    <div class="d-flex align-items-center m-t-20 wrapper-web-app">
                        <span class="description-white m-r-10">Or you can try our web reader</span>

                        <a href="https://read.forwardchess.com" target="_blank" class="d-flex align-items-center link-web-reader">
                            <svg class="rotate-90" width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 6.70588H11.4286V0H4.57143V6.70588H0L8 14.5294L16 6.70588ZM0 16.7647V19H16V16.7647H0Z"
                                      fill="white"/>
                            </svg>
                            Go to the web app
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="user-guide" class="section-user-guide">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title m-b-40">
                        Forward Chess User Guide
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="wrapper-all-block">
                        <div class="wrapper-block d-flex m-b-20">
                            <div class="col-sm-4 col-lg-4 no-padding">
                                <div class="image d-flex align-items-center0">
                                    <img src="{{ asset('images/user-guide/image.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-sm-8 col-lg-9 no-padding">
                                <div class="content">
                                    <div class="primary-title m-b-12">
                                        Introduction
                                    </div>
                                    <div class="description m-b-12">
                                        We offer books from the following publishers; Chess Stars, Quality Chess,
                                        Mongoose Press and Russell Enterprises. We will be adding more as time goes on.
                                    </div>
                                    <div class="description">
                                        The app is designed for intuitive navigation. There are two main tabs in the
                                        app: My Books and Store.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-block d-flex m-b-20">
                            <div class="col-sm-4 col-lg-4 no-padding">
                                <div class="image d-flex align-items-center">
                                    <img src="{{ asset('images/user-guide/image 2.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-sm-8 col-lg-9 no-padding">
                                <div class="content">
                                    <div class="primary-title m-b-12">
                                        My Books
                                    </div>
                                    <div class="description m-b-12">
                                        To open a book, simply tap the title you want to read. Sliding left and right
                                        provides navigation between adjacent chapters.
                                        To skip to a chapter in a different part of the book, simply tap TOC.
                                    </div>
                                    <div class="description m-b-12">
                                        You will get to choose the chapter from the table of contents.
                                    </div>
                                    <div class="description">
                                        To navigate within a chapter, simply slide up and down. All moves shown in blue
                                        can be tapped – doing so will bring up the big chess board.
                                        Depending on how you are using your device, the board will be either on the left
                                        or on the top; you can use the app in both portrait and landscape mode.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-block d-flex m-b-20">
                            <div class="col-sm-4 col-lg-4 no-padding">
                                <div class="image d-flex align-items-center">
                                    <img src="{{ asset('images/user-guide/image 3.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-sm-8 col-lg-9 no-padding">
                                <div class="content">
                                    <div class="primary-title m-b-12">
                                        Navigation
                                    </div>
                                    <div class="description m-b-12">
                                        The icons below the big board provides you with basic “VCR” navigation features
                                        – next move, previous move,
                                        flip the board. If, for some reason, you don’t need the board you can take it
                                        off by tapping the Board button.
                                        ForwardChess is more than just a book reader. You can make any legal moves on
                                        the board if you wish to explore a variation not shown in the text.
                                    </div>
                                    <div class="description">
                                        In addition to the VCR-like buttons Forward and Backward, you can move through
                                        the game and variations using the large Previous and
                                        Next buttons – the effect is the same, but it is much easier to use the large
                                        buttons, especially for moving through long lines.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-block d-flex m-b-20">
                            <div class="col-sm-4 col-lg-4 no-padding">
                                <div class="image d-flex align-items-center">
                                    <img src="{{ asset('images/user-guide/image 4.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-sm-8 col-lg-9 no-padding">
                                <div class="content">
                                    <div class="primary-title m-b-12">
                                        Settings
                                    </div>
                                    <div class="description m-b-12">
                                        The first button in the top right corner provides the user with a few additional
                                        options.
                                        All three options apply to the text of the book, not the big board. Here you can
                                        change the font size, change the board size (again, this is about
                                        the diagrams in the text, not the big board) and manage the autoscroll.
                                        Autoscroll is the feature that lets you choose whether or not you want the text
                                        to follow the lines you are examining on the big board.
                                    </div>
                                    <div class="description">
                                        To get back to the list of books, simply tap the My Books button in the upper
                                        left side of the screen.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-block d-flex m-b-20">
                            <div class="col-sm-4 col-lg-4 no-padding">
                                <div class="image d-flex align-items-center">
                                    <img src="{{ asset('images/user-guide/image 5.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-sm-8 col-lg-9 no-padding">
                                <div class="content">
                                    <div class="primary-title m-b-12">
                                        Delete Book
                                    </div>
                                    <div class="description">
                                        If your feel that the My Books list is too cluttered you can always temporarily
                                        remove one or more books there.
                                        Simply select the title you want taken out and slide to the left. You can delete
                                        it by pressing the red button Delete on the right.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-block d-flex">
                            <div class="col-sm-4 col-lg-4 no-padding">
                                <div class="image d-flex align-items-center">
                                    <img src="{{ asset('images/user-guide/image 6.jpg') }}" alt="">
                                </div>
                            </div>
                            <div class="col-sm-8 col-lg-9 no-padding">
                                <div class="content">
                                    <div class="primary-title m-b-12">
                                        Store
                                    </div>
                                    <div class="description m-b-12">
                                        The list of available titles shows all books you can get from ForwardChess.
                                        Tapping any of them will bring up the book description.
                                        If you are in landscape mode, the description will appear on the right hand
                                        side. In portrait mode it comes up as a new window.
                                    </div>
                                    <div class="description">
                                        Last but not least, we are sure you know what the BUY button is for!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row comments-and-suggestions">
                <div class="col-lg-12">
                    <div class="description">
                        We are eager to make the ForwardChess experience a great one for all users.
                        This is, and perhaps always will be, a work in progress.
                        Please send your comments and suggestions to <a class="link" href="#">info@forwardchess.com.</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-low-banner-download-app">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title-home d-flex justify-content-center text-center">
                        Download our free app
                    </div>
                    <div class="description d-flex justify-content-center text-center">
                        Advanced book reader which can display chess moves in the text on a board.
                    </div>
                    <div class="wrapper-download d-flex justify-content-center">
                        <div class="wrapper-download-mobile">
                            <a class="m-r-10" href="https://itunes.apple.com/us/app/forwardchess/id543005909?mt=8" target="_blank">
                                <img src="{{ asset('images/download-low-banner/IOS.svg') }}" alt="">
                            </a>
                            <a href="https://play.google.com/store/apps/details?id=com.forwardchess" target="_blank">
                                <img src="{{ asset('images/download-low-banner/Android.svg') }}" alt="">
                            </a>
                        </div>
                        <div class="wrapper-download-pc">
                            <a class="d-flex align-items-center justify-content-center h-100" href="https://storage.googleapis.com/fchess-installers/windows/ForwardChess-Latest.exe" target="_blank">
                                <img src="{{ asset('images/download-image/windows-two.svg') }}" alt="">
                                Get it on Windows
                            </a>
                        </div>
                        <div class="wrapper-download-pc">
                            <a class="d-flex align-items-center justify-content-center h-100" href="https://storage.googleapis.com/fchess-installers/mac/ForwardChess-Latest.pkg" target="_blank">
                                <img src="{{ asset('images/download-image/mac.svg') }}" alt="">
                                Get it on MacOS
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
