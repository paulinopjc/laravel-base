<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMSPage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CMSPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['title', 'slug', 'is_published', 'sort', 'direction']);

        $query = CMSPage::query();

        if( !empty($filters['title']) )
            $query->where('title', 'like', '%' . $filters['title'] . '%');

        if(  !empty($filters['slug']) )
            $query->where('slug', 'like', '%' . $filters['slug'] . '%');

        if( !empty($filters['is_published']) )
            $query->where('is_published', $filters['is_published']);

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';

        $cmsPages = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return Inertia::render('Admin/CmsPages/Index', compact('cmsPages', 'filters'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/CmsPages/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'content' => 'string',
            'is_published' => 'required|numeric'
        ]);

        CMSPage::create($request->all());

        return redirect()->route('admin.cms-pages.index')->with('success', 'CMS page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CMSPage $cmsPage, $slug)
    {
        if ($cmsPage->slug !== $slug) {
            return redirect()->route('cms.page', ['cmsPage' => $cmsPage->id, 'slug' => $cmsPage->slug]);
        }

        $page = CmsPage::where('slug', $cmsPage->slug)
            ->where('is_published', 1)
            ->first();

        if (! $page) {
            abort(404);
        }

        return Inertia::render('Frontend/CmsPage', [
            'page' => $page,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CMSPage $cmsPage)
    {
        return Inertia::render('Admin/CmsPages/Edit', [
            'cmsPage' => $cmsPage
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CMSPage $cmsPage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'string',
            'is_published' => 'required|numeric'
        ]);

        $cmsPage->update($request->all());

        return redirect()->route('admin.cms-pages.index')->with('success', 'CMS page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CMSPage $cmsPage)
    {
        $cmsPage->delete();

        return redirect()->route('admin.cms-pages.index')->with('success', 'CMS page deleted successfully.');
    }

    public function showBySlug($slug)
    {
        $page = CmsPage::where('slug', $slug)
            ->where('is_published', 1)
            ->first();

        if (! $page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json([
            'id' => $page->id,
            'title' => $page->title,
            'slug' => $page->slug,
            'content' => $page->content,
        ]);
    }

}
