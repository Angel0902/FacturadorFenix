<?php

namespace App\Http\Controllers;

use App\Models\Organizacion;
use App\Http\Requests\StoreOrganizacionRequest;
use App\Http\Requests\UpdateOrganizacionRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

class OrganizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $organizacions = Organizacion::paginate();
        return Inertia::render('Organizacions/Index',compact('organizacions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Organizacions/Create');
    }

    /**
     * Store a newly created resource in storage.
     * StoreOrganizacionRequest $request
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'razon_social' => 'required|string',
            'ruc' => [
                'required',
                'string',
                'regex:/^(10|20)\d{9}$/',
                'unique:organizacions,ruc'
            ],
            'direccion' => 'required|string',
            'logo' => 'nullable|image',
            'sol_user' => 'required|string',
            'sol_pass' =>  'required|string',
            // extension .pem
            'cert' =>  'required|File|mimes:pem,txt',
            'client_id' =>  'nullable|string',
            'client_secret' =>  'nullable|string',
            'production' =>  'nullable|boolean' 
        ]);

        if($request->hasFile('logo')){
            $data['logo_path'] = $request->file('logo')->store('logos');
        }        

        //if($request->hasFile('cert')){
            $data['cert_path'] = $request->file('cert')->store('certs');
        //}
        Organizacion::create($data);
        
        $organizacions = Organizacion::paginate();
        return Inertia::render('Organizacions/Index',compact('organizacions'));
    }

    public function show(Organizacion $organizacion)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Organizacion $organizacion)
    {
        return Inertia::render('Organizacions/Edit',compact('organizacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organizacion $organizacion)
    {
        $data = $request->validate([
            'razon_social' => 'required|string',
            'direccion' => 'required|string',
            'logo' => 'nullable|image',
            'sol_user' => 'required|string',
            'sol_pass' =>  'required|string',
            // extension .pem
            'client_id' =>  'nullable|string',
            'client_secret' =>  'nullable|string',
            'production' =>  'nullable|boolean' 
        ]);

        if($request->hasFile('logo')){
            $data['logo_path'] = $request->file('logo')->store('logos');
        }        

        if($request->hasFile('cert')){
            $data['cert_path'] = $request->file('cert')->store('certs');
        }
        $organizacion->update($data);
        
        $organizacions = Organizacion::paginate();
        return Inertia::render('Organizacions/Index',compact('organizacions'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organizacion $organizacion)
    {
        $organizacion->delete();
        $organizacions = Organizacion::paginate();
        return Inertia::render('Organizacions/Index',compact('organizacions'));
    }
}
