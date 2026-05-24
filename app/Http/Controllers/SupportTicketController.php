<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Mail\SupportTicketNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\ActivityLogger;

class SupportTicketController extends Controller
{
    /**
     * Display the support tickets / feedback page.
     */
    public function index(): View
    {
        // Redirect admin users
        if (Auth::user() && (Auth::user()->isAdmin() || Auth::user()->role === 'admin')) {
            return redirect()->route('admin.index');
        }

        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('support.index', compact('tickets'));
    }

    /**
     * Store a new support ticket / feedback.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category' => 'required|string|in:technical_issue,payment_billing,feature_request,general_feedback',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ], [
            'category.required' => 'Kategori bantuan wajib dipilih.',
            'subject.required' => 'Subjek atau judul pesan wajib diisi.',
            'message.required' => 'Isi pesan bantuan wajib diisi.',
            'message.min' => 'Pesan terlalu pendek, minimal 10 karakter.',
            'message.max' => 'Pesan terlalu panjang, maksimal 5000 karakter.',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'category' => $request->input('category'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        ActivityLogger::log(
            'support_ticket',
            "User mengirim tiket bantuan/feedback: {$request->input('subject')}",
            'success',
            ['category' => $request->input('category')],
            Auth::id()
        );

        try {
            Mail::to('info@teknalogi.id')->send(new SupportTicketNotificationMail($ticket));
        } catch (\Exception $e) {
            logger()->error('Gagal mengirim email notifikasi tiket bantuan: ' . $e->getMessage());
        }

        return redirect()->route('support.index')->with('success_message', 'Pesan bantuan Anda berhasil dikirim! Tim support kami akan segera membalasnya.');
    }

    /**
     * Delete a support ticket / feedback.
     */
    public function destroy(SupportTicket $ticket): RedirectResponse
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $ticket->delete();

        return redirect()->route('support.index')->with('success_message', 'Riwayat bantuan berhasil dihapus.');
    }
}
