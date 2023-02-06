<section id="gaming">
    <div class="grid-list-products">
        @if (count($products))
            <div class="control-bar d-flex justify-content-between align-items-center">
                @if (isset($sortOptions))
                    <div class="books-found">
                        {{ $products->total() }} books found
                    </div>
                    <div class="quick-filters-container d-flex">
                        <div class="form-sort d-flex listing-filter">
                            <label class="form-label">Per Page</label>
                            <div id="sortBy" class="le-select">
                                <select data-placeholder="sort by popularity" onchange="this.form.submit();" name="perPage">
                                    @foreach ($rowsPerPageOptions as $value)
                                        <option value="{{ $value }}" {{ ($value == $rowsPerPage) ? 'selected="selected"' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                <svg width="8" height="5" viewBox="0 0 8 5" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 5L7.4641 0.5H0.535898L4 5Z" fill="#333333"/>
                                </svg>
                            </div>
                        </div>
                        <!-- added search filter -->
                        @if (isset($headerSearch) && $headerSearch)
                            <input type="hidden" name="search" value="{{ $headerSearch }}">
                        @endif
                        <div class="form-sort d-flex listing-filter">
                            <label class="form-label">Sort by</label>
                            <div id="sortBy" class="le-select">
                                <select data-placeholder="sort by popularity" onchange="this.form.submit();" name="sortBy">
                                    @foreach ($sortOptions as $value => $option)
                                        <option value="{{ $value }}" {{ ($value == $sortBy) ? 'selected="selected"' : '' }}>{{ $option['label'] }}</option>
                                    @endforeach
                                </select>
                                <svg width="8" height="5" viewBox="0 0 8 5" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 5L7.4641 0.5H0.535898L4 5Z" fill="#333333"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="wrapper-form-sort d-flex align-items-center">
                    <div class="grid-list-buttons d-flex align-items-center">
                        <div class="grid-list-text">
                            View
                        </div>
                        <ul class="wrapper-grid-list-button d-flex justify-content-around align-items-center">
                            <li class="grid-list-button-item active">
                                <a data-toggle="tab"
                                   href="#list-view"
                                   class="d-flex align-items-center">
                                    <svg width="16" height="16" viewBox="0 0 18 16" fill="#757432"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0.5H18V3.5H6V0.5Z" fill="#757575"/>
                                        <path d="M6 6.5H18V9.5H6V6.5Z" fill="#757575"/>
                                        <path d="M3 12.5H0V15.5H3V12.5Z" fill="#757575"/>
                                        <path d="M18 12.5H6V15.5H18V12.5Z" fill="#757575"/>
                                        <path d="M3 6.5H0V9.5H3V6.5Z" fill="#757575"/>
                                        <path d="M3 0.5H0V3.5H3V0.5Z" fill="#757575"/>
                                    </svg>
                                </a>
                            </li>
                            <li class="grid-list-button-item">
                                <a data-toggle="tab"
                                   href="#grid-view"
                                   class="d-flex align-items-center">
                                    <svg width="16" height="16" viewBox="0 0 18 18" fill="#757575"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 0H10V8H18V0Z" fill="#757575"/>
                                        <path d="M18 10H10V18H18V10Z" fill="#757575"/>
                                        <path d="M0 10H8V18H0V10Z" fill="#757575"/>
                                        <path d="M8 0H0V8H8V0Z" fill="#757575"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><!-- /.control-bar -->

            <div class="tab-content">
                <div id="list-view" class="products-grid fade tab-pane in active">
                    <div class="products-list">
                        @foreach ($products as $product)
                            @component('site.components.product-list-item', ['product' => $product, 'section' => $section])
                            @endcomponent
                        @endforeach

                    </div><!-- /.products-list -->

                    {{--<div class="view-pagination d-flex align-items-center justify-content-center">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.79414 3.79302C8.86414 -1.27549 17.0991 -1.26268 22.1849 3.82161L25.2453 0.762153L25.2576 8.70872L25.3295 8.92332L17.0843 8.92072L20.1447 5.86126C16.1845 1.90226 9.78861 1.89231 5.84074 5.83902C1.89287 9.78572 1.90282 16.1797 5.86298 20.1387C9.82315 24.0977 16.2191 24.1077 20.1669 20.161C22.1358 18.1927 23.1126 15.5999 23.0983 13.0157L26 13.0202C26.0052 16.35 24.7536 19.6676 22.2135 22.207C17.1435 27.2755 8.90854 27.2627 3.82274 22.1784C-1.26306 17.0941 -1.27586 8.86153 3.79414 3.79302Z"
                                  fill="#F96F34"/>
                        </svg>
                        <span>View more 12 E-books</span>
                    </div>--}}

                    {{ $products->links() }}
                </div><!-- /.products-grid #list-view -->

                <div id="grid-view" class="products-grid fade tab-pane">
                    <div class="product-grid-holder">
                        <div class="row d-flex flex-wrap justify-content-center">
                            @foreach ($products as $product)
                                {{--                                no-margin product-item-holder hover--}}
                                <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 d-flex justify-content-center p-r-10 p-l-10">
                                    @component('site.components.product-grid-item', ['product' => $product])
                                    @endcomponent
                                </div>
                            @endforeach
                        </div><!-- /.row -->
                    </div><!-- /.product-grid-holder -->

                    {{ $products->links() }}
                </div><!-- /.products-grid #grid-view -->
            </div><!-- /.tab-content -->
        @else
            <h2 class="text-center">No products found!</h2>
        @endif
    </div><!-- /.grid-list-products -->

</section><!-- /#gaming -->
