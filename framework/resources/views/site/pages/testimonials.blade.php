@extends('layout.app')

@section('content')
    <main class="section-testimonials p-t-105 p-b-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title m-b-40">
                        Testimonials
                    </div>
                </div>
                <div class="col-lg-12">
                    @if (isset($testimonials))
                        <div class="wrapper-testimonial block-lines">
                            @foreach ($testimonials as $post)
                                <div class="testimonial">
                                    <div class="primary-title comment-name">
                                        {{ $post->user }}
                                    </div>
                                    @if ($post->description)
                                        <div class="description">
                                            {!! $post->description !!}
                                        </div>
                                    @endif

                                    @if ($post->video)
                                        <div class="video">
                                            <iframe width="560" height="315" src="{{ $post->video }}" frameborder="0"
                                                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <h2 class="text-center">At the moment we don't have any testimonials from our customer.</h2>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <section class="section-subscribe sub-form-row ">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <subscribe></subscribe>
                </div>
            </div>
        </div>
    </section>
@endsection