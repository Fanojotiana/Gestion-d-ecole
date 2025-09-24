<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeContentController extends Controller
{


    public function edit()
    {
        $contents = HomeContent::all()->keyBy('section');
        return view('admin.home-content.edit', compact('contents'));
    }

    public function update(Request $request)
    {
        $sections = $request->input('sections', []);

        foreach ($sections as $section => $data) {
            $content = HomeContent::firstOrNew(['section' => $section]);
            $content->title = $data['title'] ?? null;
            $content->description = $data['description'] ?? null;

            if ($request->hasFile("sections.$section.image")) {
                if ($content->image) {
                    Storage::disk('public')->delete($content->image);
                }
                $content->image = $request->file("sections.$section.image")->store('home-content', 'public');
            }
            $content->save();
        }

        return redirect()->back()->with('success', 'Contenus mis à jour avec succès.');
    }


    public function index()
    {
        $contents = HomeContent::all()->keyBy('section');
        return view('welcome', compact('contents'));
    }
}
