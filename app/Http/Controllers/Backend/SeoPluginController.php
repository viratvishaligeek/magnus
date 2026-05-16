<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoPluginController extends Controller
{
    private function getModel(string $type)
    {
        return match (Str::lower($type)) {
            'brand' => Brand::class,
            'category' => Category::class,
            'product' => Product::class,
            'blogcategory' => BlogCategory::class,
            'blog' => Blog::class,
            'page' => Page::class,
            default => abort(404, 'Invalid SEO Target Type')
        };
    }

    public function index(Request $request, string $type)
    {
        $model = $this->getModel($type);
        $pageName = Str::headline($type) . ' SEO Portfolio';

        $data = $model::with('seo')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'LIKE', "%{$request->search}%");
            })
            ->get();

        return view('backend.seo.index', compact('data', 'type', 'pageName'));
    }

    public function edit(string $type, string $id)
    {
        $model = $this->getModel($type);
        $data = $model::with('seo')->findOrFail($id);
        $pageName = "Optimize: " . ($data->name ?? $data->title);

        return view('backend.seo.edit', compact('data', 'type', 'pageName'));
    }

    public function update(Request $request, string $type, string $id)
    {
        $model = $this->getModel($type);
        $item = $model::findOrFail($id);
        $validated = $request->validate([
            'meta_title' => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'canonical_tag' => 'nullable|url',
            'meta_robots' => 'required|string',
            'meta_author' => 'nullable|string|max:100',
            'slug' => 'nullable|string|max:150',
            'og_title' => 'nullable|string|max:100',
            'og_description' => 'nullable|string|max:200',
            'og_image' => 'nullable|string',
            'twitter_title' => 'nullable|string|max:100',
            'twitter_description' => 'nullable|string|max:200',
            'twitter_image' => 'nullable|string',
            'schema' => 'nullable|json',
            'indexing_switch' => 'nullable|boolean'
        ]);

        $validated['indexing_switch'] = $request->has('indexing_switch') ? 1 : 0;

        $item->saveSeo($validated);

        return redirect()->back()->with('success', 'SEO configuration successfully published.');
    }
}
