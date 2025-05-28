<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FooterLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = request()->only(['name', 'order', 'search', 'order']);

        $query = FooterLink::query();
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }
        if (!empty($filters['order'])) {
            $query->orderBy('order', $filters['order']);
        }

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        
        $footerLinks = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        $parentFooterLinks = FooterLink::whereNull('parent_id')->get();
        return Inertia::render('Admin/FooterLinks/Index', compact('footerLinks', 'filters', 'parentFooterLinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentFooterLinks = FooterLink::whereNull('parent_id')->get();

        return Inertia::render('Admin/FooterLinks/Create', compact('parentFooterLinks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'parent_id' => 'nullable|exists:footer_links,id',
        ]);

        FooterLink::create($validated);

        return redirect()->route('admin.footer-links.index')->with('success', 'Footer link created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FooterLink $footerLink)
    {
        return Inertia::render('Admin/FooterLinks/Show', compact('footerLink'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FooterLink $footerLink)
    {
        $parentFooterLinks = FooterLink::whereNull('parent_id')->get();

        return Inertia::render('Admin/FooterLinks/Edit', compact('footerLink', 'parentFooterLinks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FooterLink $footerLink)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'order' => 'required|integer',
            'parent_id' => 'nullable|exists:footer_links,id',
        ]);

        $footerLink->update($validated);

        return redirect()->route('admin.footer-links.index')->with('success', 'Footer link updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FooterLink $footerLink)
    {
        $footerLink->delete();

        return redirect()->route('admin.footer-links.index')->with('success', 'Footer link deleted successfully.');
    }

    public function getFooterLinks(): JsonResponse
    {
        return response()->json(
            FooterLink::with('children')->whereNull('parent_id')->orderBy('order')->get()
        );
    }
}
