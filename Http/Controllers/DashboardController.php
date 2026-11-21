<?php

namespace Modules\AdminManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Shared\Models\Client;
use Modules\Shared\Models\Seller;
use Modules\Shared\Models\Website;
use Modules\Shared\Models\Order;
use Modules\Shared\Models\Payment;

class DashboardController extends Controller
{
    /**
     * Render the Dashboard Blade view.
     */
    public function renderView($viewFactory)
    {
        $data = $this->index();
        return $viewFactory->make('dashboard.index', $data);
    }

    /**
     * Collect dashboard metrics and data.
     */
    public function index(): array
    {
        try {
            $clients = Client::latest()->get()->toArray();
        } catch (\Exception $e) {
            $clients = [];
        }

        try {
            $sellers = Seller::latest()->get()->toArray();
        } catch (\Exception $e) {
            $sellers = [];
        }

        try {
            $websites = Website::with('vendor')->latest()->get()->toArray();
        } catch (\Exception $e) {
            $websites = [];
        }

        try {
            $orders = Order::with('client')->latest('order_date')->get()->toArray();
        } catch (\Exception $e) {
            $orders = [];
        }

        try {
            $payments = Payment::with(['client', 'vendor'])->latest('payment_date')->get()->toArray();
        } catch (\Exception $e) {
            $payments = [];
        }

        $totalRevenue = array_sum(array_column($orders, 'total_client_price'));
        $totalCost = array_sum(array_column($orders, 'total_vendor_cost'));
        $totalProfit = array_sum(array_column($orders, 'total_profit'));
        $pendingOrders = count(array_filter($orders, fn($o) => in_array($o['status'], ['pending', 'in_progress'])));
        $publishedPosts = count(array_filter($orders, fn($o) => in_array($o['status'], ['published', 'completed'])));

        return [
            'clients' => $clients,
            'sellers' => $sellers,
            'websites' => $websites,
            'orders' => $orders,
            'payments' => $payments,
            'stats' => [
                'total_revenue' => '$' . number_format($totalRevenue, 2),
                'total_cost' => '$' . number_format($totalCost, 2),
                'total_profit' => '$' . number_format($totalProfit, 2),
                'pending_orders' => $pendingOrders,
                'published_posts' => $publishedPosts,
            ]
        ];
    }
}
