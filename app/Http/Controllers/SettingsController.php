<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display settings index page
     */
    public function index()
    {
        return view('settings.index');
    }

    /**
     * Portal Settings
     */
    public function portalSettings()
    {
        return view('settings.portal');
    }

    public function updatePortalSettings(Request $request)
    {
        $request->validate([
            'logo_dark' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_light' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            'title_text' => 'required|string|max:255',
            'footer_text' => 'nullable|string|max:500',
            'default_language' => 'required|in:en,dv',
            'enable_rtl' => 'boolean',
            'enable_landing_page' => 'boolean',
            'enable_sign_up' => 'boolean',
            'email_verification' => 'boolean',
            'address' => 'nullable|string|max:500',
            'contact_details' => 'nullable|string|max:500',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo_dark')) {
            $logoDark = $request->file('logo_dark');
            $logoDarkName = 'logo_dark.' . $logoDark->getClientOriginalExtension();
            $logoDark->storeAs('public/settings', $logoDarkName);
        }

        if ($request->hasFile('logo_light')) {
            $logoLight = $request->file('logo_light');
            $logoLightName = 'logo_light.' . $logoLight->getClientOriginalExtension();
            $logoLight->storeAs('public/settings', $logoLightName);
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = 'favicon.' . $favicon->getClientOriginalExtension();
            $favicon->storeAs('public/settings', $faviconName);
        }

        // Save settings to database or config file
        $settings = [
            'title_text' => $request->title_text,
            'footer_text' => $request->footer_text,
            'default_language' => $request->default_language,
            'enable_rtl' => $request->has('enable_rtl'),
            'enable_landing_page' => $request->has('enable_landing_page'),
            'enable_sign_up' => $request->has('enable_sign_up'),
            'email_verification' => $request->has('email_verification'),
            'address' => $request->address,
            'contact_details' => $request->contact_details,
        ];

        // Save to JSON file or database
        file_put_contents(storage_path('app/settings.json'), json_encode($settings));

        return redirect()->back()->with('success', 'Portal settings updated successfully.');
    }

    /**
     * Theme Customization
     */
    public function themeSettings()
    {
        return view('settings.theme');
    }

    public function updateThemeSettings(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|max:7',
            'transparent_layout' => 'boolean',
            'dark_layout' => 'boolean',
        ]);

        $themeSettings = [
            'primary_color' => $request->primary_color,
            'transparent_layout' => $request->has('transparent_layout'),
            'dark_layout' => $request->has('dark_layout'),
        ];

        file_put_contents(storage_path('app/theme_settings.json'), json_encode($themeSettings));

        return redirect()->back()->with('success', 'Theme settings updated successfully.');
    }

    /**
     * Storage Settings
     */
    public function storageSettings()
    {
        return view('settings.storage');
    }

    public function updateStorageSettings(Request $request)
    {
        $request->validate([
            'storage_driver' => 'required|in:local,aws_s3,wasabi',
            'max_upload_size' => 'required|integer|min:1',
            'allowed_file_types' => 'required|array',
        ]);

        $storageSettings = [
            'driver' => $request->storage_driver,
            'max_upload_size' => $request->max_upload_size,
            'allowed_file_types' => $request->allowed_file_types,
        ];

        // AWS S3 settings
        if ($request->storage_driver === 'aws_s3') {
            $request->validate([
                's3_key' => 'required|string',
                's3_region' => 'required|string',
                's3_url' => 'required|url',
                's3_secret_key' => 'required|string',
                's3_bucket' => 'required|string',
                's3_endpoint' => 'required|url',
            ]);

            $storageSettings['s3'] = [
                'key' => $request->s3_key,
                'region' => $request->s3_region,
                'url' => $request->s3_url,
                'secret' => $request->s3_secret_key,
                'bucket' => $request->s3_bucket,
                'endpoint' => $request->s3_endpoint,
            ];
        }

        // Wasabi settings
        if ($request->storage_driver === 'wasabi') {
            $request->validate([
                'wasabi_key' => 'required|string',
                'wasabi_secret' => 'required|string',
                'wasabi_region' => 'required|string',
                'wasabi_bucket' => 'required|string',
                'wasabi_url' => 'required|url',
                'wasabi_root' => 'nullable|string',
            ]);

            $storageSettings['wasabi'] = [
                'key' => $request->wasabi_key,
                'secret' => $request->wasabi_secret,
                'region' => $request->wasabi_region,
                'bucket' => $request->wasabi_bucket,
                'url' => $request->wasabi_url,
                'root' => $request->wasabi_root,
            ];
        }

        file_put_contents(storage_path('app/storage_settings.json'), json_encode($storageSettings));

        return redirect()->back()->with('success', 'Storage settings updated successfully.');
    }
}