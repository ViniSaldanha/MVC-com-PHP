<?php

use \App\Http\Response;
use \App\Controller\Pages;

$obRouter->get('/',[
    function(){
        return new Response(200,Pages\home::getHome());
    }
]);

$obRouter->get('/sobre',[
    function(){
        return new Response(200,Pages\about::getAbout());
    }
]);

$obRouter->get('/cadastrar-ba',[
    function($request){
        return new Response(200,Pages\Testimony::getTestimonies($request));
    }
]);

$obRouter->post('/cadastrar-ba',[
    function($request){

        return new Response(200,Pages\Testimony::insertTestimony($request));
    }
]);


