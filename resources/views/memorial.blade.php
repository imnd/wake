<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>B-Ouquets</title>
    <link type="text/css" href="{{ mix('css/normalize.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=optional" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link type="image/x-icon" href="{{ url('img/favicon.ico') }}" rel="icon">
    <link type="text/css" href="{{ mix('css/index.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ mix('css/media.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ mix('css/aos.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ mix('css/memorial.css') }}" rel="stylesheet">
</head>
<body>
<script src="{{ mix('js/aos.js') }}"></script>
<div id="mask">
    <video src="{{ url('video/preloader-b-ouqets.mp4') }}" width="250" height="250" autoplay poster loop muted preload="auto"
           id="video"></video>
    <canvas id="canvas"></canvas>
</div>
<div id="main" hidden>
    <!--Header-->
    <header>
        <div class="container">
            <!--Website logo-->
            <a href="#">
                <img src="{{ url('img/logo-full.svg') }}" alt="Logo">
            </a>
            <nav>
                <!--Header menu-->
                <div class="header-menu">
                    <a class="a-link" href="#about">About {{ $memorial['full_name'] }}</a>
                    <a class="a-link" href="#bouquets">Donation</a>
                    <a class="a-Btn" href="#download">Download App</a>
                </div>
            </nav>
        </div>
    </header>
    <!--Information about person section-->
    <section class="person-card-section" id="about">
        <div class="background">
            <img class="background-img" src="{{ url('img/header_block_back.svg') }}" alt="background">
        </div>
        <div class="section-container">
            <!--Person card-->
            <div class="person-card container">
                <!--Person pictures-->
                <div class="person-image">
                    @if($memorial['avatar'] !== "")
                    <img
                        src="{{ $memorial['avatar'] }}"
                        alt="{{ $memorial['full_name'] }}"
                        data-aos="fade-right"
                    />
                    @endif
                </div>
                <!--Person information-->
                <!--div.person-data(data-aos="fade-up")-->
                <div class="person-data">
                    <!--Name / birth and death dates-->
                    <div class="in-memory" data-aos="fade-up" data-aos-duration="1000">
                        <h2 class="title">In Memory of</h2>
                        <h1 class="name">{{ $memorial['full_name'] }}</h1>
                        <time>{{ $memorial['birth_date_short'] }} - {{ $memorial['death_date_short'] }}</time>
                    </div>
                    <!--Birth and death information-->
                    <div class="timeline" data-aos="fade-zoom-in" data-aos-delay="600">
                        <ul>
                            <li><span>{{ $memorial['age'] }} years old</span></li>
                            <li>
                                <span class="birth-death">Born on {{ $memorial['birth_date_full'] }} in {{ $memorial['birth_place'] }}</span>
                            </li>
                            <li>
                                <span class="birth-death">Passed away on {{ $memorial['death_date_full'] }} in {{ $memorial['death_place'] }}</span>
                            </li>
                        </ul>
                        <a class="a-Btn" href="#download">Donate</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Section with notes about the person from the family and photographs-->
    <section class="personal-note-section">
        <img class="background-img" src="{{ url('img/personal_note_back.svg') }}" alt="background">
        <div class="section-container">
            <div class="personal-note-bar">
                <!--Notes-->
                <div class="family-note">
                    <h2 class="note-title" data-aos="fade-up" data-aos-duration="1000">
                        {{ $memorial['title'] }}
                    </h2>
                    <p class="note-text" data-aos="fade-zoom-in" data-aos-delay="600">
                        {{ $memorial['text'] }}
                    </p>
                </div>
                <!--Pictures-->
                <div class="pictures">
                    <div class="pictures-content @if($horizontal) horizontal @else vertical @endif images-{{ $imagesCount }}">
                        @if(count($memorial['images']))
                            @foreach($memorial['images'] as $image)
                                <img
                                    class="picture"
                                    src="{{ url($image['path']) }}"
                                    alt="{{ $memorial['full_name'] }}"
                                    data-aos="zoom-in"
                                    data-aos-delay="400"
                                />
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Flowers section-->
    <section class="flowers-section" id="bouquets">
        <div class="background"></div>
        <div class="section-container">
            <div class="flowers-bar container">
                <div>
                    <div class="flowers-note">
                        <!--Logo-->
                        <a href="#" data-aos="fade-up" data-aos-duration="1000">
                            <svg width="49" height="47" viewBox="0 0 49 47" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M36.1237 1.43035L29.9998 7.62705L28.2614 0.196777H20.9216L19.1832 7.62384L13.0592 1.43035C11.324 3.85904 10.2949 6.84205 10.2949 10.0686C10.2949 18.1204 16.669 24.6632 24.5915 24.8202C32.514 24.6632 38.8881 18.1204 38.8881 10.0686C38.8881 6.84205 37.8621 3.85904 36.1237 1.43035Z" fill="black" />
                                <path d="M13.4453 44.1076C16.67 45.8807 20.2245 46.8197 23.7982 46.9477H25.3818C30.4931 46.8003 35.5507 45.0077 39.6305 41.6593C45.4166 36.9141 48.4227 30.5355 48.5646 22.7018L48.5953 21.0412L46.9618 21.1964C35.7884 22.2634 28.3496 27.6372 24.7798 37.1935C20.6425 25.8058 9.81796 21.0645 2.39072 21.4486L1.07552 21.5146L0.998828 22.8454C0.619221 29.5771 4.3501 39.1024 13.4376 44.1037L13.4453 44.1076Z" fill="black" />
                            </svg>
                            <span>B-Ouquets</span>
                        </a>
                        <!--Description-->
                        <p class="flowers-text" data-aos="fade-zoom-in" data-aos-delay="600">B-Ouquets are forever
                        owners with a customized card you can send to the family. Unlike traditional owners, B-Ouquets
                        are memories that stay forever in digital form and help withnal expenses. Proceeds go to the
                        family not the orist.</p>
                    </div>
                </div>
                <!--List of products-->
                <div class="flowers-list" data-aos="fade-zoom-in">
                    <div class="flowers-card">
                        <ul class="flowers-ul">
                            @foreach($memorial['bouquets'] as $bouquet)
                                <li class="flower-li">
                                    <div class="flower-line">
                                        <img class="flower-image" src="{{ $bouquet['type']['image'] }}" alt="{{ $bouquet['type']['name'] }}" />
                                        <div class="flower-name-date">
                                            <span class="flower-nameText">{{ $bouquet['from'] }}</span>
                                            <span class="flower-date">{{ $bouquet['created'] }}</span>
                                        </div>
                                        <img class="flower-type" src="{{ url("img/{$bouquet['payment_method']}.png") }}" alt="usd" />
                                        <span class="flower-price">${{ $bouquet['type']['price'] }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div>
                            <a class="a-Btn" href="#download">Donate</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--About app section-->
    <section class="download-app-section" id="download">
        <div class="background">
            <img class="background-img" src="{{ url('img/downloadapp_back.jpg') }}" alt="background">
        </div>
        <div class="section-container">
            <div class="download-app-bar container">
                <!--Empty div-->
                <div></div>
                <div class="download-app-info">
                    <!--Title-->
                    <h2 class="download-app-info-title" data-aos="fade-up" data-aos-duration="1000">Download App</h2>
                    <!--Description-->
                    <p class="download-app-info-text" data-aos="fade-in" data-aos-delay="600"
                                         data-aos-duration="1000">Lorem Ipsum is simply dummy text of the printing and
                        typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the
                        1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen
                        book. It has survived not only five centuries, but also the leap into e</p>
                    <!--Apps stores buttons-->
                    <div class="download-app-info-buttons"
                         data-aos="fade-in" data-aos-delay="600"
                         data-aos-duration="1000"
                         data-aos-anchor-placement="top-bottom">
                        <a class="a-Btn" href="https://play.google.com/">
                            <img src="{{ url('img/googleplay.svg') }}" alt="Google Play">
                        </a>
                        <a class="a-Btn" href="https://www.apple.com/app-store/">
                            <img src="{{ url('img/appstore.svg') }}" alt="appStore">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Footer-->
    <footer>
        <div class="footer-bar container">
            <!--Logo-->
            <a href="#"><img src="{{ url('img/logo-full.svg') }}" alt="Logo"></a>
            <!--Footer nav-->
            <div class="footer-links">
                <a class="a-link" href="#">About {{ $memorial['full_name'] }}</a>
                <a class="a-link" href="#bouquets">Donation</a>
                <a class="a-link" href="#download">Download App</a>
            </div>
        </div>
    </footer>
</div>
<script src="{{ mix('js/preloader.js') }}"></script>
</body>

</html>
