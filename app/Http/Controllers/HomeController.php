<?php
namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function getDocAPI()
    {
        return view('swagger.mobile');
    }

    public function getDocAPICms()
    {
        return view('swagger.cms');
    }
}
