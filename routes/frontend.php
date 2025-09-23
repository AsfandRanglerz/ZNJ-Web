<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;

Route::controller(HomeController::class)->group(function () {
    Route::get('/event', 'event')->name('web.event');
    Route::get('/ticket', 'ticket')->name('web.ticket');
    Route::get('/eventdetail', 'eventDetail')->name('web.eventdetail');
    Route::get('/venue', 'venue')->name('web.venue');
    Route::get('/generate', 'generateTicket')->name('web.generateticket');
    Route::get('/signup', 'signup')->name('web.signup');
    Route::get('/setpassword', 'setPassword')->name('web.setpassword');
    Route::get('/otp', 'otp')->name('web.otp');
    Route::get('/forgotpassword', 'forgotPassword')->name('web.forgotpassword');
    Route::get('/login', 'login')->name('web.login');
    Route::get('/thankyou', 'thankyou')->name('web.thankyou');

    // recruiter routes
    // Route::get('/dashboard', 'dashboard')->name('web.recruiter.dashboard');
    Route::get('/createevent', 'createEvent')->name('web.recruiter.createevent');
    Route::get('/myprofile', 'myProfile')->name('web.recruiter.myprofile');
    Route::get('/createmyevent', 'createMyEvent')->name('web.recruiter.createmyevent');
    Route::get('/joinmyevent', 'joinMyEvent')->name('web.recruiter.joinmyevent');
    // Route::get('/myticket', 'myTicket')->name('web.recruiter.myticket');
});