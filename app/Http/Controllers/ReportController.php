<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Customer;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
class ReportController extends Controller
{
    //
    public function leads(Request $request, ReportService $reportService)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $query = $reportService->leadQuery($request);

        $stats = [
            'Total' => (clone $query)->count(),
            'New' => (clone $query)->where('status', 'New')->count(),
            'Qualified' => (clone $query)->where('status', 'Qualified')->count(),
            'Converted' => (clone $query)->where('is_converted', true)->count(),
            'Lost' => (clone $query)->where('status', 'Lost')->count(),
            'Contacted' => (clone $query)->where('status', 'Contacted')->count(),
            'Won' => (clone $query)->where('status', 'Won')->count(),

        ];

        $leads = $query->latest()->paginate(10);
        
        return view('reports.leads', compact('leads','stats'));
    }
    public function exportLeadCsv(Request $request, ReportService $reportService)
    {
        $leads = $reportService->leadQuery($request)
            ->latest()
            ->get();
        $stats = [
            'Total' => (clone $leads)->count(),
            'New' => (clone $leads)->where('status', 'New')->count(),
            'Qualified' => (clone $leads)->where('status', 'Qualified')->count(),
            'Converted' => (clone $leads)->where('is_converted', true)->count(),
            'Lost' => (clone $leads)->where('status', 'Lost')->count(),
            'Contacted' => (clone $leads)->where('status', 'Contacted')->count(),
            'Won' => (clone $leads)->where('status', 'Won')->count(),

        ];
        $fileName = 'leads_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($leads,$stats) {

            $file = fopen('php://output', 'w');

            // Summary
            fputcsv($file, [
                'Summary'
            ]);
            fputcsv($file, [
                'Total',$stats['Total']
            ]);
             fputcsv($file, [
                'New',$stats['New']
            ]);
             fputcsv($file, [
                'Qualified',$stats['Qualified']
            ]);
             fputcsv($file, [
                'Converted',$stats['Converted']
            ]);
             fputcsv($file, [
                'Contacted',$stats['Contacted']
            ]);
             fputcsv($file, [
                'Won',$stats['Won']
            ]);
             fputcsv($file, [
                'Lost',$stats['Lost']
            ]);
            // Header row
            fputcsv($file, [
                'Name',
                'Email',
                'Phone',
                'Status',
                'Created At'
            ]);

            // Data rows
            foreach ($leads as $lead) {

                fputcsv($file, [
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->status,
                    $lead->created_at->format('d-m-Y'),
                ]);
            }

            fclose($file);
        };

       return response()->stream($callback, 200, $headers);
    }
    public function exportLeadPdf(Request $request, ReportService $reportService)
    {

        $leads = $reportService->leadQuery($request)
            ->latest()
            ->get();
        $stats = [
            'Total' => (clone $leads)->count(),
            'New' => (clone $leads)->where('status', 'New')->count(),
            'Qualified' => (clone $leads)->where('status', 'Qualified')->count(),
            'Converted' => (clone $leads)->where('is_converted', true)->count(),
            'Lost' => (clone $leads)->where('status', 'Lost')->count(),
            'Contacted' => (clone $leads)->where('status', 'Contacted')->count(),
            'Won' => (clone $leads)->where('status', 'Won')->count(),

        ];
        $filters = $request->all();

        $pdf = Pdf::loadView('reports.pdf.leads', [
            'leads' => $leads,
            'stats' => $stats,
            'filters' => $filters,
        ]);

        return $pdf->download(
            'Lead_Report_' . now()->format('Ymd_His') . '.pdf'
        );
    }
    public function customers(Request $request, ReportService $reportService)
    {
        $filters = $request->all();
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $query = $reportService->customerQuery($request);

        $stats = $reportService->customerStats($request);

        $customers = $query->latest()->paginate(10);
        
        return view('reports.customers', compact('customers','stats'));
    }
    public function exportCustomerCsv(Request $request, ReportService $reportService)
    {
        $customers = $reportService->customerQuery($request)
            ->latest()
            ->get();
        $stats = $reportService->customerStats($request);
        $fileName = 'customers_report_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($customers,$stats) {

            $file = fopen('php://output', 'w');

            // Summary
            fputcsv($file, [
                'Summary'
            ]);
            fputcsv($file, [
                'Total',$stats['total']
            ]);
             fputcsv($file, [
                'Today',$stats['today']
            ]);
             fputcsv($file, [
                'This Month',$stats['this_month']
            ]);
             fputcsv($file, [
                'This Year',$stats['this_year']
            ]);
            
            // Header row
            fputcsv($file, [
                'Name',
                'Email',
                'Phone',
                'Company',
                'Creator',
                'Created At'
            ]);

            // Data rows
            foreach ($customers as $customer) {

                fputcsv($file, [
                    $customer->name,
                    $customer->email,
                    $customer->phone,
                    $customer->company,
                     $customer->creator->name,
                    $customer->created_at->format('d-m-Y'),
                ]);
            }

            fclose($file);
        };

       return response()->stream($callback, 200, $headers);
    }
    public function exportCustomerPdf(Request $request, ReportService $reportService)
    {

        $customers = $reportService->customerQuery($request)
            ->latest()
            ->get();
        $stats = $reportService->customerStats($request);
        $filters = $request->all();

        $pdf = Pdf::loadView('reports.pdf.customers', [
            'customers' => $customers,
            'stats' => $stats,
            'filters' => $filters,
        ]);

        return $pdf->download(
            'Customer_Report_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}
