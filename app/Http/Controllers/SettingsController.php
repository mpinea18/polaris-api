<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function get($key)
    {
        $setting = DB::table('settings')->where('key', $key)->first();
        return response()->json([
            'key'   => $key,
            'value' => $setting ? $setting->value : null,
        ]);
    }

    public function set(Request $request, $key)
    {
        $request->validate(['value' => 'nullable|string']);
        DB::table('settings')->updateOrInsert(
            ['key' => $key],
            ['value' => $request->value, 'updated_at' => now()]
        );
        return response()->json(['key' => $key, 'value' => $request->value]);
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,avi|max:102400',
        ]);

        $current = DB::table('settings')->where('key', 'background_video')->first();
        if ($current && $current->value && str_contains($current->value, '/storage/')) {
            $oldPath = 'public/' . basename($current->value);
            Storage::delete($oldPath);
        }

        $path = $request->file('video')->store('videos', 'public');
        $url  = url('/storage/' . $path);

        DB::table('settings')->updateOrInsert(
            ['key' => 'background_video'],
            ['value' => $url, 'updated_at' => now()]
        );

        return response()->json(['key' => 'background_video', 'value' => $url]);
    }
}