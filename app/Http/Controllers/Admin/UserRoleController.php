<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only('name', 'sort', 'direction');

        $query = UserRole::query();

        if( !empty($filters['name']) )
            $query->where('name', 'like', '%' . $filters['name'] . '%');

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';
        $userRoles = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return Inertia::render('Admin/UserRoles/Index', compact( 'userRoles', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/UserRoles/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        UserRole::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.user-roles.index')->with('success', 'User Role added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $userRole)
    {
        $userRoles = UserRole::findOrFail($userRole->id);

        return Inertia::render('Admin/UserRoles/Show', compact('userRoles'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRole $userRole)
    {
        $userRole = UserRole::findOrFail($userRole->id);

        return Inertia::render('Admin/UserRoles/Edit', compact('userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRole $userRole)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $userRole->name = $request->name;
        $userRole->save();

        return redirect()->route('admin.user-roles.index')->with('success', 'User Role added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserRole $userRole)
    {
        $userRole = UserRole::findOrFail($userRole->id);
        $userRole->delete();
    }
}
