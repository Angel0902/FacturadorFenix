<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Pagination.vue';
    //import Menu from '@/Components/Menu.vue';
    //import DialogModal from '@/Components/DialogModal.vue';
</script>


<template>
    
    <app-layout title="Ventas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Facturas
            </h2>
        </template>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                <div class="px-6 py-4 flex items-center float-right">
                    <Link :href="route('ventas.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nuevo</Link>
                </div>

                <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">RUC</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Razón Social</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">No. Documento</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Fecha</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">HTML</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">PDF</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">XML</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">CDR</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Sunat</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        
                        <tr v-for="venta in ventas.data" :key="venta.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ venta.ruc}}</td>
                            <td class="px-6 py-4">{{ venta.razonSocial}}</td>
                            <td class="px-6 py-4">{{ venta.serie + '-' + venta.correlativo}}</td>
                            <td class="px-6 py-4">{{ venta.fechaEmision}}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4">
                                    <a v-if="venta.estadoSunat==1" :href="route('ventas.html',venta)" ><i class="fa-brands fa-html5"></i></a>
                                    <a v-else @click="noInformada"><i class="fa-brands fa-html5"></i></a>
                                </div>
                            </td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="venta.estadoSunat==1" :href="route('ventas.pdf',venta)" ><i class="fa-solid fa-file-pdf"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-file-pdf"></i></a>
                            </div></td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="venta.estadoSunat==1" :href="route('ventas.xml',venta)" ><i class="fa-solid fa-file-code"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-file-code"></i></a>
                            </div></td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="venta.estadoSunat==1" :href="route('ventas.cdr',venta)"><i class="fa-solid fa-file"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-file"></i></a>
                            </div></td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="venta.estadoSunat==1" @click="stateInvoice(venta)"><i class="fa-solid fa-check"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-check"></i></a>
                            </div></td>
                            <td class="px-6 py-4">
                            <div class="flex justify-end gap-4">
                                <a v-if="venta.estadoSunat==1" @click="informadaEdit"><i class="fa-solid fa-pen"></i></a>
                                <a v-else :href="route('ventas.edit',venta)"><i class="fa-solid fa-pen"></i></a>
                                <a v-if="venta.estadoSunat==1" @click="informadaDelete"><i class="fa-solid fa-trash-can"></i></a>
                                <a v-else @click="destroy(venta)"><i class="fa-solid fa-trash-can"></i></a>
                            </div>
                            </td>
                        </tr> 
                        
                    </tbody>
                </table>
                <pagination :pagination="ventas"></pagination>
            </div>
        </div>
        
    </app-layout>
    
</template>

<script>
import {Link} from '@inertiajs/vue3';
    export default {
        props:{
            ventas:{
                type: Object,
                required:true
            },
        },
        components:{
            Pagination,
            Link
        },
        methods:{
            destroy(venta){
                let text = "¿Está seguro de eliminar esta factura?";
                if (confirm(text) == true) {
                    this.$inertia.delete(this.route('ventas.destroy',venta));
                } else {
                    return false;
                }
            },
            stateInvoice(venta){
                if(venta.cdrResponse){
                    let $datosResponse = JSON.parse(venta.cdrResponse);
                    alert($datosResponse.description);
                }else{
                    alert("La factura no ha sido informada");
                }
            },
            noInformada(){
                alert("La factura no ha sido informada");
            },
            informadaEdit(){
                alert("La factura ya esta informada a SUNAT y no se puede editar");
            },
            informadaDelete(){
                alert("La factura ya esta informada a SUNAT y no se puede eliminar");
            }
        }
    }
</script>

<style>

</style>