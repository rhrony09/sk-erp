@extends('ecommerce.layouts.master')
@section('content')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area mb-30">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('ecommerce.home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- About Us Content Start -->
<div class="about-us-area pb-60">
    <div class="container-fluid">
        <!-- Company Overview Section -->
        <div class="row mb-40">
            <div class="col-lg-6">
                <div class="about-content">
                    <h2 class="mb-20">Welcome to SK Corporation</h2>
                    <p class="mb-15">Founded in 2002, SK Corporation has grown from a small local business to become one of the leading electronics and technology retailers in Bangladesh. For over two decades, we've been dedicated to providing our customers with high-quality products, exceptional service, and innovative solutions.</p>
                    <p>We pride ourselves on our commitment to excellence and our ability to stay ahead of the curve in the ever-evolving world of technology. Our extensive range of products includes everything from the latest smartphones and laptops to home appliances and electronic accessories.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-image text-center">
                    <img src="{{ asset('assets/img-ecom/about/about-us.jpg') }}" alt="About SK Corporation" class="img-fluid rounded">
                </div>
            </div>
        </div>

        <!-- Mission and Vision Section -->
        <div class="row mb-40 bg-light py-4 rounded">
            <div class="col-lg-4">
                <div class="mission-box text-center p-4">
                    <div class="icon mb-3">
                        <i class="fa fa-bullseye fa-3x text-primary"></i>
                    </div>
                    <h3 class="mb-3">Our Mission</h3>
                    <p>To provide our customers with the best technology products and services at competitive prices while ensuring an exceptional shopping experience both online and in-store.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="vision-box text-center p-4">
                    <div class="icon mb-3">
                        <i class="fa fa-eye fa-3x text-primary"></i>
                    </div>
                    <h3 class="mb-3">Our Vision</h3>
                    <p>To be the leading technology retailer in Bangladesh, recognized for our product quality, customer service excellence, and innovative approach to meeting consumer needs.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="values-box text-center p-4">
                    <div class="icon mb-3">
                        <i class="fa fa-heart fa-3x text-primary"></i>
                    </div>
                    <h3 class="mb-3">Our Values</h3>
                    <p>Integrity, customer satisfaction, innovation, teamwork, and continuous improvement are the core values that guide every aspect of our business operations.</p>
                </div>
            </div>
        </div>

        <!-- Why Choose Us Section -->
        <div class="row mb-40">
            <div class="col-12">
                <div class="section-title">
                    <h3>Why Choose Us</h3>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box text-center mb-30">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-certificate fa-4x text-primary"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Quality Products</h4>
                        <p>We offer only the highest quality products from trusted brands and manufacturers.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box text-center mb-30">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-4x text-primary"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Customer Support</h4>
                        <p>Our dedicated support team is available to assist you with any questions or concerns.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box text-center mb-30">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-truck fa-4x text-primary"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Fast Delivery</h4>
                        <p>We ensure prompt delivery of your orders, with options for express shipping.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-box text-center mb-30">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-4x text-primary"></i>
                    </div>
                    <div class="feature-content">
                        <h4>Secure Payments</h4>
                        <p>Your transactions are secure with our multiple payment options and encryption.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Team Section -->
        <div class="row mb-40">
            <div class="col-12">
                <div class="section-title">
                    <h3>Our Leadership Team</h3>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-30">
                <div class="team-member text-center">
                    <div class="team-thumb mb-3">
                        <img src="{{ asset('assets/img-ecom/about/team1.jpg') }}" alt="CEO" class="img-fluid rounded-circle" style="width: 180px; height: 180px; object-fit: cover;">
                    </div>
                    <div class="team-content">
                        <h5>Md. Abdullah Khan</h5>
                        <p class="designation">Chief Executive Officer</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-30">
                <div class="team-member text-center">
                    <div class="team-thumb mb-3">
                        <img src="{{ asset('assets/img-ecom/about/team2.jpg') }}" alt="COO" class="img-fluid rounded-circle" style="width: 180px; height: 180px; object-fit: cover;">
                    </div>
                    <div class="team-content">
                        <h5>Nasrin Akter</h5>
                        <p class="designation">Chief Operations Officer</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-30">
                <div class="team-member text-center">
                    <div class="team-thumb mb-3">
                        <img src="{{ asset('assets/img-ecom/about/team3.jpg') }}" alt="CTO" class="img-fluid rounded-circle" style="width: 180px; height: 180px; object-fit: cover;">
                    </div>
                    <div class="team-content">
                        <h5>Rahat Islam</h5>
                        <p class="designation">Chief Technology Officer</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-30">
                <div class="team-member text-center">
                    <div class="team-thumb mb-3">
                        <img src="{{ asset('assets/img-ecom/about/team4.jpg') }}" alt="CMO" class="img-fluid rounded-circle" style="width: 180px; height: 180px; object-fit: cover;">
                    </div>
                    <div class="team-content">
                        <h5>Ahmed Sharif</h5>
                        <p class="designation">Chief Marketing Officer</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Journey Section -->
        <div class="row mb-40">
            <div class="col-12">
                <div class="section-title">
                    <h3>Our Journey</h3>
                </div>
            </div>
            <div class="col-12">
                <div class="timeline">
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-3">
                            <div class="year-box text-center py-3 bg-primary text-white rounded">
                                <h4>2002</h4>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="milestone-content p-3 border rounded">
                                <h5>Founded in Dhaka</h5>
                                <p>SK Corporation was established with a small electronics shop in Dhaka, focusing on computer accessories and mobile devices.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-3">
                            <div class="year-box text-center py-3 bg-primary text-white rounded">
                                <h4>2010</h4>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="milestone-content p-3 border rounded">
                                <h5>Expansion Phase</h5>
                                <p>Opened three new branches across major cities in Bangladesh and expanded our product range to include home appliances and entertainment systems.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-3">
                            <div class="year-box text-center py-3 bg-primary text-white rounded">
                                <h4>2015</h4>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="milestone-content p-3 border rounded">
                                <h5>Digital Transformation</h5>
                                <p>Launched our e-commerce platform, allowing customers to shop online and offering delivery services throughout Bangladesh.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-3">
                            <div class="year-box text-center py-3 bg-primary text-white rounded">
                                <h4>2023</h4>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <div class="milestone-content p-3 border rounded">
                                <h5>Innovation Hub</h5>
                                <p>Established our innovation hub to showcase the latest technology and provide hands-on experience for customers, making us a leading tech destination in the country.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action Section -->
        <div class="row">
            <div class="col-12">
                <div class="cta-box text-center p-5 bg-light rounded">
                    <h3 class="mb-3">Ready to Experience Our Products?</h3>
                    <p class="mb-4">Explore our wide range of electronics and technology products today.</p>
                    <a href="{{ route('ecommerce.home') }}" class="btn btn-primary">Shop Now</a>
                    <a href="#" class="btn btn-outline-primary ml-3">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About Us Content End -->
@endsection 