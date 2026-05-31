<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalStudents = Student::count();
        $totalUsers = User::count();
        $recentStudents = Student::latest()->take(5)->get();
        return view('dashboard', compact('user', 'totalStudents', 'totalUsers', 'recentStudents'));
    }
}