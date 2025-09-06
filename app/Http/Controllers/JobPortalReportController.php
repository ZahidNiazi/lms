<?php

namespace App\Http\Controllers;

use App\Models\JobPortalApplication;
use App\Models\ApplicationReview;
use App\Models\TrainingBatch;
use App\Models\ApplicationCommunication;
use App\Models\PoliceDisVetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobPortalReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->type !== 'super admin') {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
            return $next($request);
        });
    }

    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());

        $stats = $this->getReportStatistics($dateFrom, $dateTo);
        $charts = $this->getChartData($dateFrom, $dateTo);

        return view('job-portal.reports.index', compact('stats', 'charts', 'dateFrom', 'dateTo'));
    }

    /**
     * Export reports
     */
    public function export(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->endOfMonth()->toDateString());
        $type = $request->get('type', 'applications');

        switch ($type) {
            case 'applications':
                return $this->exportApplications($dateFrom, $dateTo);
            case 'interviews':
                return $this->exportInterviews($dateFrom, $dateTo);
            case 'communications':
                return $this->exportCommunications($dateFrom, $dateTo);
            case 'vetting':
                return $this->exportVetting($dateFrom, $dateTo);
            default:
                return redirect()->back()->with('error', 'Invalid export type.');
        }
    }

    /**
     * Get report statistics
     */
    private function getReportStatistics($dateFrom, $dateTo)
    {
        return [
            'total_applications' => JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'approved_applications' => JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'approved')->count(),
            'rejected_applications' => JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'rejected')->count(),
            'interview_scheduled' => JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'interview_scheduled')->count(),
            'selected_applications' => JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'selected')->count(),
            'batch_assigned' => JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'batch_assigned')->count(),
            'total_communications' => ApplicationCommunication::whereBetween('created_at', [$dateFrom, $dateTo])->count(),
            'successful_communications' => ApplicationCommunication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'delivered')->count(),
            'failed_communications' => ApplicationCommunication::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'failed')->count(),
            'pending_vetting' => PoliceDisVetting::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'pending')->count(),
            'cleared_vetting' => PoliceDisVetting::whereBetween('created_at', [$dateFrom, $dateTo])
                ->where('status', 'cleared')->count(),
            'failed_vetting' => PoliceDisVetting::whereBetween('created_at', [$dateFrom, $dateTo])
                ->whereIn('status', ['failed', 'rejected'])->count()
        ];
    }

    /**
     * Get chart data
     */
    private function getChartData($dateFrom, $dateTo)
    {
        // Applications by status
        $applicationsByStatus = JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Applications by month
        $applicationsByMonth = JobPortalApplication::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Communications by type
        $communicationsByType = ApplicationCommunication::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        // Vetting by type and status
        $vettingByType = PoliceDisVetting::whereBetween('created_at', [$dateFrom, $dateTo])
            ->selectRaw('vetting_type, status, count(*) as count')
            ->groupBy('vetting_type', 'status')
            ->get()
            ->groupBy('vetting_type');

        return [
            'applications_by_status' => $applicationsByStatus,
            'applications_by_month' => $applicationsByMonth,
            'communications_by_type' => $communicationsByType,
            'vetting_by_type' => $vettingByType
        ];
    }

    /**
     * Export applications data
     */
    private function exportApplications($dateFrom, $dateTo)
    {
        $applications = JobPortalApplication::with(['student.profile', 'student.addresses', 'batch'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $filename = 'applications_' . $dateFrom . '_to_' . $dateTo . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Application Number',
                'Student Name',
                'Email',
                'NID',
                'Mobile',
                'Address',
                'Status',
                'Applied Date',
                'Reviewed Date',
                'Batch',
                'Position'
            ]);

            foreach ($applications as $application) {
                $address = $application->student->addresses->first();
                fputcsv($file, [
                    $application->application_number,
                    $application->student->name,
                    $application->student->email,
                    $application->student->profile->nid ?? '',
                    $application->student->profile->mobile_no ?? '',
                    ($address ? $address->island . ', ' . $address->atoll : ''),
                    ucfirst(str_replace('_', ' ', $application->status)),
                    $application->created_at->format('Y-m-d'),
                    $application->reviewed_at ? $application->reviewed_at->format('Y-m-d') : '',
                    $application->batch ? $application->batch->batch_name : '',
                    $application->batch_position ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export interviews data
     */
    private function exportInterviews($dateFrom, $dateTo)
    {
        // Implementation for exporting interview data
        return redirect()->back()->with('info', 'Interview export feature coming soon.');
    }

    /**
     * Export communications data
     */
    private function exportCommunications($dateFrom, $dateTo)
    {
        // Implementation for exporting communications data
        return redirect()->back()->with('info', 'Communications export feature coming soon.');
    }

    /**
     * Export vetting data
     */
    private function exportVetting($dateFrom, $dateTo)
    {
        // Implementation for exporting vetting data
        return redirect()->back()->with('info', 'Vetting export feature coming soon.');
    }
}