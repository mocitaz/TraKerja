<?php

namespace App\Http\Controllers;

use App\Models\AiStudioRun;
use App\Services\AiStudioProcessingService;
use Illuminate\Http\Request;

class StudioRequestController extends Controller
{
    public function submit(Request $request, string $task)
    {
        $run = app(AiStudioProcessingService::class)->submit($request, $task);
        return $this->respond($request, $run);
    }

    public function status(Request $request, int $run)
    {
        $model = $this->owned($request, $run);
        return response()->json(['success' => true, 'status' => $model->status, 'credit_state' => $model->credit_state,
            'message' => $model->error_message, 'url' => $model->workspaceUrl()])->header('Cache-Control', 'no-store');
    }

    public function retry(Request $request, int $run)
    {
        return $this->respond($request, app(AiStudioProcessingService::class)->retry($request, $this->owned($request, $run)));
    }

    private function owned(Request $request, int $id): AiStudioRun
    {
        abort_if($request->user()->isAdmin(), 403);
        abort_unless(\Illuminate\Support\Facades\Schema::hasTable('ai_studio_runs'), 503, 'AI Studio is being updated. Please try again shortly.');
        return AiStudioRun::where('user_id', $request->user()->id)->findOrFail($id);
    }

    private function respond(Request $request, AiStudioRun $run)
    {
        return $request->expectsJson()
            ? response()->json(['success' => true, 'status' => $run->status, 'request_id' => $run->id, 'url' => $run->workspaceUrl()], 202)
            : redirect()->to($run->workspaceUrl());
    }
}
