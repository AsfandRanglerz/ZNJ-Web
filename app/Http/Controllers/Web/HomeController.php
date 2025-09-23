<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function event() {
        return view('web.event');
    }

    public function ticket() {
        return view('web.ticket');
    }

    public function eventDetail() {
        return view('web.eventdetail');
    }

    public function venue() {
        return view('web.venue');
    }

    public function generateTicket() {
        return view('web.generateticket');
    }

    public function signup() {
        return view('web.signup');
    }

    public function setPassword() {
        return view('web.setpassword');
    }

    public function otp() {
        return view('web.otp');
    }

    public function forgotPassword() {
        return view('web.forgotpassword');
    }

    public function login() {
        return view('web.login');
    }
      public function thankyou() {
        return view('web.thankyou');
    }

    // recruiter methods
    public function dashboard() {
        return view('web.recruiter.dashboard');
    }

    public function createEvent() {
        return view('web.recruiter.createevent');
    }

    public function myProfile() {
        return view('web.recruiter.myprofile');
    }

    public function createMyEvent() {
        return view('web.recruiter.createmyevent');
    }

    public function joinMyEvent() {
        return view('web.recruiter.joinmyevent');
    }

    public function myTicket() {
        return view('web.recruiter.myticket');
    }   
}
