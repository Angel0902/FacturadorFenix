<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\password;

class UserController extends Controller
{
    public function __construct()
    {   
        $this->middleware('auth');
    }
    public function index()
    {
        $users = User::with(['organizacion','roles'])->paginate();
        return Inertia::render('User/Index',compact('users'));
    }
    public function create()
    {
        $roles = Role::all();
        $organizacions = Organizacion::all();
        return Inertia::render('User/Create',compact('organizacions','roles'));
    }
    public function store(Request $request)
    {   
        $request['password'] = Hash::make($request['password']);        
        
        $data = $request->validate([
            'name' => 'required',
            'email'   => 'required',
            'organizacion_id'  => 'required',
            'password'  => 'required',
            'rol'  => 'required',
        ]);
        $user  = User::create($data);
        $user->roles()->attach($request->rol);

        $users = User::with(['organizacion','roles'])->paginate();
        return Inertia::render('User/Index',compact('users'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $organizacions = Organizacion::all();
        $user->hasRole(Role::all());
        return Inertia::render('User/Edit',compact('user','organizacions','roles'));
    }
    public function update(Request $request, User $user)
    {
        //return $request;
        $data = $request->validate([
            'name' => 'required',
            'email'   => 'required',
            'organizacion_id'  => 'required'
        ]);
        
        $user->update($data);
        //$user->roles()->attach($request->rol);

        //$user->roles()->attach($request->roles[0]);
        //$user->roles()->sync($request->roles[0]);
        //$user->syncRoles($request->roles[0]);
        
        $users = User::with(['organizacion','roles'])->paginate();
        return Inertia::render('User/Index',compact('users'));
    }

    /**
     * muestra the specified resource from storage.
     */
    public function show(string $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        $users = User::with(['organizacion','roles'])->paginate();
        return Inertia::render('User/Index',compact('users'));
    }
}
