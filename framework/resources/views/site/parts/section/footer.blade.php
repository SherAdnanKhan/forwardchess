<!-- ============================================================= FOOTER ============================================================= -->
<footer id="footer" class="footer">
    <div class="container">
        <div class="row d-flex align-items-center">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-footer">
                <div class="copyright d-flex">
                    &copy; <a href="{{ route('site.home') }}">2014 - {{ date('Y') }} Forward Chess</a> - all rights
                    reserved
                </div><!-- /.copyright -->
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-footer">
                <div class="payment-methods d-flex justify-content-end">
                    <ul class="d-flex align-items-center">
                        <li><a href="{{ route('site.terms-of-service') }}">Terms of Service</a></li>
                        <li><a href="{{ route('site.privacy-policy') }}">Privacy Policy</a></li>
                        <li class="payment-icon"><img alt="" src="{{ asset('images/payments/paypal-icon.png') }}"></li>
                    </ul>
                </div><!-- /.payment-methods -->
            </div>
        </div>
    </div><!-- /.container -->
</footer><!-- /#footer -->
<!-- ============================================================= FOOTER : END ============================================================= -->
