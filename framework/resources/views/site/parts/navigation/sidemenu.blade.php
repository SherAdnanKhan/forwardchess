<!-- ================================== TOP NAVIGATION ================================== -->
<div class="side-menu animate-dropdown">
    <div class="head"><i class="fa fa-list"></i> Browse books</div>
    <nav class="yamm megamenu-horizontal" role="navigation">
        <ul class="nav">
            @if (isset($groupedPublishers))
                <li class="dropdown menu-item">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Publishers</a>
                    <ul class="dropdown-menu mega-menu">
                        <li class="yamm-content">
                            <div class="row">
                                @foreach ($groupedPublishers as $group)
                                    <div class="col-md-4">
                                        <ul class="list-unstyled">
                                            @foreach ($group as $publisher)
                                                <li>
                                                    <a href="{{ productsPageUrl(null, $publisher->name) }}">{{$publisher->name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </li><!-- /.menu-item -->
            @endif

            @if (isset($categories))
                @foreach ($categories as $category)
                    <li class="menu-item">
                        <a href="{{ productsPageUrl($category->url) }}">{{ $category->name }}</a>
                    </li>
                @endforeach
            @endif
        </ul><!-- /.nav -->
    </nav><!-- /.megamenu-horizontal -->
</div><!-- /.side-menu -->
<!-- ================================== TOP NAVIGATION : END ================================== -->