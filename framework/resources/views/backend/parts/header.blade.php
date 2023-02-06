<header class="header">
    <div class="logo-container">
        <a href="{{ url(env('APP_URL')) }}" class="logo" target="_blank">
            <img src="{{ asset('images/logo.jpg') }}" height="35" alt="Porto Admin"/>
        </a>
        <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right">

        <div id="userbox" class="userbox m-r-40">
            <a href="#" data-toggle="dropdown">
                <div class="profile-info">
                    <user-name></user-name>
                </div>

                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                <ul class="list-unstyled">
                    <li class="divider"></li>
                    <li>
                        <a role="menuitem" tabindex="-1" href="{{ route('site.profile.index') }}" target="_blank"><i class="fa fa-user"></i> My Profile</a>
                    </li>
                    <li>
                        <a role="menuitem" tabindex="-1" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-power-off"></i> Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end: search & user box -->
</header>