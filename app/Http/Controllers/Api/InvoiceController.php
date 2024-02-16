<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Organizacion;
use App\Services\SunatService;
use App\Traits\SunatTrait;
use Greenter\Report\XmlUtils;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    use SunatTrait;
    public function send(Request $request)
    {
        $request->validate([
            'company' => 'required|array',
            'company.address' => 'required|array',
            'client' => 'required|array',
            'details' => 'required|array',
            'details.*' => 'required|array'
        ]);

        $data = $request->all();

        $company = Organizacion::where('ruc',$data['company']['ruc'])
            ->firstOrFail();

        $this->setTotales($data);
        $this->setLegends($data);
        
        $sunat = new SunatService();  
        $see = $sunat->getSee($company);
        $invoice = $sunat->getInvoice($data);
        $result = $see->send($invoice);
        $response['xml'] = $see->getFactory()->getLastXml();
        $response['hash'] = (new XmlUtils())->getHashSign($response['xml']);
        $response['sunatResponse'] = $sunat->sunatResponse($result,$invoice,$response['xml']);

        $sunat->getHtmlReport($invoice,$company,$response['hash']);        
        $sunat->generatePdfReport($invoice,$company,$response['hash']);
        return response()->json($response,200);
    }

    public function xml(Request $request)
    {
        $request->validate([
            'company' => 'required|array',
            'company.address' => 'required|array',
            'client' => 'required|array',
            'details' => 'required|array',
            'details.*' => 'required|array',
        ]);

        $data = $request->all();

        $company = Organizacion::where('user_id', auth()->id())
                    ->where('ruc', $data['company']['ruc'])
                    ->firstOrFail();

        $sunat = new SunatService();
        $see = $sunat->getSee($company);
        $despatch = $sunat->getDespatch($data);   

        $response['xml'] = $see->getXmlSigned($despatch);
        $response['hash'] = (new XmlUtils())->getHashSign($response['xml']);
        return $response;
    }

    public function pdf(Request $request){
        $request->validate([
            'company' => 'required|array',
            'company.address' => 'required|array',
            'client' => 'required|array',
            'details' => 'required|array',
            'details.*' => 'required|array',
        ]);

        $data = $request->all();

        $this->setTotales($data);
        $this->setLegends($data);

        $sunat = new SunatService();
        
        $invoice = $sunat->getInvoice($data);

        //$sunat->generatePdfReport($invoice);
        
        //return $sunat->getHtmlReport($invoice);        
    }
}
