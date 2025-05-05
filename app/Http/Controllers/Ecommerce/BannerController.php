<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderByDesc('id')->get();
        return view('banner.index', compact('banners'));
    }

    public function create()
    {
        // Show the banner creation form
        return view('banner.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/banners');
        }

        Banner::create([
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'image' => $imagePath,
            'link' => $request->link,
            'status' => 1,
        ]);

        return redirect()->route('ecommerce.banners')->with('success', 'Banner created successfully!');
    }

    public function edit(Banner $banner)
    {
        return view('banner.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image
            if ($banner->image) {
                Storage::delete($banner->image);
            }
            $imagePath = $request->file('image')->store('uploads/banners');
            $banner->image = $imagePath;
        }

        $banner->title = $request->title;
        $banner->sub_title = $request->sub_title;
        $banner->link = $request->link;
        $banner->save();

        return redirect()->route('ecommerce.banners')->with('success', 'Banner updated successfully!');
    }

    public function destroy(Banner $banner)
    {
        // Delete the image file
        if ($banner->image) {
            Storage::delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('ecommerce.banners')->with('success', 'Banner deleted successfully!');
    }
} 