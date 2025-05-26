<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use Inertia\Inertia;
use function Laravel\Prompts\select;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $filters = $request->only('name', 'email', 'role_id', 'sort', 'direction');

        $query = User::with('role');

        $userRoles = UserRole::all();

        if( !empty($filters['name']) )
            $query->whereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ['%' . $filters['name'] . '%']);

        if( !empty($filters['email']) )
            $query->where('email', 'like', '%' . $filters['email'] . '%');

        if( !empty($filters['role_id']) )
            $query->where('role_id', '=', $filters['role_id'] );

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'asc';

        if( in_array($sort, ['id', 'name', 'email']) )
        {
            if( $sort === 'role_id' )
            {
                $query->join('user_roles', 'users.role_id', '=', 'user_roles.id')
                    ->orderBy('user_roles.name', $direction)
                    ->select('users.*');
            }
            elseif ($sort === 'name') {
                $query->orderByRaw("CONCAT(firstname, ' ', lastname) {$direction}");
            }
            else
            {
                $query->orderBy($sort, $direction);
            }
        }

        $users = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return Inertia::render('Admin/Users/Index', compact('users', 'filters', 'userRoles'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $userRoles = UserRole::all();

        return Inertia::render('Admin/Users/Create', compact('userRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role_id' => 'required|integer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('Admin/Users/Show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userRoles = UserRole::all();

        $user = User::findOrFail($id);
        return Inertia::render('Admin/Users/Edit', compact('user', 'userRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|integer',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->role_id = $request->role_id;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
