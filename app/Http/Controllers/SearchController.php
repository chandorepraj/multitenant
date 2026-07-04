<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Lead;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function index(Request $request)
    {
        $search = trim($request->search_text);
        $tenantId = session('tenant_id');

        $customers = collect();
        $leads = collect();

        if ($search != '') {

            $customers = Customer::where('tenant_id', $tenantId)
                ->where(function ($query) use ($search) {

                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");

                })
                ->get();

            $leads = Lead::where('tenant_id', $tenantId)
                ->where(function ($query) use ($search) {

                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");

                })
                ->get();
        }

        return view('search.index', compact(
            'search',
            'customers',
            'leads'
        ));
    }
}
