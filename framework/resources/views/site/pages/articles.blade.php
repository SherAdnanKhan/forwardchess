@extends('layout.app')

@section('content')
  <main id="blog" class="p-t-70 p-b-100">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="title m-b-10">
            Blog
          </div>
        </div>
      </div>
      @if($tags->count())
        <div class="row row-hide">
          <div class="col-lg-12">
            <div class="control-group-menu">
              <label for="exampleFormControlSelect1" class="description m-b-10">Tags</label>
              <select class="control-menu description p-l-15" id="exampleFormControlSelect1" onchange="location = this.value;">
                @foreach ($tags as $tag)
                  <option value="{{ route('site.articles.index') }}" {{ empty($tagId) ? 'selected' : '' }} class="description">
                    All tags
                  </option>
                  <option value="{{ route('site.articles.index', ['tag' => kebab_case($tag->name)]) }}" {{ $tagId === $tag->id ? 'selected' : '' }} class="description">
                    {{ $tag->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="row section-blog-category">
          <div class="col-sm-12 col-md-4 col-lg-3 col-hide">
            <aside class="sidebar-category m-b-30">
              <div class="primary-title">
                Tags
              </div>
              <ul class="category-menu">
                <li class="{{ empty($tagId) ? 'active' : '' }}">
                  <a class="primary-description d-flex" href="{{ route('site.articles.index') }}">All tags</a>
                </li>

                @foreach ($tags as $tag)
                  <li class="{{ $tagId === $tag->id ? 'active' : '' }}">
                    <a class="primary-description d-flex" href="{{ route('site.articles.index', ['tag' => kebab_case($tag->name)]) }}">{{ $tag->name }}</a>
                  </li>
                @endforeach
              </ul>
            </aside>
          </div>
          <div class="col-sm-12 col-md-8 col-lg-9">
            <div class="content">
              @foreach ($articles as $counter => $article)
                <a href="{{ route('site.articles.show', $article->url) }}">
                  <div class="primary-title">
                    {{ $article->title }}
                  </div>
                  <div class="description">
                    {{ $article->publishDate }}
                  </div>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      @else
        <h2>No articles to display</h2>
      @endif
    </div>
  </main>
@endsection
