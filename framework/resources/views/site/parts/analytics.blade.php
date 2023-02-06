@if (isset($analytics) && !empty($analytics))
    <script>
        function addProducts(actionName, products) {
            for (var i = 0; i < products.length; i++) {
                ga(actionName, products[i]);
            }
        }

        function productPurchased(products) {
            for (var i = 0; i < products.length; i++) {
                ga('send', 'event', 'Purchase', products[i].purchasedSection, products[i].name);
            }
        }

        function handleAnalytics(analytics) {
            switch (analytics.event) {
                case 'VIEW_PRODUCTS':
                    addProducts('ec:addImpression', analytics.data);
                    break;
                case 'VIEW_CHECKOUT':
                    addProducts('ec:addProduct', analytics.data);

                    ga('ec:setAction', 'checkout', {
                        'step'  : 1,
                        'option': 'Paypal'
                    });

                    break;
                case 'PURCHASE':
                    addProducts('ec:addProduct', analytics.data.products);
                    ga('ec:setAction', 'purchase', analytics.data.purchase);

                    const orderPlaced = "{{ session()->get('orderPlaced') }}"

                    if (orderPlaced) {
                        productPurchased(analytics.data.products)

                        @php
                            session()->forget('orderPlaced');
                        @endphp
                    }

                    break;
            }
        }

        handleAnalytics({!! $analytics !!})
    </script>
@endif
