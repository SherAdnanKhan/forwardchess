@extends('layout.app')

@section('content')
    <main id="orders" class="inner-bottom-md">
        <div class="container">
            <div class="inner-xs">
                <div class="page-header">
                    <h2 class="page-title">
                        <!-- Orders list -->
                        Order history
                    </h2>
                </div>
            </div>

            <section class="section">
                <div class="col-md-12">
                    @if (isset($orders) && count($orders))
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Book</th>
                                <th>Date</th>
                                <th>Status</th>
{{--                                <th>Total</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orders as $index => $order)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    {{--<th>
                                        <a href="{{ route('site.orders.display', $order->refNo) }}">{{ $order->refNo }}</a>
                                    </th>--}}
                                    <th>
                                        @if(isset($order['productsData']) && isset($order['productsData']['title']))
                                            {{ $order['productsData']['title'] }}
                                        @else
                                            --
                                        @endif
                                    </th>

                                    <td>{{ date('Y-m-d H:i:s', $order['created']/1000) }}</td>
                                    <td>{{ $order['status'] }}</td>
{{--                                    <td>--}}
{{--                                        @if($order['amount'] != 0)--}}
{{--                                        ${{ $order['amount'] }}--}}
{{--                                        @else--}}
{{--                                        ----}}
{{--                                        @endif--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h4>You don't have any orders yet.</h4>
                    @endif
                </div>
            </section>
        </div>
    </main>
@endsection