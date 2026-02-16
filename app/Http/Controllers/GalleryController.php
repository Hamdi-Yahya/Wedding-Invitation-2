<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        return view('dashboard.gallery');
    }

    public function apiIndex()
    {
        $images = GalleryImage::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get();

        return response()->json(['success' => true, 'images' => $images]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image_url' => 'required|string',
            'alt_text' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'object_position' => 'nullable|string|max:50',
        ]);

        $maxOrder = GalleryImage::max('sort_order') ?? 0;
        $validated['sort_order'] = $maxOrder + 1;

        $image = GalleryImage::create($validated);

        return response()->json(['success' => true, 'image' => $image], 201);
    }

    public function update(Request $request, int $id)
    {
        $image = GalleryImage::findOrFail($id);

        $validated = $request->validate([
            'image_url' => 'sometimes|string',
            'alt_text' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'object_position' => 'nullable|string|max:50',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $image->update($validated);

        return response()->json(['success' => true, 'image' => $image]);
    }

    public function destroy(int $id)
    {
        $image = GalleryImage::findOrFail($id);
        $image->delete();

        return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus.']);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,jpg,png,webp,gif|max:5120',
            'type' => 'required|in:gallery,background',
        ]);

        $file = $request->file('file');
        $type = $request->input('type');
        $extension = $file->getClientOriginalExtension();
        $filename = $type . '_' . time() . '.' . $extension;

        $file->move(public_path('uploads'), $filename);

        $url = '/uploads/' . $filename;

        return response()->json(['success' => true, 'url' => $url]);
    }
}
