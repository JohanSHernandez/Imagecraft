<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Client;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Ventas del mes
        $monthlySales = Sale::where('user_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');

        // Comparación con el mes anterior
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthSales = Sale::where('user_id', $user->id)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total_amount');

        $salesPercentage = $lastMonthSales > 0 
            ? (($monthlySales - $lastMonthSales) / $lastMonthSales) * 100 
            : 100;

        // Clientes nuevos del mes
        $newClients = Client::where('user_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Comparación con el mes anterior
        $lastMonthClients = Client::where('user_id', $user->id)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $clientsPercentage = $lastMonthClients > 0 
            ? (($newClients - $lastMonthClients) / $lastMonthClients) * 100 
            : 100;

        // Selecciones pendientes
        $pendingSelections = Album::where('user_id', $user->id)
            ->whereHas('photos', function ($query) {
                $query->where('is_selected', true);
            })
            ->count();

        // Pagos pendientes
        $pendingPayments = Sale::where('user_id', $user->id)
            ->where('status', '!=', 'paid')
            ->sum(DB::raw('total_amount - paid_amount'));

        // Ventas recientes
        $recentSales = Sale::with('client')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Datos para el gráfico de ingresos vs gastos
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M');
            
            $income = Sale::where('user_id', $user->id)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
            
            // Aquí deberías sumar los gastos cuando implementes esa funcionalidad
            $expenses = 0;
            
            $monthlyData[] = [
                'name' => $monthName,
                'ingresos' => $income,
                'gastos' => $expenses
            ];
        }

        return response()->json([
            'monthly_sales' => $monthlySales,
            'sales_percentage' => $salesPercentage,
            'new_clients' => $newClients,
            'clients_percentage' => $clientsPercentage,
            'pending_selections' => $pendingSelections,
            'pending_payments' => $pendingPayments,
            'recent_sales' => $recentSales,
            'monthly_data' => $monthlyData
        ]);
    }
}