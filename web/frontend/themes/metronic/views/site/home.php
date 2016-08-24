<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 30/03/2015
 * Time: 8:08
 */

use yii\helpers\Url;

?>
<!-- BEGIN SLIDER -->
<div class="page-slider margin-bottom-40">
<div class="fullwidthbanner-container revolution-slider">
<div class="fullwidthabnner">
<ul id="revolutionul">
<!-- THE NEW SLIDE -->
<li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="themes/metronic/assets/frontend/pages/img/revolutionslider/thumbs/thumb2.jpg">
    <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
    <img src="themes/metronic/assets/frontend/pages/img/revolutionslider/bg9.jpg" alt="">

    <div class="caption lft slide_title_white slide_item_left helvetic"
         data-x="30"
         data-y="90"
         data-speed="400"
         data-start="1500"
         data-easing="easeOutExpo" style="text-align: left;">
        <span style="color: #B2C6D3;font-size: 65px;">BEAUTY DATE</span><br>
        <span style="font-size: 45px;">LA MANERA MÁS FÁCIL DE RESERVAR</span><br>
        <span style="font-size: 45px;">EN SALONES DE BELLEZA</span>
    </div>
    <div class="caption lft btn dark slide_item_left"
       data-x="187"
       data-y="315"
       data-speed="400"
       data-start="3000"
       data-easing="easeOutExpo">
        <a href="#"><img src="themes/metronic/assets/frontend/pages/img/revolutionslider/google_play.png" alt=""></a>
        <a href="#"><img src="themes/metronic/assets/frontend/pages/img/revolutionslider/app_store.png" alt=""></a>
    </div>

    <div class="caption lfb"
         data-x="640"
         data-y="0"
         data-speed="700"
         data-start="1000"
         data-easing="easeOutExpo">
        <img src="themes/metronic/assets/frontend/pages/img/revolutionslider/lady.png" alt="Image 1">
    </div>
</li>

<!-- THE FIRST SLIDE -->
<li data-transition="fade" data-slotamount="8" data-masterspeed="700" data-delay="9400" data-thumb="themes/metronic/assets/frontend/pages/img/revolutionslider/thumbs/thumb2.jpg">
    <!-- THE MAIN IMAGE IN THE FIRST SLIDE -->
    <img src="themes/metronic/assets/frontend/pages/img/revolutionslider/bg1.png" alt="">

    <div class="caption lft slide_title slide_item_left helvetic"
         data-x="30"
         data-y="105"
         data-speed="400"
         data-start="1500"
         data-easing="easeOutExpo">
        <span style="color: #B2C6D3;font-size: 65px;">BEAUTY DATE</span><br>
        <span style="font-size: 45px;color: white">A DATE WITH YOURSELF</span><br>
    </div>
    <a class="caption lft btn slide_item_left" href="<?=Url::toRoute('site/signup')?>"
       data-x="30"
       data-y="290"
       data-speed="400"
       data-start="3000"
       data-easing="easeOutExpo">
        <img src="themes/metronic/assets/frontend/pages/img/revolutionslider/download.png" alt="">
    </a>
</li>

<!-- THE SECOND SLIDE -->
<li data-transition="fade" data-slotamount="7" data-masterspeed="300" data-delay="9400" data-thumb="themes/metronic/assets/frontend/pages/img/revolutionslider/thumbs/thumb2.jpg">
    <img src="themes/metronic/assets/frontend/pages/img/revolutionslider/bg2.png" alt="">
    <div class="caption lfl slide_title slide_item_left helvetic"
         data-x="30"
         data-y="125"
         data-speed="400"
         data-start="3500"
         data-easing="easeOutExpo">
        <span style="color: #B2C6D3;font-size: 60px;">REGISTRA TU SALÓN</span><br>
        <div style="font-size: 25px;color: white;margin-top: -15px;">
            <span><img src="themes/metronic/assets/frontend/pages/img/revolutionslider/dot.png" alt=""></span> ENCUENTRA MÁS CLIENTES</div>
        <div style="font-size: 25px;color: white;margin-top: -15px;">
            <span><img src="themes/metronic/assets/frontend/pages/img/revolutionslider/dot.png" alt=""></span> PROMUEVE TU SALÓN DE FORMA MÁS EFECTIVA</div>
        <div style="font-size: 25px;color: white;margin-top: -15px;">
            <span><img src="themes/metronic/assets/frontend/pages/img/revolutionslider/dot.png" alt=""></span> ADMINISTRA TUS RESERVACIONES FÁCILMENTE</div>

    </div>

    <div class="caption lfr slide_item_right"
         data-x="635"
         data-y="105"
         data-speed="1200"
         data-start="1500"
         data-easing="easeOutBack" style="top: 20px !important;">
        <img src="themes/metronic/assets/frontend/pages/img/revolutionslider/mac_book.png" alt="Image 1">
    </div>

</li>

</ul>
<div class="tp-bannertimer tp-bottom"></div>
</div>
</div>
</div>
<!-- END SLIDER -->

<div class="main">
<div class="container">
<!-- BEGIN SERVICE BOX -->
<div class="row service-box margin-bottom-40">
    <div class="col-md-4 col-sm-4">
        <div class="service-box-heading">
            <em><i class="fa fa-location-arrow blue"></i></em>
            <span>Incrementa tus clientes</span>
        </div>
        <p>BeautyDate incrementa tus ganancias ayudándote a atender más clientes, aumentando reservaciones y convirtiendo clientes ocasionales en frecuentes.</p>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="service-box-heading">
            <em><i class="fa fa-check red"></i></em>
            <span>Sencillo</span>
        </div>
        <p>BeautyDate está presente a la hora que los clientes están tomando la decisión de donde hacer una cita. Los clientes reservan de una forma sencilla por medio de nuestra aplicación móvil para iPhone y Android.</p>
    </div>
    <div class="col-md-4 col-sm-4">
        <div class="service-box-heading">
            <em><i class="fa fa-compress green"></i></em>
            <span>Eficiente</span>
        </div>
        <p>BeautyDate le permite a tus clientes hacer reservaciones las 24 horas. Cada reservación realizada en la aplicación es instantáneamente visible en tu calendario.</p>
    </div>
</div>
<!-- END SERVICE BOX -->

<!-- BEGIN BLOCKQUOTE BLOCK -->
<div class="row quote-v1 margin-bottom-30">
    <div class="col-md-9">
        <span>BeautyDate - La más completa &amp; popular aplicación de gestión de salones.</span>
    </div>
    <div class="col-md-3 text-right">
        <a class="btn-transparent" href="<?=Url::toRoute('site/signup')?>" target="_blank"><i class="fa fa-rocket margin-right-10"></i>Registrarse</a>
    </div>
</div>
<!-- END BLOCKQUOTE BLOCK -->

<!-- BEGIN TABS AND TESTIMONIALS -->
<div class="row mix-block margin-bottom-40">
    <!-- TABS -->
    <div class="col-md-7 tab-style-1">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-1" data-toggle="tab">Calendario</a></li>
            <li><a href="#tab-2" data-toggle="tab">Clientes</a></li>
            <li><a href="#tab-3" data-toggle="tab">Reportes</a></li>
            <li><a href="#tab-4" data-toggle="tab">Publicidad</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane row fade in active" id="tab-1">
                <div class="col-md-9 col-sm-9">
                    <p class="margin-bottom-10">Utiliza el calendario para ver y gestionar tus citas, ya sea hechas por medio de la aplicación o registradas manualmente desde tu página.</p>
                    <p><a class="more" href="#">Leer más <i class="icon-angle-right"></i></a></p>
                </div>
            </div>
            <div class="tab-pane row fade" id="tab-2">
                <div class="col-md-9 col-sm-9">
                    <p>Visualiza información de todos tus clientes y su historial de citas.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-3">
                <p>BeautyDate te permite consultar tus ingresos, calificaciones y estadísticas que te ayudarán a administrar de mejor manera tu salón.</p>
            </div>
            <div class="tab-pane fade" id="tab-4">
                <p>Publica tus servicios, fotos, precios y pormociones, los cuales serán vistos por tu mercado.</p>
            </div>
        </div>
    </div>
    <!-- END TABS -->

    <!-- TESTIMONIALS -->
    <div class="col-md-5 testimonials-v1">
        <!--
        <div id="myCarousel" class="carousel slide">
            <!-- Carousel items -->
        <!--<div class="carousel-inner">
            <div class="active item">
                <iv class="carousel-info">
                    <img class="pull-left" src="themes/metronic/assets/frontend/pages/img/people/img1-small.jpg" alt="">
                    <div class="pull-left">
                        <span class="testimonials-name">Lina Mars</span>
                        <span class="testimonials-post">Commercial Salón</span>
                    </div>
                </div>
            </div>
            <div class="item">
                <blockquote><p>Raw denim you Mustache cliche tempor, williamsburg carles vegan helvetica probably haven't heard of them jean shorts austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica.</p></blockquote>
                <div class="carousel-info">
                    <img class="pull-left" src="themes/metronic/assets/frontend/pages/img/people/img5-small.jpg" alt="">
                    <div class="pull-left">
                        <span class="testimonials-name">Kate Ford</span>
                        <span class="testimonials-post">Peluquera</span>
                    </div>
                </div>
            </div>
            <div class="item">
                <blockquote><p>Reprehenderit butcher stache cliche tempor, williamsburg carles vegan helvetica.retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid Aliquip placeat salvia cillum iphone.</p></blockquote>
                <div class="carousel-info">
                    <img class="pull-left" src="themes/metronic/assets/frontend/pages/img/people/img2-small.jpg" alt="">
                    <div class="pull-left">
                        <span class="testimonials-name">Jake Witson</span>
                        <span class="testimonials-post">Gerente</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel nav -->
        <!--<a class="left-btn" href="#myCarousel" data-slide="prev"></a>
        <a class="right-btn" href="#myCarousel" data-slide="next"></a>
    </div>-->
    </div>
    <!-- END TESTIMONIALS -->
</div>
<!-- END TABS AND TESTIMONIALS -->

<!-- BEGIN STEPS -->
<div class="row margin-bottom-40 front-steps-wrapper front-steps-count-3">
    <div class="col-md-4 col-sm-4 front-step-col">
        <div class="front-step front-step1">
            <h2>Registrarse</h2>
            <p>Haz tu cuenta y tu perfil.</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 front-step-col">
        <div class="front-step front-step2">
            <h2>Configurar</h2>
            <p>Ingresa los servicios que ofrece tu salón.</p>
        </div>
    </div>
    <div class="col-md-4 col-sm-4 front-step-col">
        <div class="front-step front-step3">
            <h2>Adquirir Licencia</h2>
            <p>Escoge tu plan.</p>
        </div>
    </div>
</div>
<!-- END STEPS -->

</div>
</div