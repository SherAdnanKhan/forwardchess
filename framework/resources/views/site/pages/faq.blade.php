@extends('layout.app')

@section('content')
    <main id="faq" class="p-t-70 p-b-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title m-b-10">
                        Frequently Asked Questions
                    </div>
                    <div class="primary-description m-b-40">
                        We try to answer common questions with our FAQs, so please take a moment to browse these helpful
                        articles.
                        If none of this applies to your case, reach out with this <a class="link"
                                                                                     href="{{ route('site.contact.show') }}">Contact
                            form</a>
                    </div>
                </div>
            </div>
            <div class="row row-hide">
                <div class="col-lg-12">
                    <div class="control-group-menu">
                        <label for="exampleFormControlSelect1"
                               class="description m-b-10">Categories</label>
                        <select class="control-menu description p-l-15" id="exampleFormControlSelect1"
                                onchange="location = this.value;">
                            @foreach ($faqCategories as $category)
                                <option value="{{ route('site.faq.index', ['category' => kebab_case($category->name)]) }}"
                                        {{ ($faqCategoryId === $category->id) ? 'selected': '' }} class="description">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row section-faq-category">
                <div class="col-sm-12 col-md-4 col-lg-3 col-hide">
                    <aside class="sidebar-category m-b-30">
                        <div class="primary-title">
                            Categories
                        </div>
                        <ul class="category-menu">
                            @foreach ($faqCategories as $category)
                                <li class="{{ ($faqCategoryId === $category->id) ? 'active': ''}}">
                                    <a class="primary-description d-flex"
                                       href="{{ route('site.faq.index', ['category' => kebab_case($category->name)]) }}">{{ $category->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
                <div class="col-sm-12 col-md-8 col-lg-9">
                    <div class="content">
                        @foreach ($faqPosts as $counter => $post)
                            <div class="primary-title">
                                {{ $post->question }}
                            </div>
                            <div class="description">
                                {!! $post->answer !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection