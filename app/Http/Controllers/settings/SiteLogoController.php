<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Setting;
use Illuminate\Support\Facades\Validator;

class SiteLogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $logos = [
            'desktop_logo' => Setting::get('desktop_logo'),
            'mobile_logo' => Setting::get('mobile_logo'),
            'favicon' => Setting::get('favicon'),
            'apple_touch_icon' => Setting::get('apple_touch_icon'),
        ];

        return Inertia::render('Settings/Logos', [
            'logos' => $logos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request )
    {
        $validator = Validator::make($request->all(), [
            'desktop_logo' => 'nullable|image|max:2048',
            'mobile_logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|mimes:ico,png|max:1024',
            'apple_touch_icon' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $uploads = [];

        foreach (['desktop_logo', 'mobile_logo', 'favicon', 'apple_touch_icon'] as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->storePubliclyAs('logos', $file->hashName(), 'public');
                Setting::set($key, '/storage/' . $path);
                $uploads[$key] = '/storage/' . $path;
            }
        }

        Setting::save();

        return redirect()->route('admin.settings.logos.edit')
            ->with('success', 'Site logos updated successfully.')
            ->with('logos', $uploads);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
