<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bird;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.pages.dashboard', [
            'totalBirds'    => Bird::count(),
            'totalOrders'   => Order::count(),
            'totalRevenue'  => Order::where('status', 'paid')->sum('total'),
            'totalUsers'    => User::count(),
            'recentOrders'  => Order::with('user')->latest()->take(5)->get(),
        ]);
    }
}
