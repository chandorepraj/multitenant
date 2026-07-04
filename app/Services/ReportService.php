<?php
namespace App\Services;

use App\Models\Lead;
use Illuminate\Http\Request;
use App\Models\Customer;


class ReportService
{
    public function leadQuery(Request $request)
    {
        return Lead::query()
            ->where('tenant_id', session('tenant_id'))
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            });
    }
    public function customerQuery(Request $request)
    {
        return Customer::query()
            ->where('tenant_id', session('tenant_id'))
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });
    }
    public function customerStats(Request $request)
    {
        $query = $this->customerQuery($request);

        return [
            'total' => (clone $query)->count(),
            'today' => (clone $query)
                ->whereDate('created_at', today())
                ->count(),
            'this_month' => (clone $query)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'this_year' => (clone $query)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }
}