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
                Notas de Crédito
            </h2>
        </template>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ValidationsErrors/>
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">            
                <h1 class="text-3xl font-semibold text-gray-700 mb-3">
                    <span class="text-indigo-500" >Notas de Crédito / </span> Crear
                </h1>
                
                <div class="bg-white px-6 py-8 rounded-lg shadow">
                    
                    <div class="grid grid-cols-4 gap-6" >
                        <div>
                            <label >
                                Serie
                                <TextInput v-model="nota.serie" type="text" class="w-full" maxlength="4"/>    
                            </label>  
                        </div>
                        <div>
                            <label >
                                Correlativo
                                <TextInput v-model="nota.correlativo" type="Number" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label>
                                Fecha de Emisión
                                <TextInput v-model="nota.fechaEmision" type="datetime-local" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label>
                                Fecha de Vencimiento
                                <TextInput v-model="nota.fechaVencimiento" type="datetime-local" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Factura Relacionada
                                <TextInput v-model="nota.numDocfectado" type="text" class="w-full" maxlength="10"/>    
                            </label>  
                        </div>
                        <!-- <div>
                            <label >
                                Cod. Motivo
                                <TextInput v-model="nota.codMotivo" type="Number" class="w-full"/>    
                            </label>
                        </div> -->
                        <div class="col-span-3">
                            <label >
                                Motivo
                                <!-- <TextInput v-model="nota.desMotivo" type="text" class="w-full"/>     -->
                                <select v-model="nota.codMotivo" class="w-full rounded">
                                    <option value="" selected disabled>- Seleccione un motivo -</option>
                                    <option value="01" >Anulación de la operación</option>
                                    <option value="02">Anulación por error en el RUC</option>
                                    <option value="03">Corrección por error en la descripción</option>
                                    <option value="04">Descuento global</option>
                                    <option value="05">Descuento por ítem</option>
                                    <option value="06">Devolucion total</option>
                                    <option value="07">Devolucion por item</option>
                                    <option value="08">Bonificación</option>
                                    <option value="09">Disminución en el valor</option>
                                    <option value="10">Otros conceptos</option>
                                    <option value="11">Ajustes de operaciones de exportación</option>
                                    <option value="12">Ajustes afectos al IVAP</option>
                                    <option value="13">Corrección del monto neto pendiente del pago y/o la(s) fecha(s) de vencimiento del pago único o de las cuotas y/o los montos correspondientes a cada cuota, de ser el caso</option>
                                </select>   
                            </label>
                        </div>
                        <div>
                            <label >
                                RUC
                                <TextInput v-model="nota.ruc" type="text" class="w-full" maxlength="11" />    
                            </label>
                        </div>
                        <div class="col-span-2">
                            <label >
                                Razon Social
                                <TextInput v-model="nota.razonSocial" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Moneda
                                <select v-model="nota.tipoMoneda" class="w-full rounded" >
                                    <option value="PEN" >SOL</option>
                                    <option value="USD"  >USD</option>
                                </select>   
                            </label>
                        </div>
                        <div class="col-span-4">
                            <label >
                                Direccion
                                <TextInput v-model="nota.direccion" type="text" class="w-full"/>    
                            </label>
                        </div>                        
                    </div>
                    
                    <form-producto  @add-producto="agregarProducto"  />
                    <br>
                    <table-products :productos="nota.productos" v-model="nota.productos" @delete-producto="eliminarProducto"  />
                    <br>    
                    <div class="bg-white rounded-lg shadow"> 
                    <div class="grid grid-cols-5 gap-6" >
                        <div>
                            <label >
                                SubTotal
                                <TextInput v-model="nota.mtoOperGravadas" type="text" class="w-full" readonly/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                IGV
                                <TextInput v-model="nota.mtoIGV" type="text" class="w-full" readonly/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Total
                                <TextInput v-model="nota.subTotal" type="text" class="w-full" readonly/>    
                            </label>
                        </div>
                        <div>
                            <button @click="update" class="bg-blue-500 hover:bg-blue-700 text-white font-bold w-full mt-6 py-2 px-4 rounded">
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
            form:this.nota          
            
        }
    },
    props:{
        nota:Object,
        organizacion:Object
    },
    mounted(){
        let productos = JSON.parse(this.nota.productos);
        this.form.productos = [];
        
        this.nota.mtoOperGravadas = 0;
        this.nota.valorVenta = 0;
        this.nota.mtoIGV = 0;
        this.nota.totalImpuestos = 0;
        this.nota.subTotal = 0;
        this.nota.mtoImpVenta = 0;
        this.nota.redondeo = 0;

        productos.forEach(producto => {
            let id = 0;
            if (this.nota.productos.length > 0) {
                id = this.nota.productos[this.nota.productos.length - 1].id + 1;
            }
            this.nota.productos = [...this.nota.productos, { ...producto, id}];
            // Calculos para los totales
            this.nota.mtoOperGravadas = parseFloat(this.nota.mtoOperGravadas) + parseFloat(producto.mtoValorVenta);
            this.nota.valorVenta = parseFloat(this.nota.valorVenta) + parseFloat(producto.mtoValorVenta);
            this.nota.mtoIGV = (parseFloat(this.nota.mtoIGV) + parseFloat(producto.mtoValorVenta*0.18)).toFixed(2);
            this.nota.totalImpuestos = parseFloat(this.nota.totalImpuestos) + parseFloat(producto.mtoValorVenta*0.18);
            this.nota.subTotal = parseFloat(this.nota.valorVenta) + this.nota.totalImpuestos;
            this.nota.mtoImpVenta = Math.floor(this.nota.subTotal * 10) / 10;
            this.nota.redondeo = parseFloat(this.nota.mtoImpVenta) - parseFloat(this.nota.subTotal);
        });

    },
    methods:{
        envioSunat(){
            let data = JSON.stringify({
            "ublVersion": "2.1",
            "tipoDoc": "07",
            "serie": this.nota.serie,
            "correlativo": this.nota.correlativo,
            "fechaEmision": this.nota.fechaEmision,
            "tipDocAfectado": this.nota.tipDocAfectado,
            "numDocfectado": this.nota.numDocfectado,
            "codMotivo": this.nota.codMotivo,
            "desMotivo": this.nota.desMotivo,
            "formaPago": {
                "moneda": this.nota.tipoMoneda,
                "tipo": "Contado"
            },
            "tipoMoneda":this.nota.tipoMoneda,
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
                "numDoc": this.nota.ruc,
                "rznSocial": this.nota.razonSocial
            },
            "details": this.nota.productos
            });
            //console.log(data);
            let token = JSON.parse(localStorage.getItem('token'));
            let config = {
            method: 'post',
            maxBodyLength: Infinity,
            url: 'http://facturadorfenix/api/notes/send',
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
                this.nota.XML = resp.xml;
                if(resp.sunatResponse.succes){
                    this.nota.CDR = resp.sunatResponse.cdrZip;
                    resp.sunatResponse.cdrResponse.hash = resp.hash;
                    this.nota.cdrResponse = JSON.stringify(resp.sunatResponse.cdrResponse);
                    this.nota.estadoSunat = 1;
                    this.$inertia.put(this.route('notas.update',this.nota),this.form);
                }else{
                    this.nota.CDR = '';
                    this.nota.estadoSunat = 0;
                    this.nota.cdrResponse = JSON.stringify(resp.sunatResponse.error);
                }
                //this.$inertia.post(this.route('notas.store'),this.nota);    
            })
            .catch((error) => {
                alert("Ha ocurrido un error y los datos no puedieron ser guardados:"+error);
            });

        },
        update(){
            this.nota.desMotivo = this.selectDesMotivo(this.nota.codMotivo);
            let text = "¿Desea enviar la nota de crédito a SUNAT?";
            if (confirm(text) == true) {
                this.envioSunat();
            } else {
                this.form._method = 'put',
                //console.log(this.organizacion);
                this.$inertia.form(this.form).post(this.route('notas.update',this.nota));
                //this.$inertia.post(this.route('notas.store'),this.nota);
            }            
        },
        selectDesMotivo(codMotivo){
            switch (codMotivo) {
                case '01':
                    return 'Anulación de la operación';
                    break;
                case '02':
                    return 'Anulación por error en el RUC';
                    break;    
                case '03':
                    return 'Corrección por error en la descripción';
                    break;
                case '04':
                    return 'Descuento global';
                    break;
                case '05':
                    return 'Descuento por ítem';
                    break; 
                case '06':
                    return 'Devolucion total';
                    break;  
                case '07':
                    return 'Devolucion por item';
                    break; 
                case '08':
                    return 'Bonificación';
                    break;
                case '09':
                    return 'Disminución en el valor';
                    break;
                case '10':
                    return 'Otros conceptos';
                    break;  
                case '11':
                    return 'Ajustes de operaciones de exportación';
                    break;                
                case '12':
                    return 'Ajustes afectos al IVAP';
                    break;      
                case '13':
                    return 'Corrección del monto neto pendiente del pago y/o la(s) fecha(s) de vencimiento del pago único o de las cuotas y/o los montos correspondientes a cada cuota, de ser el caso';
                    break;  
                default:
                    break;
            }
        },
        agregarProducto(producto){
            console.log(producto);
            let id = 0;
            if (this.nota.productos.length > 0) {
                id = this.nota.productos[this.nota.productos.length - 1].id + 1;
            }
            this.nota.productos = [...this.nota.productos, { ...producto, id}];

            // Calculos para los totales
            this.nota.mtoOperGravadas = parseFloat(this.nota.mtoOperGravadas) + parseFloat(producto.mtoValorVenta);
            this.nota.valorVenta = parseFloat(this.nota.valorVenta) + parseFloat(producto.mtoValorVenta);
            this.nota.mtoIGV = (parseFloat(this.nota.mtoIGV) + parseFloat(producto.mtoValorVenta*0.18)).toFixed(2);
            this.nota.totalImpuestos = parseFloat(this.nota.totalImpuestos) + parseFloat(producto.mtoValorVenta*0.18);
            this.nota.subTotal = parseFloat(this.nota.valorVenta) + this.nota.totalImpuestos;
            this.nota.mtoImpVenta = Math.floor(this.nota.subTotal * 10) / 10;
            this.nota.redondeo = parseFloat(this.nota.mtoImpVenta) - parseFloat(this.nota.subTotal);            
        },

        
        eliminarProducto(id) {
            console.log(this.nota.productos.length);
            this.nota.productos = this.nota.productos.filter(
                producto => producto.id !== id
            );
            let mtoOperGravadas = 0;
            let valorVenta = 0;
            let mtoIGV = 0;
            let totalImpuestos = 0;
            let subTotal = 0;
            let mtoImpVenta = 0;
            let redondeo = 0;
            
            this.nota.productos.forEach(function(producto) {
                mtoOperGravadas = parseFloat(mtoOperGravadas) + parseFloat(producto.mtovalorVenta);
                valorVenta = parseFloat(valorVenta) + parseFloat(producto.mtovalorVenta);
                mtoIGV = mtoIGV + parseFloat(producto.mtovalorVenta*0.18);
                totalImpuestos = parseFloat(totalImpuestos) + parseFloat(producto.mtovalorVenta*0.18);
                subTotal = parseFloat(valorVenta) + parseFloat(totalImpuestos);
                mtoImpVenta = Math.floor(subTotal * 10) / 10;
                redondeo = parseFloat(mtoImpVenta) - parseFloat(subTotal);
            })
            this.nota.mtoOperGravadas = mtoOperGravadas;
            this.nota.valorVenta = valorVenta;
            this.nota.mtoIGV = mtoIGV;
            this.nota.totalImpuestos = totalImpuestos;
            this.nota.subTotal = subTotal;
            this.nota.mtoImpVenta = mtoImpVenta;
            this.nota.redondeo = redondeo;
        },
        cancelar(){
            this.$inertia.get(this.route('notas.index'));
        }
    }

}
</script>

<style>

</style>