@extends('layout.app')

@section('content')
    <main id="profile">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="title">
                            My profile
                        </span>
                        <a class="primary-description hidden d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg class="m-r-10" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M8.25808 15.2727C8.25808 15.6744 7.91145 16 7.48388 16H0.774195C0.34663 16 0 15.6744 0 15.2727V0.727273C0 0.325627 0.34663 0 0.774195 0H7.48388C7.91145 0 8.25808 0.325627 8.25808 0.727273C8.25808 1.12892 7.91145 1.45455 7.48388 1.45455H1.54839V14.5455H7.48388C7.91145 14.5455 8.25808 14.8711 8.25808 15.2727ZM11.0656 4.09169C11.3679 3.80771 11.8582 3.80775 12.1604 4.09174L15.7732 7.48577C16.0756 7.76979 16.0756 8.2303 15.7732 8.51428L12.1604 11.9081C12.0093 12.0501 11.8112 12.1211 11.613 12.1211C11.4149 12.1211 11.2167 12.0501 11.0656 11.9081C10.7632 11.6241 10.7632 11.1636 11.0656 10.8796L13.3568 8.72727H3.87108C3.44351 8.72727 3.09688 8.40165 3.09688 8C3.09688 7.59835 3.44351 7.27273 3.87108 7.27273H13.3569L11.0656 5.12019C10.7632 4.83617 10.7633 4.37571 11.0656 4.09169Z"
                                      fill="black"/>
                            </svg>
                            {{ __('Logout') }}
                        </a>
                    </div>
                    <div class="wrapper-profile d-flex justify-content-between">
                        <div class="profile">
                            <div class="name primary-title m-b-15">
                                {{ Auth::user()->fullName }}
                            </div>
                            <div class="wrapper-address d-flex align-items-center">
                                <div class="email d-flex align-items-center m-r-25">
                                    <svg class="m-r-10 m-t-1" width="18" height="14" viewBox="0 0 18 14" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.2 3.5L9 7.875L1.8 3.5V1.75L9 6.125L16.2 1.75V3.5ZM16.2 0H1.8C0.801 0 0 0.77875 0 1.75V12.25C0 12.7141 0.189642 13.1592 0.527208 13.4874C0.864773 13.8156 1.32261 14 1.8 14H16.2C16.6774 14 17.1352 13.8156 17.4728 13.4874C17.8104 13.1592 18 12.7141 18 12.25V1.75C18 0.77875 17.19 0 16.2 0Z"
                                              fill="#757575"/>
                                    </svg>
                                    <span class="description">{{Auth::user()->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="edit-profile d-flex">
                            <!-- Modal -->
                            <edit-profile-modal
                                    id="edit-info"
                                    form-action="{{ route('users.update', ['user' => Auth::user()->id]) }}"
                                    profile="{{ $profile }}"
                            ></edit-profile-modal>

                            <edit-mobile-modal
                                    id="edit-mobile"
                                    form-action="{{ route('site.profile.mobile') }}"
                                    profile="{{ $profile }}"
                            ></edit-mobile-modal>

                            <!-- Modal -->
                            <password-modal
                                    id="edit-password"
                                    form-action="{{ route('site.profile.password') }}"
                            ></password-modal>

                            <div class="edit-info d-flex">
                                <button type="button" class="edit-btn d-flex align-items-center" data-toggle="modal"
                                        data-target="#edit-info">
                                    <svg class="m-r-10 m-b-2" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.74 3.59283C16.0867 3.24622 16.0867 2.66852 15.74 2.33967L13.6603 0.259964C13.3315 -0.0866546 12.7538 -0.0866546 12.4072 0.259964L10.7718 1.8864L14.1047 5.21927L15.74 3.59283ZM0 12.6671V16H3.33287L13.1626 6.16137L9.82975 2.8285L0 12.6671Z"
                                              fill="#F96F34"/>
                                    </svg>
                                    Edit info
                                </button>

                                <button
                                        type="button"
                                        class="edit-btn d-flex align-items-center"
                                        data-toggle="modal"
                                        data-target="#edit-mobile"
                                >
                                    <svg class="m-r-10 m-b-2" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.74 3.59283C16.0867 3.24622 16.0867 2.66852 15.74 2.33967L13.6603 0.259964C13.3315 -0.0866546 12.7538 -0.0866546 12.4072 0.259964L10.7718 1.8864L14.1047 5.21927L15.74 3.59283ZM0 12.6671V16H3.33287L13.1626 6.16137L9.82975 2.8285L0 12.6671Z"
                                              fill="#F96F34"/>
                                    </svg>
                                    Edit mobile
                                </button>

                                <button type="button" class="edit-btn d-flex align-items-center" data-toggle="modal"
                                        data-target="#edit-password">
                                    <svg class="m-r-10 m-b-2" width="20" height="18" viewBox="0 0 20 18" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.1572 0C16.0424 0 20 4.05 20 9C20 13.95 16.0424 18 11.1572 18C8.05654 18 5.34452 16.362 3.76325 13.887L5.15901 12.762C6.40459 14.823 8.62191 16.2 11.1661 16.2C13.0404 16.2 14.838 15.4414 16.1633 14.0912C17.4886 12.7409 18.2332 10.9096 18.2332 9C18.2332 7.09044 17.4886 5.25909 16.1633 3.90883C14.838 2.55857 13.0404 1.8 11.1661 1.8C7.56184 1.8 4.59364 4.554 4.16078 8.1H6.59894L3.29505 11.457L0 8.1H2.37633C2.81802 3.555 6.58127 0 11.1572 0ZM13.7721 7.416C14.2138 7.425 14.576 7.785 14.576 8.244V12.393C14.576 12.843 14.2138 13.221 13.7633 13.221H8.87809C8.42756 13.221 8.06537 12.843 8.06537 12.393V8.244C8.06537 7.785 8.42756 7.425 8.86926 7.416V6.507C8.86926 5.13 9.9735 4.014 11.3163 4.014C12.6678 4.014 13.7721 5.13 13.7721 6.507V7.416ZM11.3163 5.274C10.6537 5.274 10.106 5.823 10.106 6.507V7.416H12.5353V6.507C12.5353 5.823 11.9876 5.274 11.3163 5.274Z"
                                              fill="#F96F34"/>
                                    </svg>
                                    Change password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="orders-history">
                        <div class="title m-b-40">
                            Order history
                        </div>
                        @if (isset($orders) && count($orders))
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Book</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($orders as $index => $order)
                                
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <th>
                                            @if(isset($order['productsData']) && isset($order['productsData']['title']))
                                                {{ $order['productsData']['title'] }}
                                            @else
                                                --
                                            @endif
                                        </th>

                                        <td>{{ date('Y-m-d H:i:s', $order['created']/1000) }}</td>
                                        
                                        <td>{{ $order['status'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            There are no orders to display.
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /.container -->
        <div class="wrapper-logout-btn">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <a class="logout d-flex justify-content-center" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
