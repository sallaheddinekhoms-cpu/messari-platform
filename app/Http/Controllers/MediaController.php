<?php

namespace App\Http\Controllers;

use App\Models\MediaGallery;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'all');
        $category = $request->query('category', 'all');

        $query = MediaGallery::orderBy('event_date', 'desc');

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $items = $query->paginate(12)->withQueryString();

        $featuredVideo = MediaGallery::where('type', 'video')->where('is_featured', true)->first();

        return view('masari.media', compact('items', 'type', 'category', 'featuredVideo'));
    }
}
