<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get dashboard statistics
        $totalCustomers = User::count();
        
        // Get today's orders count
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        
        // Get pending orders count
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Get customer requests (assuming this is contact form submissions or similar)
        $customerRequests = 0; // Placeholder - implement based on your requirements

        // Get cart statistics
        $activeCarts = CartItem::distinct('session_id')->count('session_id');
        $totalCartItems = CartItem::sum('quantity');
        $totalCartValue = CartItem::join('products', 'cart_items.product_id', '=', 'products.id')
            ->selectRaw('SUM(cart_items.quantity * products.price) as total')
            ->value('total') ?? 0;
        
        // Get earnings data for chart - last 6 months
        $monthlyEarnings = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        
        // Prepare chart data
        $labels = [];
        $data = [];
        
        // Fill in the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M');
            $labels[] = $monthName;
            
            // Find earnings for this month
            $earnings = $monthlyEarnings->where('month', $date->month)
                                      ->where('year', $date->year)
                                      ->first();
            
            $data[] = $earnings ? (float)$earnings->total : 0;
        }
        
        $earningsData = [
            'labels' => $labels,
            'data' => $data
        ];
        
        return view('admin.dashboard', compact(
            'totalCustomers',
            'todayOrders', 
            'pendingOrders',
            'customerRequests',
            'earningsData',
            'activeCarts',
            'totalCartItems',
            'totalCartValue'
        ));
    }
}