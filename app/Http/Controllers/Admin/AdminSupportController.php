<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminSupportController extends Controller
{
    /**
     * Display a listing of user feedbacks and support tickets.
     */
    public function index(Request $request): View
    {
        $query = SupportTicket::with('user')->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $tickets = $query->paginate(15);

        // Stats
        $totalFeedbacks = SupportTicket::count();
        $pendingFeedbacks = SupportTicket::where('status', 'pending')->count();
        $repliedFeedbacks = SupportTicket::where('status', 'replied')->count();
        $completedFeedbacks = SupportTicket::where('status', 'completed')->count();
        $onHoldFeedbacks = SupportTicket::where('status', 'on_hold')->count();

        return view('admin.feedbacks', compact(
            'tickets', 
            'totalFeedbacks', 
            'pendingFeedbacks', 
            'repliedFeedbacks',
            'completedFeedbacks',
            'onHoldFeedbacks'
        ));
    }

    /**
     * Reply to a user support ticket and update its status.
     */
    public function reply(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate([
            'admin_reply' => 'required|string|min:5|max:5000',
            'status' => 'required|string|in:pending,replied,completed,on_hold',
        ], [
            'admin_reply.required' => 'Balasan wajib diisi.',
            'admin_reply.min' => 'Balasan minimal 5 karakter.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $ticket->update([
            'admin_reply' => $request->input('admin_reply'),
            'status' => $request->input('status'),
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.feedbacks.index')->with('success_message', 'Balasan berhasil disimpan dan status tiket diperbarui.');
    }

    /**
     * Update the status of a support ticket directly.
     */
    public function updateStatus(Request $request, SupportTicket $ticket): RedirectResponse
    {
        $request->validate([
            'status' => 'required|string|in:pending,replied,completed,on_hold',
        ]);

        $ticket->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->route('admin.feedbacks.index')->with('success_message', 'Status tiket berhasil diperbarui menjadi ' . ucfirst($ticket->status) . '.');
    }

    /**
     * Delete a feedback / support ticket.
     */
    public function destroy(SupportTicket $ticket): RedirectResponse
    {
        $ticket->delete();

        return redirect()->route('admin.feedbacks.index')->with('success_message', 'Feedback pengguna berhasil dihapus.');
    }
}
