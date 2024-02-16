<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Http\Requests\StoreNotaRequest;
use App\Http\Requests\UpdateNotaRequest;
use App\Models\Organizacion;
use App\Models\User;
use Inertia\Inertia;
use Luecano\NumeroALetras\NumeroALetras;

class NotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notas = Nota::paginate();
        return Inertia::render('Notas/Index',compact('notas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('id', '=',  auth()->user()->id)->get();
        $organizacion = Organizacion::where('id',$user[0]->organizacion_id)
        ->firstOrFail();
        return Inertia::render('Notas/Create',compact('organizacion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNotaRequest $request)
    {
        //return $request;
        $data = $request->validate([
            'ruc' => 'required',
            'razonSocial' => 'required',
            'codMotivo' => 'required',
            'desMotivo' => 'required',
            'tipoMoneda' => 'required',
            'serie' => 'required',
            'correlativo' => 'required',
            'tipDocAfectado' => 'required',
            'numDocfectado' => 'required',
            'direccion' => 'required',
            'fechaEmision' => 'required',
            'fechaVencimiento' => 'required',
            'subTotal' => 'required', 
            'mtoIGV' => 'required',
            'productos' => 'required',
            'valorVenta' => 'required',
            'tipoDoc' => 'required',
            'mtoImpVenta' => 'required',
            'mtoOperGravadas' => 'required',
            'mtoOperExoneradas' => 'required',
            'mtoOperInafectas' => 'required',
            'mtoOperExportacion' => 'required',
            'mtoOperGratuitas' => 'required',
            'mtoIGVGratuitas' => 'required',
            'icbper' => 'required',
            'totalImpuestos' => 'required',
            'redondeo' => 'required',
            'legends' => 'required'
            
        ]);

        if($request->XML!='' && $request->XML!=null){
            $data['XML'] = $request->XML;
            $data['CDR'] = $request->CDR;
            $data['cdrResponse'] = $request->cdrResponse;
        }

        $formatter = new NumeroALetras();
        $data['legends'] = json_encode([['code' => '1000','value' => $formatter->toInvoice($data['mtoImpVenta'], 2, 'SOLES')]]);

        $user = User::where('id', '=',  auth()->user()->id)->get();
        $data['user_id'] = auth()->user()->id;
        $data['organizacion_id'] = $user[0]->organizacion_id;
        $data['productos'] = json_encode($data['productos']);
        
        $nota  = Nota::create($data);
        
        $notas = Nota::paginate(5);
        return Inertia::render('Notas/Index',compact('notas'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Nota $nota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nota $nota)
    {
        $user = User::where('id', '=',  auth()->user()->id)->get();
        $organizacion = Organizacion::where('id',$user[0]->organizacion_id)
        ->firstOrFail();
        return Inertia::render('Notas/Edit',compact('nota','organizacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNotaRequest $request, Nota $nota)
    {
        
        $data = $request->validate([
            'ruc' => 'required',
            'razonSocial' => 'required',
            'codMotivo' => 'required',
            'desMotivo' => 'required',
            'tipoMoneda' => 'required',
            'serie' => 'required',
            'correlativo' => 'required',
            'tipDocAfectado' => 'required',
            'numDocfectado' => 'required',
            'direccion' => 'required',
            'fechaEmision' => 'required',
            'fechaVencimiento' => 'required',
            'subTotal' => 'required', 
            'mtoIGV' => 'required',
            'productos' => 'required',
            'valorVenta' => 'required',
            'tipoDoc' => 'required',
            'mtoImpVenta' => 'required',
            'mtoOperGravadas' => 'required',
            'mtoOperExoneradas' => 'required',
            'mtoOperInafectas' => 'required',
            'mtoOperExportacion' => 'required',
            'mtoOperGratuitas' => 'required',
            'mtoIGVGratuitas' => 'required',
            'icbper' => 'required',
            'totalImpuestos' => 'required',
            'redondeo' => 'required',
            'legends' => 'required'
            
        ]);
        //return $request->XML;
        
        if($request->XML!='' && $request->XML!=null){
            $data['XML'] = $request->XML;
            $data['CDR'] = $request->CDR;
            $data['cdrResponse'] = $request->cdrResponse;
            $data['estadoSunat'] = $request->estadoSunat;
        }
        //return $data;
        $formatter = new NumeroALetras();
        $data['legends'] = json_encode([['code' => '1000','value' => $formatter->toInvoice($data['mtoImpVenta'], 2, 'SOLES')]]);

        $user = User::where('id', '=',  auth()->user()->id)->get();
        $data['user_id'] = auth()->user()->id;
        $data['organizacion_id'] = $user[0]->organizacion_id;
        $data['productos'] = json_encode($data['productos']);
        
        $nota->update($data);
        
        //$notas = Nota::paginate(5);
        //return redirect()->to('Ventas/Index');
        return redirect()->route('notas.index');
        //return Inertia::render('Notas/Index',compact('notas'));
    }

    public function destroy(Nota $nota)
    {
        $nota->delete();
        //$ventas = Nota::paginate(5);
        return redirect()->route('notas.index');
        //return Inertia::render('Ventas/Index',compact('ventas'));
    }

    public function html(Nota $nota)
    {
        $organizacion = Organizacion::where('id',$nota['organizacion_id'])->firstOrFail();
        $name = $organizacion->ruc."-".$nota->tipoDoc."-".$nota->serie."-".$nota->correlativo.".html";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }

    public function pdf(Nota $nota)
    {
        $organizacion = Organizacion::where('id',$nota['organizacion_id'])->firstOrFail();
        $name = $organizacion->ruc."-".$nota->tipoDoc."-".$nota->serie."-".$nota->correlativo.".pdf";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }

    public function xml(Nota $nota)
    {
        $organizacion = Organizacion::where('id',$nota['organizacion_id'])->firstOrFail();
        $name = $organizacion->ruc."-".$nota->tipoDoc."-".$nota->serie."-".$nota->correlativo.".xml";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }

    public function cdr(Nota $nota)
    {
        $organizacion = Organizacion::where('id',$nota['organizacion_id'])->firstOrFail();
        $name = "R-".$organizacion->ruc."-".$nota->tipoDoc."-".$nota->serie."-".$nota->correlativo.".zip";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }
}