<script setup>
    import TextInput from '@/Components/TextInput.vue';
</script>
<template>
    <br>    
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Productos
            </h2>
    <div  id="formulario-producto"  class="bg-white px-1 py-1 rounded-lg shadow">
      <form  @submit.prevent="enviarFormulario" class="grid grid-cols-12 gap-6">
              <div class="col-span-2">
                <label>Codigo
                    <TextInput v-model="producto.codProducto" type="text"  class="w-full" :class="{ 'is-invalid': procesando && codigoInvalido }"/>
                </label>
              </div>
              <div class="col-span-3">
                <label>Descripcion</label>
                <TextInput v-model="producto.descripcion" type="text"  class="w-full" :class="{ 'is-invalid': procesando && descripcionInvalido }"/>
              </div>
              <div class="col-span-2">
                <div>
                  <label>Cantidad</label>
                  <TextInput v-model="producto.cantidad" type="text"  class="w-full" @change="importe()" :class="{ 'is-invalid': procesando && cantidadInvalido }"/>
                </div>
              </div>  
              <div class="col-span-2">
                <label>Precio</label>
                <TextInput v-model="producto.mtoValorUnitario" type="text"  class="w-full" @change="importe()" :class="{ 'is-invalid': procesando && precioInvalido }"/>
              </div>
              <div class="col-span-2">
                <label>Importe</label>
                <TextInput v-model="producto.mtoValorVenta" type="Number"  class="w-full" readonly/>
              </div>   
              <div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-6"><i class="fa-solid fa-plus"></i></button>
              </div>
              <div class="col-span-10">
                <div v-if="error && procesando">
                  <label  class="text-red-500">Debes rellenar todos los campos!</label>
                </div>
              </div>
      </form>
    </div>
  </template>
  
  <script>
    export default {
      name: 'formulario-producto',
      data() {
        return {
          procesando: false,
          correcto: false,
          error: false,
          producto: {
            codProducto: '',
            descripcion: '',
            cantidad: '',
            mtoValorUnitario:'',
            mtoPrecioUnitario:'',
            mtoValorVenta:'',
            mtoBaseIgv:'',
            unidad: "NIU",
            porcentajeIgv: 18,
            igv: '',
            totalImpuestos: '',
            tipAfeIgv: 10,      
          },
        }
      },
        methods: {
            importe(){
              if(this.producto.cantidad>0 && this.producto.mtoValorUnitario>0){
                this.producto.mtoValorVenta = this.producto.cantidad*this.producto.mtoValorUnitario;
                this.producto.mtoBaseIgv    = this.producto.cantidad*this.producto.mtoValorUnitario;
                this.producto.mtoPrecioUnitario = ((this.producto.porcentajeIgv/100)*this.producto.mtoValorUnitario).toFixed(2);
                this.producto.mtoPrecioUnitario = parseFloat(this.producto.mtoPrecioUnitario)+parseFloat(this.producto.mtoValorUnitario);
                this.producto.totalImpuestos = parseFloat(this.producto.mtoBaseIgv)*(this.producto.porcentajeIgv/100);
                this.producto.igv            = parseFloat(this.producto.mtoBaseIgv)*(this.producto.porcentajeIgv/100);
              }              
            },
            enviarFormulario(){
              console.log("111111");
              this.procesando = true;
              console.log("222222");
              this.resetEstado();
              
              // Comprobamos la presencia de errores
              if (this.codigoInvalido || this.descripcionInvalido || this.cantidadInvalido || this.precioInvalido) {
                this.error = true;
                return;
              }

              this.$emit('add-producto', this.producto);

              this.error = false;
              this.correcto = true;
              this.procesando = false;

              // Restablecemos el valor de la variables
              this.producto = {
                codProducto: '',
                descripcion: '',
                cantidad: '',
                mtoValorUnitario:'',
                mtoPrecioUnitario:'',
                mtoValorVenta:'',
                mtoBaseIgv:'',
                unidad: "NIU",
                porcentajeIgv: 18,
                igv: 18,
                totalImpuestos: 18,
                tipAfeIgv: 10,      
              }
            },
            resetEstado() {
              this.correcto = false;
              this.error = false;
            }
        },
        computed: {
          codigoInvalido() {
            return this.producto.codProducto.length < 1;
          },
          descripcionInvalido() {
            return this.producto.descripcion.length < 1;
          },
          cantidadInvalido() {
            return this.producto.cantidad.length < 1;
          },
          precioInvalido() {
            return this.producto.mtoValorUnitario.length < 1;
          },

        },
    }
  </script>
  
  <style scoped>
    form {
      margin-bottom: 2rem;
    }
  </style>