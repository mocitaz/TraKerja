<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\{AdminControlSnapshot, AdminControlAnalyst};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminControlController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->isAdmin(),403);
        return view('admin.control');
    }

    public function snapshot(Request $request, AdminControlSnapshot $snapshots)
    {
        abort_unless($request->user()?->isAdmin(),403);
        $validated=$request->validate(['days'=>'sometimes|integer|in:7,30']);
        $days=(int)($validated['days'] ?? 7);
        try {
            return response()->json(['snapshot'=>$snapshots->build($days),
                'history'=>$request->session()->get($this->historyKey($request,$days),[])]);
        } catch (\Throwable) {
            return response()->json(['message'=>'The operational snapshot could not be loaded. Retry or open the source pages.'],503);
        }
    }

    public function ask(Request $request, AdminControlSnapshot $snapshots, AdminControlAnalyst $analyst)
    {
        abort_unless($request->user()?->isAdmin(),403);
        $validated=$request->validate(['question'=>'required|string|min:3|max:2000','days'=>'required|integer|in:7,30']);
        $lock=Cache::lock('admin-control:'.$request->user()->id,75);
        if (!$lock->get()) return response()->json(['message'=>'An analysis is already running. Please wait.'],429)->header('Retry-After','3');
        try {
            $snapshot=$snapshots->build((int)$validated['days']);
            $key=$this->historyKey($request,(int)$validated['days']);
            $history=$request->session()->get($key,[]);
            $answer=$analyst->answer($validated['question'],$snapshot,$history);
            $history[]=['role'=>'user','text'=>$validated['question'],'at'=>$snapshot['generated_at']];
            $history[]=['role'=>'model','text'=>$answer['text'],'mode'=>$answer['mode'],'at'=>$snapshot['generated_at']];
            $request->session()->put($key,array_slice($history,-12));
            return response()->json(['answer'=>$answer,'snapshot'=>$snapshot]);
        } catch (\Throwable) {
            return response()->json(['message'=>'Analysis could not be completed. Your prompt is kept so you can retry.'],503);
        } finally { $lock->release(); }
    }

    public function clear(Request $request)
    {
        abort_unless($request->user()?->isAdmin(),403);
        $validated=$request->validate(['days'=>'required|integer|in:7,30']);
        $request->session()->forget($this->historyKey($request,(int)$validated['days']));
        return response()->json(['success'=>true]);
    }

    private function historyKey(Request $request,int $days): string
    {
        return 'admin_control.'.$request->user()->id.'.'.$days;
    }
}
