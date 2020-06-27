<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $group = $user->group()->first();
        $gates = $group->gates()->where('key', 'marketers')->get();
        if(count($gates)>0){
            return redirect()->route('marketerdashboard');
        }
        $gates = $group->gates()->where('key', 'supporters')->get();
        if(count($gates)>0){
            return redirect()->route('supporter_students');
        }
        return view('dashboard.admin');
    }
}
