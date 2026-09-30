<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Report;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'pending_kyc' => User::where('kyc_status', 'pending')->count(),
            'active_orders' => Order::whereNotIn('status', ['completed', 'cancelled'])->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'total_fees' => Order::where('status', 'completed')->sum('amount') * 0.05,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function kycIndex()
    {
        $users = User::where('kyc_status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.kyc.index', compact('users'));
    }

    public function verifyKYC(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $user->update(['kyc_status' => $request->status]);

        if ($request->status === 'verified') {
            $user->assignRole('vendor');
        }

        // Send Notification to User
        $title = $request->status === 'verified' ? \App\Support\LocalizedMessage::store('events.seller_account_verified') : \App\Support\LocalizedMessage::store('events.identity_verification_rejected');
        $content = $request->status === 'verified' 
            ? \App\Support\LocalizedMessage::store('events.congratulations_your_identity_documents_have_been_verified_you')
            : \App\Support\LocalizedMessage::store('events.your_identity_documents_were_rejected_please_submit_a');
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
        ]);

        return back()->with('status', __('KYC status updated to :value1 for :value2', ['value1' => \App\Support\LocalizedMessage::status($request->status), 'value2' => $user->name]));
    }

    public function reportsIndex()
    {
        $reports = Report::with(['reporter', 'reported', 'product'])->latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }
}
