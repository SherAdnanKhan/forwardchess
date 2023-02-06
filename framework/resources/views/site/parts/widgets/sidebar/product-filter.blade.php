<!-- ========================================= PRODUCT FILTER ========================================= -->
<div class="widget product-filter">
    <div class="wrap-filters">
        <div class="category-filter">
            <div class="wrapper-clear d-flex justify-content-between align-items-center">
                <div class="filters-text">
                    Filters
                </div>
                <a href="{{ productsPageUrl() }}" class="clear-widget">Clear all</a>
            </div>

            <div id="faqOne" role="tablist" aria-multiselectable="true">
                @if (isset($categories))
                    <div class="accordion accordion-default">
                        <div class="accordion-heading" role="tab" id="questionTwo">
                            <h5 class="accordion-title">
                                <a class="collapsed d-flex align-items-center justify-content-between" data-toggle="collapse" data-parent="#faq" href="#answerTwo" aria-expanded="false"
                                   aria-controls="answerTwo">
                                    Categories
                                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 4.37114e-07L0.669873 5.25L9.33013 5.25L5 4.37114e-07Z"
                                              fill="#757575"/>
                                    </svg>
                                </a>
                            </h5>
                        </div>
                        <div id="answerTwo" class="accordion-collapse collapse in" role="tabaccordion" aria-labelledby="questionTwo">
                            <div class="accordion-body">
                                <ul>
                                    @foreach ($categories as $category)
                                        <li>
                                            @if (isset($filters['categoryId']) && ($filters['categoryId'] === $category->id))
                                                {{ $category->name }}
                                            @else
                                                <a href="{{ productsPageUrl($category->url) }}">
                                                    {{ $category->name }}
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($filters['publishers']))
                    <div class="accordion accordion-default">
                        <div class="accordion-heading" role="tab" id="questionOne">
                            <h5 class="accordion-title">
                                <a data-toggle="collapse" class="d-flex align-items-center justify-content-between" data-parent="#faq" href="#answerOne" aria-expanded="false"
                                   aria-controls="answerOne">
                                    Publishers
                                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 4.37114e-07L0.669873 5.25L9.33013 5.25L5 4.37114e-07Z"
                                              fill="#757575"/>
                                    </svg>
                                </a>
                            </h5>
                        </div>
                        <div id="answerOne" class="accordion-collapse collapse in"
                             role="tabaccordion"
                             aria-labelledby="questionOne">
                            <div class="accordion-body">
                                <ul>
                                    @foreach ($filters['publishers'] as $publisher)
                                        <li>
                                            <input
                                                    name="publishers[]"
                                                    value="{{ $publisher['value'] }}"
                                                    class="le-checkbox"
                                                    type="checkbox" {{ $publisher['checked'] ? 'checked="checked"' : '' }}
                                                    onchange="this.form.submit();"
                                            />
                                            <label>{{ str_limit($publisher['label'], 23) }}</label>
                                            @if (isset($publisher['products']))
                                                <span class="pull-right">{{ $publisher['products'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div><!-- /.category-filter -->

        @if (isset($filters['price']))
            <price-filter
                    form-selector="#products-form"
                    value="{{ $filters['price']['value'] }}"
                    label="{{ $filters['price']['label'] }}"
            />
        @endif
    </div><!-- /.body -->
</div><!-- /.widget -->
<!-- ========================================= PRODUCT FILTER : END ========================================= -->