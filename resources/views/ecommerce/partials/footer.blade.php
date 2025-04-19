@php
    $setting = \App\Models\Utility::settings();
@endphp

<!-- scroll to top -->
<div class="scroll-top not-visible">
    <i class="fa fa-angle-up"></i>
</div> <!-- /End Scroll to Top -->

<!-- footer area start -->  
<footer>
    <!-- news-letter area start -->
    <div class="newsletter-group">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="newsletter-box">
                        <div class="newsletter-inner">
                            <div class="newsletter-title">
                                <h3>Sign Up For Newsletters</h3>
                                <p>Be the First to Know. Sign up for newsletter today</p>
                            </div>
                            <div class="newsletter-box">
                                <form id="mc-form">
                                    <input type="email" id="mc-email" autocomplete="off" class="email-box" placeholder="enter your email">
                                    <button class="newsletter-btn" type="submit" id="mc-submit">subscribe !</button>
                                </form>
                            </div>
                        </div>
                        <div class="link-follow">
                            <a href="https://www.facebook.com/"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="https://plus.google.com/discover"><i class="fa-brands fa-google-plus-g"></i></a>
                            <a href="https://twitter.com/"><i class="fa-brands fa-x-twitter"></i></a>
                            <a href="https://www.youtube.com/"><i class="fa-brands fa-youtube"></i></a>
                        </div>
                    </div>
                    <!-- mailchimp-alerts Start -->
                    <div class="mailchimp-alerts">
                        <div class="mailchimp-submitting"></div><!-- mailchimp-submitting end -->
                        <div class="mailchimp-success"></div><!-- mailchimp-success end -->
                        <div class="mailchimp-error"></div><!-- mailchimp-error end -->
                    </div><!-- mailchimp-alerts end -->
                </div>
            </div>
        </div>
    </div>
    <!-- news-letter area end -->
    <!-- footer top area start -->
    <div class="footer-top pt-50 pb-50">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="footer-single-widget">
                        <div class="widget-title">
                            <div class="footer-logo mb-30">
                                <a href="index.html">
                                     <img src="assets/img/logo/logo-sinrato.png" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <p>We are a team of designers and developers that create high quality Magento, Prestashop, Opencart.</p>
                            <div class="payment-method">
                                <h4>payment</h4>
                                <img src="{{asset('assets/img-ecom/payment/payment.png')}}" alt="">
                            </div>
                        </div>
                    </div>
                </div> <!-- single widget end -->
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="footer-single-widget">
                        <div class="widget-title">
                            <h4>Information</h4>
                        </div>
                        <div class="widget-body">
                            <div class="footer-useful-link">
                                <ul>
                                    <li><a href="about.html">about us</a></li>
                                    <li><a href="#">Delivery Information</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Terms & Conditions</a></li>
                                    <li><a href="#">Contact Us</a></li>
                                    <li><a href="#">Returns</a></li>
                                    <li><a href="#">Site Map</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- single widget end -->
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="footer-single-widget">
                        <div class="widget-title">
                            <h4>contact us</h4>
                        </div>
                        <div class="widget-body">
                            <div class="footer-useful-link">
                                <ul>
                                    <li><span>Address:</span> {{$setting['company_address']}}</li>
                                    <li><span>email:</span> info@skcorporationbd.com</li>
                                    <li><span>Call us:</span> <strong>{{$setting['company_telephone']}}</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer top area end -->
    <!-- footer bottom area start -->
    <div class="footer-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="footer-bottom-content">
                        <div class="footer-copyright">
                            <p><a href="/"><b>{{$setting['company_name'] ?? 'Sk Corporation'}}</b></a> &copy; 2002-{{Date('Y')}} | All Rights Reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer bottom area end -->
</footer>