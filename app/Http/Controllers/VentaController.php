<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use App\Models\Organizacion;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::paginate(5);
        return Inertia::render('Ventas/Index',compact('ventas'));
    }

    public function create()
    {
        $user = User::where('id', '=',  auth()->user()->id)->get();
        $organizacion = Organizacion::where('id',$user[0]->organizacion_id)
        ->firstOrFail();
        return Inertia::render('Ventas/Create',compact('organizacion'));
    }

    public function store(Request $request)
    {
        //return $request->all();
        $data = $request->validate([
            'ruc' => 'required',
            'razonSocial'   => 'required',
            'tipoMoneda'  => 'required',
            'serie'  => 'required',
            'correlativo'  => 'required',
            'direccion' => 'required',
            'fechaEmision'   => 'required',
            'fechaVencimiento'  => 'required',
            'subTotal'  => 'required',
            'mtoIGV'  => 'required',
            'mtoImpVenta'  => 'required',
            'productos' => 'required',
            'tipoOperacion' => 'required',
            'tipoDoc' => 'required',
            'formaPago' => 'required',
            'mtoOperGravadas' => 'required',
            'mtoOperExoneradas' => 'required',
            'mtoOperInafectas' => 'required',
            'mtoOperExportacion' => 'required',
            'mtoOperGratuitas' => 'required',
            'mtoIGVGratuitas' => 'required',
            'icbper' => 'required',
            'totalImpuestos' => 'required',
            'valorVenta' => 'required',
            'redondeo' => 'required',
            'legends' => 'required',
            //'XML' => 'required',
            //'CDR' => 'required',
            //'cdrResponse' => 'required',
            'estadoSunat' => 'required',             
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
        $venta  = Venta::create($data);
        
        $ventas = Venta::paginate(5);
        return Inertia::render('Ventas/Index',compact('ventas'));
    }

    public function show(Venta $venta)
    {
        //
    }

    public function edit(Venta $venta)
    {
        $user = User::where('id', '=',  auth()->user()->id)->get();
        $organizacion = Organizacion::where('id',$user[0]->organizacion_id)
        ->firstOrFail();
        return Inertia::render('Ventas/Edit',compact('venta','organizacion'));
    }

    public function update(UpdateVentaRequest $request, Venta $venta)
    {
        
        //return $request->all();
        $data = $request->validate([
            'ruc' => 'required',
            'razonSocial'   => 'required',
            'tipoMoneda'  => 'required',
            'serie'  => 'required',
            'correlativo'  => 'required',
            'direccion' => 'required',
            'fechaEmision'   => 'required',
            'fechaVencimiento'  => 'required',
            'subTotal'  => 'required',
            'mtoIGV'  => 'required',
            'mtoImpVenta'  => 'required',
            'productos' => 'required',
            'tipoOperacion' => 'required',
            'tipoDoc' => 'required',
            'formaPago' => 'required',
            'mtoOperGravadas' => 'required',
            'mtoOperExoneradas' => 'required',
            'mtoOperInafectas' => 'required',
            'mtoOperExportacion' => 'required',
            'mtoOperGratuitas' => 'required',
            'mtoIGVGratuitas' => 'required',
            'icbper' => 'required',
            'totalImpuestos' => 'required',
            'valorVenta' => 'required',
            'redondeo' => 'required',
            'legends' => 'required',
            //'XML' => 'required',
            //'CDR' => 'required',
            //'cdrResponse' => 'required',
            'estadoSunat' => 'required',             
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
        
        $venta->update($data);
        
        $ventas = Venta::paginate(5);
        //return redirect()->to('Ventas/Index');
        return redirect()->route('ventas.index');
        //return Inertia::render('Ventas/Index',compact('ventas'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();
        $ventas = Venta::paginate(5);
        return redirect()->route('ventas.index');
        //return Inertia::render('Ventas/Index',compact('ventas'));
    }

    public function html(Venta $venta)
    {
        $organizacion = Organizacion::where('id',$venta['organizacion_id'])->firstOrFail();
        $name = $organizacion->ruc."-".$venta->tipoDoc."-".$venta->serie."-".$venta->correlativo.".html";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }

    public function pdf(Venta $venta)
    {
        $organizacion = Organizacion::where('id',$venta['organizacion_id'])->firstOrFail();
        $name = $organizacion->ruc."-".$venta->tipoDoc."-".$venta->serie."-".$venta->correlativo.".pdf";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }

    public function xml(Venta $venta)
    {
        $organizacion = Organizacion::where('id',$venta['organizacion_id'])->firstOrFail();
        $name = $organizacion->ruc."-".$venta->tipoDoc."-".$venta->serie."-".$venta->correlativo.".xml";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }

    public function cdr(Venta $venta)
    {
        $organizacion = Organizacion::where('id',$venta['organizacion_id'])->firstOrFail();
        $name = "R-".$organizacion->ruc."-".$venta->tipoDoc."-".$venta->serie."-".$venta->correlativo.".zip";
        $filePath = storage_path('app\public\invoices/'.$name);        
        return response()->download($filePath);         
    }
}
