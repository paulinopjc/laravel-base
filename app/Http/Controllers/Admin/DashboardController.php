<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return Inertia::render('Admin/Dashboard');
    }
}