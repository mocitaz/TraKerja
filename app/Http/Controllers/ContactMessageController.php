<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class ContactMessageController extends Controller
{
    /**
     * Terima pesan dari floating widget di landing page.
     */
    public function store(Request $request)
    {
        // Rate limiting: maks 3 pesan per IP per 10 menit
        $key = 'contact:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak permintaan. Coba lagi dalam {$seconds} detik.",
            ], 429);
        }
        RateLimiter::hit($key, 600); // 10 menit

        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ], [
            'name.required'    => 'Nama wajib diisi.',
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        // Simpan ke database
        $contact = ContactMessage::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'subject'    => $validated['subject'] ?? null,
            'message'    => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        // Kirim notifikasi email ke admin (opsional, aktifkan jika SMTP sudah dikonfigurasi)
        // $this->sendAdminNotification($contact);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim! Kami akan menghubungi Anda segera.',
        ]);
    }

    /**
     * [ADMIN] Daftar semua pesan masuk.
     */
    public function index()
    {
        $messages = ContactMessage::orderByDesc('created_at')->paginate(20);
        return view('admin.contact-messages.index', compact('messages'));
    }

    /**
     * [ADMIN] Detail pesan + tandai sudah dibaca.
     */
    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();
        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Kirim email notifikasi ke admin.
     * Aktifkan jika sudah mengkonfigurasi SMTP di .env
     */
    // private function sendAdminNotification(ContactMessage $contact): void
    // {
    //     Mail::raw(
    //         "Pesan baru dari: {$contact->name} ({$contact->email})\n\n{$contact->message}",
    //         function ($mail) use ($contact) {
    //             $mail->to(config('mail.from.address'))
    //                  ->subject('[TraKerja] Pesan baru: ' . ($contact->subject ?? 'Tanpa subjek'));
    //         }
    //     );
    // }
}