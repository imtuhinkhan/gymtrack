<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PwaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PwaSettingsController extends Controller
{
    /**
     * Display the PWA settings form.
     */
    public function index()
    {
        $pwaSettings = PwaSetting::getCurrent();
        return view('admin.pwa-settings.index', compact('pwaSettings'));
    }

    /**
     * Update PWA settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'theme_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'display' => 'required|in:standalone,fullscreen,minimal-ui,browser',
            'orientation' => 'required|in:portrait,landscape,any',
            'start_url' => 'required|string',
            'scope' => 'required|string',
            'icon_192' => 'nullable|image|mimes:png|max:2048',
            'icon_512' => 'nullable|image|mimes:png|max:2048',
            'splash_icon' => 'nullable|image|mimes:png|max:2048',
            'is_enabled' => 'boolean',
        ]);

        $pwaSettings = PwaSetting::getCurrent();
        
        $data = $request->only([
            'app_name', 'short_name', 'description', 'theme_color', 
            'background_color', 'display', 'orientation', 'start_url', 
            'scope', 'is_enabled'
        ]);

        // Handle file uploads
        if ($request->hasFile('icon_192')) {
            $data['icon_192'] = $request->file('icon_192')->store('pwa-icons', 'public');
        }

        if ($request->hasFile('icon_512')) {
            $data['icon_512'] = $request->file('icon_512')->store('pwa-icons', 'public');
        }

        if ($request->hasFile('splash_icon')) {
            $data['splash_icon'] = $request->file('splash_icon')->store('pwa-icons', 'public');
        }

        $pwaSettings->update($data);

        return redirect()->route('admin.pwa-settings.index')
            ->with('success', 'PWA settings updated successfully!');
    }

    /**
     * Generate and return the PWA manifest.json
     */
    public function manifest()
    {
        $pwaSettings = PwaSetting::getCurrent();
        
        if (!$pwaSettings->is_enabled) {
            abort(404);
        }

        $manifest = [
            'name' => $pwaSettings->app_name,
            'short_name' => $pwaSettings->short_name,
            'description' => $pwaSettings->description,
            'start_url' => $pwaSettings->start_url,
            'scope' => $pwaSettings->scope,
            'display' => $pwaSettings->display,
            'orientation' => $pwaSettings->orientation,
            'theme_color' => $pwaSettings->theme_color,
            'background_color' => $pwaSettings->background_color,
            'icons' => [],
        ];

        // Add icons if they exist
        if ($pwaSettings->icon_192) {
            $manifest['icons'][] = [
                'src' => Storage::url($pwaSettings->icon_192),
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ];
        }

        if ($pwaSettings->icon_512) {
            $manifest['icons'][] = [
                'src' => Storage::url($pwaSettings->icon_512),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ];
        }

        return response()->json($manifest);
    }
}