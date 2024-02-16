<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import TextInput from '@/Components/TextInput.vue';
    import ValidationsErrors from '@/Components/ValidationsErrors.vue';
    import FormProducto from '@/Components/FormProducto.vue';
    import TableProducts from '@/Components/TableProducts.vue'; 
</script>
<template>
  <app-layout title="Usuarios">
        
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Facturas
            </h2>
        </template>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ValidationsErrors/>
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">            
                <h1 class="text-3xl font-semibold text-gray-700 mb-3">
                    <span class="text-indigo-500" >Facturas / </span> Crear
                </h1>
                
                <div class="bg-white px-6 py-8 rounded-lg shadow">
                    
                    <div class="grid grid-cols-4 gap-6" >
                        <div>
                            <label >
                                Serie
                                <TextInput v-model="venta.serie" type="text" class="w-full" maxlength="4"/>    
                            </label>  
                        </div>
                        <div>
                            <label >
                                Correlativo
                                <TextInput v-model="venta.correlativo" type="Number" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label>
                                Fecha de Emisión
                                <TextInput v-model="venta.fechaEmision" type="datetime-local" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label>
                                Fecha de Vencimiento
                                <TextInput v-model="venta.fechaVencimiento" type="datetime-local" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                RUC
                                <TextInput v-model="venta.ruc" type="text" class="w-full" maxlength="11" />    
                            </label>
                        </div>
                        <div class="col-span-2">
                            <label >
                                Razon Social
                                <TextInput v-model="venta.razonSocial" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Moneda
                                <select v-model="venta.tipoMoneda" class="w-full rounded" >
                                    <option value="PEN" >SOL</option>
                                    <option value="USD"  >USD</option>
                                </select>   
                            </label>
                        </div>
                        <div class="col-span-4">
                            <label >
                                Direccion
                                <TextInput v-model="venta.direccion" type="text" class="w-full"/>    
                            </label>
                        </div>
                        
                        <!-- <div>
                            <label >
                                Tipo de Operación
                                <select v-model="user.organizacion_id" class="w-full rounded" >
                                    <option value="" selected disabled>- Seleccione una empresa - </option>
                                    <option v-for="organizacion in organizacions" :key="organizacion.id" :value="organizacion.id">
                                        {{ organizacion.nombre }}
                                    </option>
                                </select>
                            </label>
                        </div> -->
                        
                        
                    </div>
                    
                    <form-producto  @add-producto="agregarProducto"  />
                    <br>
                    <table-products :productos="venta.productos" v-model="venta.productos" @delete-producto="eliminarProducto"  />
                    <br>    
                    <div class="bg-white rounded-lg shadow"> 
                    <div class="grid grid-cols-5 gap-6" >
                        <div>
                            <label >
                                SubTotal
                                <TextInput v-model="venta.mtoOperGravadas" type="text" class="w-full" readonly/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                IGV
                                <TextInput v-model="venta.mtoIGV" type="text" class="w-full" readonly/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Total
                                <TextInput v-model="venta.subTotal" type="text" class="w-full" readonly/>    
                            </label>
                        </div>
                        <div>
                            <button @click="store" class="bg-blue-500 hover:bg-blue-700 text-white font-bold w-full mt-6 py-2 px-4 rounded">
                                Guardar 
                            </button>
                        </div>
                        <div>
                            <button @click="cancelar" class="bg-red-500 hover:bg-blue-700 text-white font-bold w-full mt-6 py-2 px-4 rounded">
                                Cancelar
                            </button>
                        </div>
                    </div>
                            
                    </div>
                </div>
                
            </div>
        </div>
        
    </app-layout>
</template>

<script>
export default {
    components: {
        TableProducts,
    },
    data(){
        return {
            venta:{
                ruc:'',
                razonSocial:'',
                tipoMoneda:'PEN',
                serie:'',
                correlativo:'',
                direccion:'',
                fechaEmision: new Date().getFullYear()+'-'+("0" + (new Date().getMonth())+1).slice(-2)+ "-" +("0" + new Date().getDate()).slice(-2)+' ' + new Date().toLocaleTimeString('it-IT'),
                fechaVencimiento: new Date().getFullYear()+'-'+("0" + (new Date().getMonth()+1)).slice(-2)+ "-" +("0" + new Date().getDate()).slice(-2)+' ' + new Date().toLocaleTimeString('it-IT'),
                subTotal:0,
                mtoIGV:0,
                mtoImpVenta:'0',
                tipoOperacion:'0101',
                tipoDoc:'01',
                formaPago:'Contado',
                mtoOperGravadas : 0,
                mtoOperExoneradas:0,
                mtoOperInafectas:0,
                mtoOperExportacion: 0,
                mtoOperGratuitas: 0,
                mtoIGVGratuitas:0,
                icbper:0,
                totalImpuestos:0,
                valorVenta:0,
                redondeo:0,
                legends:'soles',
                productos: [],
                cdrResponse:'',
                XML:'',
                CDR:'',
                estadoSunat:'0'
            },
            
        }
    },
    props:{
        organizacion:Object
    },
    methods:{
        envioSunat(){
            let data = JSON.stringify({
            "ublVersion": "2.1",
            "tipoDoc": "01",
            "tipoOperacion": "0101",
            "serie": this.venta.serie,
            "correlativo": this.venta.correlativo,
            "fechaEmision": this.venta.fechaEmision,
            "formaPago": {
                "moneda": this.venta.tipoMoneda,
                "tipo": "Contado"
            },
            "tipoMoneda":this.venta.tipoMoneda,
            "company": {
                "ruc": this.organizacion.ruc,
                "razonSocial": this.organizacion.razon_social,
                "nombreComercial": this.organizacion.razon_social,
                "address": {
                "ubigueo": "150101",
                "departamento": "LIMA",
                "provincia": "LIMA",
                "distrito": "LIMA",
                "urbanizacion": "-",
                "direccion": this.organizacion.direccion,
                "codLocal": "0000"
                }
            },
            "client": {
                "tipoDoc": "6",
                "numDoc": this.venta.ruc,
                "rznSocial": this.venta.razonSocial
            },
            "details": this.venta.productos
            });
            //console.log(data);
            let token = JSON.parse( localStorage.getItem('token') );
            let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: 'http://facturadorfenix/api/invoices/send',
            headers: { 
                'Accept': 'application/json', 
                'Content-Type': 'application/json', 
                'Authorization': 'Bearer ' + token
            },
            data : data
            };

            axios.request(config)
            .then((response) => {
                let resp = response.data;     
                this.venta.XML = resp.xml;
                if(resp.sunatResponse.succes){
                    this.venta.CDR = resp.sunatResponse.cdrZip;
                    resp.sunatResponse.cdrResponse.hash = resp.hash;
                    this.venta.cdrResponse = JSON.stringify(resp.sunatResponse.cdrResponse);
                    this.venta.estadoSunat = 1; 
                    this.$inertia.post(this.route('ventas.store'),this.venta);
                }else{
                    this.venta.CDR = '';
                    this.venta.estadoSunat = 0;
                    this.venta.cdrResponse = JSON.stringify(resp.sunatResponse.error);
                }
            })
            .catch((error) => {
                alert("Ha ocurrido un error y los datos no puedieron ser guardados");
            });

        },
        store(){
            let text = "¿Desea enviar la factura a SUNAT?";
            if (confirm(text) == true) {
                this.envioSunat();
            } else {
                this.$inertia.post(this.route('ventas.store'),this.venta);
            }
            
        },
        agregarProducto(producto){
            let id = 0;
            if (this.venta.productos.length > 0) {
                id = this.venta.productos[this.venta.productos.length - 1].id + 1;
            }
            this.venta.productos = [...this.venta.productos, { ...producto, id}];
            // Calculos para los totales
            this.venta.mtoOperGravadas = parseFloat(this.venta.mtoOperGravadas) + parseFloat(producto.mtoValorVenta);
            this.venta.valorVenta = parseFloat(this.venta.valorVenta) + parseFloat(producto.mtoValorVenta);
            this.venta.mtoIGV = (parseFloat(this.venta.mtoIGV) + parseFloat(producto.mtoValorVenta*0.18)).toFixed(2);
            this.venta.totalImpuestos = parseFloat(this.venta.totalImpuestos) + parseFloat(producto.mtoValorVenta*0.18);
            this.venta.subTotal = parseFloat(this.venta.valorVenta) + this.venta.totalImpuestos;
            this.venta.mtoImpVenta = Math.floor(this.venta.subTotal * 10) / 10;
            this.venta.redondeo = parseFloat(this.venta.mtoImpVenta) - parseFloat(this.venta.subTotal);
            
        },

        
        eliminarProducto(id) {
            console.log(this.venta.productos.length);
            this.venta.productos = this.venta.productos.filter(
                producto => producto.id !== id
            );
            let mtoOperGravadas = 0;
            let valorVenta = 0;
            let mtoIGV = 0;
            let totalImpuestos = 0;
            let subTotal = 0;
            let mtoImpVenta = 0;
            let redondeo = 0;
            
            this.venta.productos.forEach(function(producto) {
                mtoOperGravadas = parseFloat(mtoOperGravadas) + parseFloat(producto.mtoValorVenta);
                valorVenta = parseFloat(valorVenta) + parseFloat(producto.mtoValorVenta);
                mtoIGV = mtoIGV + parseFloat(producto.mtoValorVenta*0.18);
                totalImpuestos = parseFloat(totalImpuestos) + parseFloat(producto.mtoValorVenta*0.18);
                subTotal = parseFloat(valorVenta) + parseFloat(totalImpuestos);
                mtoImpVenta = Math.floor(subTotal * 10) / 10;
                redondeo = parseFloat(mtoImpVenta) - parseFloat(subTotal);
            })
            this.venta.mtoOperGravadas = mtoOperGravadas;
            this.venta.valorVenta = valorVenta;
            this.venta.mtoIGV = mtoIGV;
            this.venta.totalImpuestos = totalImpuestos;
            this.venta.subTotal = subTotal;
            this.venta.mtoImpVenta = mtoImpVenta;
            this.venta.redondeo = redondeo;
        },
        cancelar(){
            this.$inertia.get(this.route('ventas.index'));
        }
    }

}
</script>

<style>

</style>