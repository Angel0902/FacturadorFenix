<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import TextInput from '@/Components/TextInput.vue';
    import ValidationsErrors from '@/Components/ValidationsErrors.vue';
    import fileUpload from '@/Components/fileUpload.vue';
</script>
<template>
  <app-layout title="Usuarios">
        
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Empresas
            </h2>
        </template>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <ValidationsErrors/>
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">            
                <h1 class="text-3xl font-semibold text-gray-700 mb-3">
                    <span class="text-indigo-500" >Empresas / </span> Crear
                </h1>
                
                <div class="bg-white px-6 py-8 rounded-lg shadow">
                    

                    <div class="grid grid-cols-2 gap-6" >
                        <div>
                            <label >
                                RUC
                                <TextInput v-model="organizacion.ruc" type="text" class="w-full"/>    
                            </label>  
                        </div>
                        <div>
                            <label >
                                Razón Social
                                <TextInput v-model="organizacion.razon_social" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Dirección
                                <TextInput v-model="organizacion.direccion" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Estado
                                <select v-model="organizacion.production" class="w-full rounded" >
                                    <option value="0" selected>Beta</option>
                                    <option value="1">Producción</option>                                    
                                </select>
                            </label>
                        </div>
                        <div>
                            <label >
                                Usuario SOL
                                <TextInput v-model="organizacion.sol_user" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Clave Sol
                                <TextInput v-model="organizacion.sol_pass" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <!-- <div>
                            <label >
                                Id Cliente
                                <TextInput v-model="organizacion.client_id" type="text" class="w-full"/>    
                            </label>
                        </div>
                        <div>
                            <label >
                                Clave Cliente
                                <TextInput v-model="organizacion.client_secret" type="text" class="w-full"/>    
                            </label>
                        </div> -->
                        <div>
                            <label >
                                Logo<br>
                                <input type="file" accept="image/*" @change=uploadImage >
                                <!-- <TextInput v-model="organizacion.logo" type="file"  accept="image/*" class="w-full rounded"  @change=uploadImage/>     -->
                            </label>
                        </div>
                        
                        <div>
                            <label >
                                Certificado<br>
                                <file-upload v-model="organizacion.cert"></file-upload>
                                <!-- <input v-model="organizacion.cert" type="file" class="w-full rounded"/>     -->
                            </label>
                        </div>
                        
                    </div>
                    <div class="bg-white px-6 py-8 rounded-lg shadow">
                        <div class="grid grid-cols-2 gap-6">
                        </div>
                        <div class="flex justify-end">
                            <button @click="store" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 m-1 rounded">
                                Crear Empresa
                            </button>
                            <button @click="cancelar" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 m-1 rounded">
                                Cancelar
                            </button>

                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </app-layout>
</template>

<script>
export default {
    data(){
        return {
            organizacion:{
            razon_social:'',
            ruc:'',
            direccion:'',
            logo:'',
            sol_user:'',
            sol_pass:'',
            cert:'',
            client_id:'',
            client_secret:'',
            production:''
            }
        }
    },
    methods:{
        store(){
            this.$inertia.post(this.route('organizacions.store'),this.organizacion);
        },
        cancelar(){
            this.$inertia.get(this.route('organizacions.index'));
        },
        uploadImage(e){
            const image = e.target.files[0];
            this.organizacion.logo = image;
        }
    }

}
</script>

<style>

</style>