<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, EmailBlastLog, EmailBlastTemplate};
use App\Mail\CustomEmailBlastMail;
use App\Support\{EmailBannerCatalog, EmailTemplateCatalog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, Log};
use Illuminate\Validation\Rule;

class EmailBlastController extends Controller
{
    public function index()
    {
        return view('admin.email-blast', [
            'audienceCounts' => collect(['all', 'verified', 'unverified', 'premium', 'free', 'new'])->mapWithKeys(fn ($key) => [$key => $this->audienceQuery($key)->count()])->all(),
            'templates' => EmailTemplateCatalog::groups(),
            'savedTemplates' => EmailBlastTemplate::whereNotNull('reviewed_at')->latest()->get(['id', 'name', 'language', 'subject', 'content', 'button_text', 'button_url']),
            'geminiReady' => (bool) config('services.gemini.key'),
        ]);
    }

    private function validateMessage(Request $request, array $extra = []): void
    {
        $rules = [
            'banner' => EmailBannerCatalog::rules(),
            'email_type' => ['required', Rule::in([...array_keys(EmailTemplateCatalog::mailables()), 'verification', 'custom'])],
            'email_language' => 'nullable|in:id,en',
        ];
        if ($request->input('email_type') === 'custom') {
            $rules += [
                'custom_subject' => 'required|string|max:255',
                'custom_content' => 'required|string|max:5000',
                'custom_button_text' => 'nullable|required_with:custom_button_url|string|max:100',
                'custom_button_url' => 'nullable|required_with:custom_button_text|url:http,https|max:500',
            ];
        }
        $request->validate($rules + $extra);
        app()->setLocale($request->input('email_language') ?: app()->getLocale());
    }

    private function mailable(User $user, Request $request): \Illuminate\Mail\Mailable
    {
        $type = $request->input('email_type');
        if ($type === 'custom') {
            $mail = new CustomEmailBlastMail($user, $request->input('custom_subject'), $request->input('custom_content'), $request->input('custom_button_text'), $request->input('custom_button_url'));
        } elseif ($type === 'verification') {
            $mail = new \App\Mail\OtpVerificationMail($user, '123456');
        } else {
            $class = EmailTemplateCatalog::mailables()[$type];
            $mail = new $class($user);
        }
        return $mail->locale(app()->getLocale())->with('emailBannerId', $request->input('banner'));
    }

    public function preview(Request $request)
    {
        $this->validateMessage($request);
        return response($this->mailable($request->user(), $request)->render())->header('Content-Type', 'text/html');
    }

    public function initProgress(Request $request)
    {
        $this->validateMessage($request, ['target_user' => 'required|in:all,verified,premium,free,new,unverified']);
        $query = $this->audienceQuery($request->input('target_user'));
        if ($request->input('email_type') === 'verification') $query->whereNull('email_verified_at');
        $users = $query->get(['id', 'email', 'name']);
        if ($users->isEmpty()) return response()->json(['success' => false, 'message' => __('Tidak ada user yang ditemukan untuk target yang dipilih.')], 422);
        return response()->json(['success' => true, 'users' => $users, 'total' => $users->count()]);
    }

    public function sendSingleProgress(Request $request)
    {
        $this->validateMessage($request, ['user_id' => 'required|exists:users,id']);
        $user = User::where('role', '!=', 'admin')->findOrFail($request->input('user_id'));
        try {
            if ($request->input('email_type') === 'verification') {
                if ($user->hasVerifiedEmail()) return response()->json(['success' => false, 'error' => __('This account is already verified.')], 422);
                app(\App\Services\EmailVerificationService::class)->send($user, $request->input('banner'), app()->getLocale());
            } else {
                Mail::to($user)->locale(app()->getLocale())->send($this->mailable($user, $request));
            }
            return response()->json(['success' => true, 'email' => $user->email, 'name' => $user->name]);
        } catch (\Throwable $e) {
            Log::error('Email blast delivery failed', ['user_id' => $user->id, 'exception' => get_class($e)]);
            return response()->json(['success' => false, 'email' => $user->email, 'name' => $user->name, 'error' => __('Delivery could not be confirmed. Review before sending again.')], 500);
        }
    }

    public function send(Request $request)
    {
        $response = $this->initProgress($request);
        if ($response->getStatusCode() !== 200) return back()->with('error', $response->getData()->message);
        $success = 0;
        $users = $response->getData()->users;
        set_time_limit(0);
        foreach ($users as $user) {
            $request->merge(['user_id' => $user->id]);
            if ($this->sendSingleProgress($request)->getData()->success) $success++;
            sleep(1);
        }
        return back()->with('success', __(':value1 emails sent.', ['value1' => $success]));
    }

    public function storeLog(Request $request)
    {
        $data = $request->validate([
            'template_name' => 'nullable|string|max:255', 'subject' => 'nullable|string|max:255',
            'language' => 'nullable|in:id,en', 'banner' => EmailBannerCatalog::rules(),
            'email_type' => 'required|string|max:255', 'target_audience' => 'required|in:all,verified,premium,free,new,unverified',
            'total_target' => 'required|integer|min:0', 'success_count' => 'required|integer|min:0|lte:total_target',
            'failed_count' => 'required|integer|min:0|lte:total_target', 'failed_details' => 'nullable|array',
        ]);
        if ($data['success_count'] + $data['failed_count'] > $data['total_target']) {
            throw \Illuminate\Validation\ValidationException::withMessages(['total_target' => __('Result counts exceed the recipient total.')]);
        }
        $log = EmailBlastLog::create($data);
        return response()->json(['success' => true, 'log_id' => $log->id]);
    }

    public function history()
    {
        $audience = request('audience', 'all-segments');
        $search = mb_substr((string) request('search', ''), 0, 100);
        $query = EmailBlastLog::query()->when($search !== '', fn ($q) => $q->where(fn ($query) => $query->where('email_type', 'like', '%'.$search.'%')->orWhere('template_name', 'like', '%'.$search.'%')->orWhere('subject', 'like', '%'.$search.'%')));
        if (in_array($audience, ['all', 'verified', 'unverified', 'premium', 'free', 'new'], true)) {
            $query->where('target_audience', $audience);
        }
        $status = in_array(request('status'), ['complete', 'issues', 'partial'], true) ? request('status') : 'all';
        match ($status) {
            'complete' => $query->where('total_target', '>', 0)->whereColumn('success_count', 'total_target')->where('failed_count', 0),
            'issues' => $query->where('failed_count', '>', 0),
            'partial' => $query->whereRaw('total_target > success_count + failed_count'),
            default => null,
        };
        return view('admin.email-blast-history', [
            'logs' => $query->latest()->orderByDesc('id')->paginate(15)->withQueryString(),
            'search' => $search, 'audience' => $audience, 'status' => $status,
            'totalCampaigns' => EmailBlastLog::count(),
            'totalTarget' => EmailBlastLog::sum('total_target'),
            'totalSuccess' => EmailBlastLog::sum('success_count'),
            'totalFailed' => EmailBlastLog::sum('failed_count'),
        ]);
    }

    private function audienceQuery(string $target)
    {
        $query = User::where('role', '!=', 'admin');
        $activePremium = User::where('is_premium', true)->where('payment_status', 'paid')
            ->where(fn ($q) => $q->whereNull('premium_until')->orWhere('premium_until', '>', now()))->select('id');
        return match ($target) {
            'verified' => $query->whereNotNull('email_verified_at'),
            'unverified' => $query->whereNull('email_verified_at'),
            'new' => $query->where('created_at', '>=', now()->subDays(7)),
            'premium' => $query->whereIn('id', $activePremium),
            'free' => $query->whereNotIn('id', $activePremium),
            default => $query,
        };
    }

}
