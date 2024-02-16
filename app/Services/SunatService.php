<?php

namespace App\Services;

use App\Models\Company as ModelsCompany;
use App\Models\Organizacion;
use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Illuminate\Support\Facades\Storage;
use Greenter\Model\Company\Company;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Driver;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Despatch\Vehicle;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Report\Resolver\DefaultTemplateResolver;

class SunatService
{
    public function getSee($company){
        $certificate = Storage::get($company->cert_path);
        $see = new See();
        $see->setCertificate($certificate);
        $see->setService($company->production ? SunatEndpoints::FE_PRODUCCION : SunatEndpoints::FE_BETA);
        $see->setClaveSOL($company->ruc, $company->sol_user, $company->sol_pass);
        return $see;
    }

    public function getSeeApi($company){
        $api = new \Greenter\Api($company->production ? [
            'auth' => 'https://api-seguridad.sunat.gob.pe/v1',
            'cpe' => 'https://api-cpe.sunat.gob.pe/v1',
          ]:
          [
            'auth' => 'https://gre-test.nubefact.com/v1',
            'cpe' =>  'https://gre-test.nubefact.com/v1'
          ]);
        $certificate = Storage::get($company->cert_path);
        $api->setBuilderOptions([
            'strict_variable' => true,
            'optimizacions' => 0,
            'debug' => true,
            'cache' => false
        ])->setApiCredentials(
            $company->production ? $company->client_id : "test-85e5b0ae-255c-4891-a595-0b98c65c9854",
            $company->production ? $company->client_secret  : "test-Hty/M6QshYvPgItX2P0+Kw=="
        )->setClaveSOL(
            $company->ruc,
            $company->production ? $company->sol_user  : "MODDATOS",
            $company->production ? $company->sol_pass  : "MODDATOS"
        )->setCertificate($certificate);

        return $api;
    }

    public function getInvoice($data){
        return (new Invoice())
            ->setUblVersion($data['ublVersion'] ?? "2.1")
            ->setTipoOperacion($data['tipoOperacion'] ?? null) // Venta - Catalog. 51
            ->setTipoDoc($data['tipoDoc'] ?? null) // Factura - Catalog. 01 
            ->setSerie($data['serie'] ?? null)
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setFechaEmision(new DateTime($data['fechaEmision'] ?? null)) // Zona horaria: Lima
            ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
            ->setTipoMoneda($data['formaPago']['moneda'] ?? null) // Sol - Catalog. 02
            ->setCompany($this->getCompany($data['company'] ))
            ->setClient($this->getClient($data['client']))
            
            //Mto Operaciones
            ->setMtoOperGravadas($data['mtoOperGravadas'])
            ->setMtoOperExoneradas($data['mtoOperExoneradas'])
            ->setMtoOperInafectas($data['mtoOperInafectas'])
            ->setMtoOperExportacion($data['mtoOperExportacion'])
            ->setMtoOperGratuitas($data['mtoOperGratuitas'])

            //Impuestos
            ->setMtoIGV($data['mtoIGV'])
            ->setMtoIGVGratuitas($data['mtoIGVGratuitas'])
            ->setIcbper($data['icbper'])
            ->setTotalImpuestos($data['totalImpuestos'])

            //Totales
            ->setValorVenta($data['valorVenta'])
            ->setSubTotal($data['subTotal'])
            ->setRedondeo($data['redondeo'])
            ->setMtoImpVenta($data['mtoImpVenta'])

            //Productos
            ->setDetails($this->getDetails($data['details']))

            //Leyendas
            ->setLegends($this->getLegends($data['legends']));
            
    }

    public function getNote($data){
        return (new Note())
            ->setUblVersion($data['ublVersion'] ?? "2.1")
            ->setTipoDoc($data['tipoDoc'] ?? null) // Factura - Catalog. 01 
            ->setSerie($data['serie'] ?? null)
            ->setCorrelativo($data['correlativo'] ?? null)
            ->setFechaEmision(new DateTime($data['fechaEmision'] ?? null)) // Zona horaria: Lima
            ->setTipDocAfectado($data['tipDocAfectado'] ?? null)
            ->setNumDocfectado($data['numDocfectado'] ?? null)
            ->setCodMotivo($data['codMotivo'] ?? null)
            ->setDesMotivo($data['desMotivo'] ?? null)
            ->setTipoMoneda($data['formaPago']['moneda'] ?? null) // Sol - Catalog. 02
            ->setCompany($this->getCompany($data['company'] ))
            ->setClient($this->getClient($data['client']))
            
            //Mto Operaciones
            ->setMtoOperGravadas($data['mtoOperGravadas'])
            ->setMtoOperExoneradas($data['mtoOperExoneradas'])
            ->setMtoOperInafectas($data['mtoOperInafectas'])
            ->setMtoOperExportacion($data['mtoOperExportacion'])
            ->setMtoOperGratuitas($data['mtoOperGratuitas'])

            //Impuestos
            ->setMtoIGV($data['mtoIGV'])
            ->setMtoIGVGratuitas($data['mtoIGVGratuitas'])
            ->setIcbper($data['icbper'])
            ->setTotalImpuestos($data['totalImpuestos'])

            //Totales
            ->setValorVenta($data['valorVenta'])
            ->setSubTotal($data['subTotal'])
            ->setRedondeo($data['redondeo'])
            ->setMtoImpVenta($data['mtoImpVenta'])

            //Productos
            ->setDetails($this->getDetails($data['details']))

            //Leyendas
            ->setLegends($this->getLegends($data['legends']));
            
    }

    public function getDespatch($data)
    {
        return(new Despatch)
        ->setVersion($data['version'] ?? '2022')
        ->setTipoDoc($data['tipoDoc'] ?? '09')
        ->setSerie($data['serie'] ?? null)
        ->setCorrelativo($data['correlativo'] ?? null)
        ->setFechaEmision(new DateTime($data['FechaEmision'] ?? null))
        ->setCompany($this->getCompany($data['company']) ?? null)
        ->setDestinatario($this->getClient($data['destinatario']) ?? null)
        ->setEnvio($this->getEnvio($data['envio']) ?? null)
        ->setDetails($this->getDespatchDetails($data['details']) ?? null);
    }

    public function getCompany($company)
    {
        return (new Company())
            ->setRuc($company['ruc'] ?? null)
            ->setRazonSocial($company['razonSocial'] ?? null)
            ->setNombreComercial($company['nombreComercial'] ?? null)
            ->setAddress($this->getAddress($company['address']) ?? null);
    }

    public function getClient($client)
    {
        return (new Client())
            ->setTipoDoc($client['tipoDoc'] ?? null) // DNI - Catalog. 06
            ->setNumDoc($client['numDoc'] ?? null)
            ->setRznSocial($client['rznSocial'] ?? null);
    }

    public function getAddress($address)
    {
        return (new Address())
            ->setUbigueo($address['ubigueo'] ?? null)
            ->setDepartamento($address['departamento'] ?? null)
            ->setProvincia($address['provincia'] ?? null)
            ->setDistrito($address['distrito'] ?? null)
            ->setUrbanizacion($address['urbanizacion'] ?? null)
            ->setDireccion($address['direccion'] ?? null)
            ->setCodLocal($address['codLocal'] ?? null); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

    }

    public function getDetails($details)
    {
        $green_details = [];

        foreach ($details as $detail) {        
            $green_details[] = (new SaleDetail())
                ->setCodProducto($detail['codProducto'] ?? null)
                ->setUnidad($detail['unidad'] ?? null) // Unidad - Catalog. 03
                ->setCantidad($detail['cantidad'] ?? null)
                ->setMtoValorUnitario($detail['mtoValorUnitario'] ?? null)
                ->setDescripcion($detail['descripcion'] ?? null)
                ->setMtoBaseIgv($detail['mtoBaseIgv'] ?? null)
                ->setPorcentajeIgv($detail['porcentajeIgv'] ?? null) // 18%
                ->setIgv($detail['igv'] ?? null)
                ->setFactorIcbper($detail['factorIcbper'] ?? null) // 0.3%
                ->setIcbper($detail['icbper'] ?? null)
                ->setTipAfeIgv($detail['tipAfeIgv'] ?? null) // Gravado Op. Onerosa - Catalog. 07
                ->setTotalImpuestos($detail['totalImpuestos'] ?? null) // Suma de impuestos en el detalle
                ->setMtoValorVenta($detail['mtoValorVenta'] ?? null)
                ->setMtoPrecioUnitario($detail['mtoPrecioUnitario'] ?? null);
        }
        return $green_details;
    }

    public function getDespatchDetails($details)
    {
        $green_details = [];

        foreach ($details as $detail) {        
            $green_details[] = (new DespatchDetail())
                ->setUnidad($detail['unidad'] ?? null) // Unidad - Catalog. 03
                ->setCantidad($detail['cantidad'] ?? null)
                ->setDescripcion($detail['descripcion'] ?? null)
                ->setCodigo($detail['codigo'] ?? null);
        }
        return $green_details;
    }

    public function getLegends($legends)
    {
        $green_legends = [];

        foreach ($legends as $legend) {
            $green_legends[] = (new Legend())
                ->setCode($legend['code'] ?? null) // Monto en letras - Catalog. 52
                ->setValue($legend['value'] ?? null);
        }

        return $green_legends;
    }

    public function getEnvio($data){
        $shipment =  (new Shipment)
        ->setCodTraslado($data['codTraslado'] ?? null) // Cat. 18 
        ->setModTraslado($data['modTraslado'] ?? null) // Cat. 19 
        ->setFecTraslado(new DateTime($data['fecTraslado'] ?? null))
        ->setCodTraslado($data['codTraslado'] ?? null)
        ->setPesoTotal($data['pesoTotal'] ?? null)
        ->setUndPesoTotal($data['undPesoTotal'] ?? null)
        ->setLlegada(new Direction($data['llegada']['ubigueo'],$data['llegada']['direccion'] ?? null))
        ->setPartida(new Direction($data['partida']['ubigueo'],$data['partida']['direccion'] ?? null));
        if($data['modTraslado'] == '01'){
            $shipment->setTransportista($this->getTransportista($data['transportista']) ?? null);
        }
        if($data['modTraslado'] == '02'){
            $shipment->setVehiculo($this->getVehiculo($data['vehiculos']) ?? null)
            ->setChoferes($this->getChoferes($data['choferes']) ?? null);
        }

        return $shipment;
    }

    public function getVehiculo($vehiculos){

        $vehiculos = collect($vehiculos);
        $secundarios = [];

        foreach ($vehiculos->slice(1) as $item) {
            $secundarios[] = (new Vehicle())
                    ->setPlaca($item['placa'] ?? null);
        }
        return (new Vehicle())
               ->setPlaca($vehiculos->first()['placa'] ?? null)
               ->setSecundarios($secundarios);
    }

    public function getChoferes($choferes){
        $choferes = collect($choferes);

        $drivers = [];
        $drivers [] = (new Driver)
            ->setTipo('Principal')
            ->setTipoDoc($choferes->first()['tipoDoc'] ?? null)
            ->setNroDoc($choferes->first()['nroDoc'] ?? null)
            ->setLicencia($choferes->first()['licencia'] ?? null)
            ->setNombres($choferes->first()['nombres'] ?? null)
            ->setApellidos($choferes->first()['apellidos'] ?? null);

        foreach ($choferes->slice(1) as $item) {
            $drivers [] = (new Driver)
            ->setTipo('Secundario')
            ->setTipoDoc($item['tipoDoc'] ?? null)
            ->setNroDoc($item['nroDoc'] ?? null)
            ->setLicencia($item['licencia'] ?? null)
            ->setNombres($item['nombres'] ?? null)
            ->setApellidos($item['apellidos'] ?? null);
        }   
        return $drivers; 
    }

    public function getTransportista($data){
        return(new Transportist)
        ->setTipoDoc($data['tipoDoc'] ?? null)
        ->setNumDoc($data['numDoc'] ?? null)
        ->setRznSocial($data['rznSocial'] ?? null)
        ->setNroMtc($data['nroMtc'] ?? null);
    }
    public function sunatResponse($result,$invoice,$xml){
        // Guardar XML firmado digitalmente.
        //file_put_contents($invoice->getName().'.xml', $xml);
        Storage::put('invoices/' . $invoice->getName() . '.xml', $xml);

        $response['succes'] = $result->isSuccess();
        // Verificamos que la conexión con SUNAT fue exitosa.
        if (!$result->isSuccess()){
            // Mostrar error al conectarse a SUNAT.
            $response['error'] = [
                'code' => $result->getError()->getCode(),
                'message' => $result->getError()->getMessage()
            ];            
            return $response;
        }
        $response['cdrZip'] = base64_encode($result->getCdrZip());
        
        // Guardamos el CDR
        //file_put_contents('R-'.$invoice->getName().'.zip', $result->getCdrZip());
        Storage::put('invoices/R-' . $invoice->getName() . '.zip',$result->getCdrZip());

        $cdr = $result->getCdrResponse();

        $response['cdrResponse'] = [
            'code' => (int)$cdr->getCode(),
            'description' => $cdr->getDescription(),
            'notes' => $cdr->getNotes(),
        ];        
        return $response;
    }

    public function getHtmlReport($invoice,$company,$hash){
        $report = new HtmlReport();

        $resolver = new DefaultTemplateResolver();
        $report->setTemplate($resolver->getTemplate($invoice));

        /*$ruc = $invoice->getCompany()->getRuc();
        $company = Organizacion::where('ruc', $ruc)
        ->where('user_id',auth()->id())
        ->first();*/

        $params = [
            'system' => [
                'logo' => Storage::get($company->logo_path), // Logo de Empresa
                'hash' => $hash, // Valor Resumen 
            ],
            'user' => [
                'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                'extras'     => [
                    // Leyendas adicionales
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'     ],
                    ['name' => 'VENDEDOR'         , 'value' => 'GITHUB SELLER'],
                ],
                'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];
        Storage::put('invoices/' . $invoice->getName() . '.html', $report->render($invoice, $params));
        //return $report->render($invoice, $params);
    }

    public function generatePdfReport($invoice,$company,$hash)
    {
        $htmlReport = new HtmlReport();

        $resolver = new DefaultTemplateResolver();
        $htmlReport->setTemplate($resolver->getTemplate($invoice));

        $report = new PdfReport($htmlReport);
        // Options: Ver mas en https://wkhtmltopdf.org/usage/wkhtmltopdf.txt
        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);

        $report->setBinPath(env('WKHTMLTODCF_PATH'));

        /*$ruc = $invoice->getCompany()->getRuc();
        $company = Organizacion::where('ruc', $ruc)
        ->where('user_id',auth()->id())
        ->first();*/

        $params = [
            'system' => [
                'logo' => Storage::get($company->logo_path), // Logo de Empresa
                'hash' => $hash, // Valor Resumen 
            ],
            'user' => [
                'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                'extras'     => [
                    // Leyendas adicionales
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'     ],
                    ['name' => 'VENDEDOR'         , 'value' => 'GITHUB SELLER'],
                ],
                'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];
        $pdf = $report->render($invoice, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Errorsss: '.$error;
            return;
        }
        
        //file_put_contents('invoice.pdf', $pdf);
        Storage::put('invoices/' . $invoice->getName() . '.pdf', $pdf);
    }    
}