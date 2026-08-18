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
        $title = $request->status === 'verified' ? 'Compte commerçant validé ! 🎉' : 'Dossier KYC rejeté ⚠️';
        $content = $request->status === 'verified' 
            ? 'Félicitations, vos pièces d\'identité ont été validées. Vous pouvez maintenant accéder à votre boutique vendeur.'
            : 'Malheureusement, vos pièces d\'identité ont été rejetées. Veuillez soumettre un document conforme dans votre profil.';
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => $title,
            'content' => $content,
        ]);

        return back()->with('status', "KYC status updated to {$request->status} for {$user->name}");
    }

    public function reportsIndex()
    {
        $reports = Report::with(['reporter', 'reported', 'product'])->latest()->paginate(15);
        return view('admin.reports.index', compact('reports'));
    }
}
