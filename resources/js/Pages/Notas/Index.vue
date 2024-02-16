<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Pagination.vue';
    import Menu from '@/Components/Menu.vue';
</script>


<template>
    
    <app-layout title="Ventas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Notas de Credito
            </h2>
        </template>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                <div class="px-6 py-4 flex items-center float-right">
                    <Link :href="route('notas.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nuevo</Link>
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
                        
                        <tr v-for="nota in notas.data" :key="nota.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ nota.ruc}}</td>
                            <td class="px-6 py-4">{{ nota.razonSocial}}</td>
                            <td class="px-6 py-4">{{ nota.serie + '-' + nota.correlativo}}</td>
                            <td class="px-6 py-4">{{ nota.fechaEmision}}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4">
                                    <a :href="route('notas.html',nota)" ><i class="fa-brands fa-html5"></i></a>
                                </div>
                            </td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a :href="route('notas.pdf',nota)" ><i class="fa-solid fa-file-pdf"></i></a>
                            </div></td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="nota.estadoSunat==1" :href="route('notas.xml',nota)" ><i class="fa-solid fa-file-code"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-file"></i></a>
                            </div></td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="nota.estadoSunat==1" :href="route('notas.cdr',nota)"><i class="fa-solid fa-file"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-file"></i></a>
                            </div></td>
                            <td class="px-6 py-4"><div class="flex justify-end gap-4">
                                <a v-if="nota.estadoSunat==1" @click="stateInvoice(nota)"><i class="fa-solid fa-check"></i></a>
                                <a v-else @click="noInformada"><i class="fa-solid fa-check"></i></a>
                            </div></td>
                            <td class="px-6 py-4">
                            <div class="flex justify-end gap-4">
                                <a v-if="nota.estadoSunat==1" @click="informadaEdit"><i class="fa-solid fa-pen"></i></a>
                                <a v-else :href="route('notas.edit',nota)"><i class="fa-solid fa-pen"></i></a>
                                <a v-if="nota.estadoSunat==1" @click="informadaDelete"><i class="fa-solid fa-trash-can"></i></a>
                                <a v-else @click="destroy(nota)"><i class="fa-solid fa-trash-can"></i></a>
                            </div>
                            </td>
                        </tr> 
                        
                    </tbody>
                </table>
                <pagination :pagination="notas"></pagination>
            </div>
        </div>
        
    </app-layout>
    
</template>

<script>
import {Link} from '@inertiajs/vue3';
    export default {
        props:{
            notas:{
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
                let text = "¿Está seguro de eliminar esta nota de credito?";
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
                    alert("La nota de credito no ha sido informada");
                }
            },
            noInformada(){
                alert("La nota de credito no ha sido informada");
            },
            informadaEdit(){
                alert("La nota de credito ya esta informada a SUNAT y no se puede editar");
            },
            informadaDelete(){
                alert("La nota de credito ya esta informada a SUNAT y no se puede eliminar");
            }
        }
    }
</script>

<style>

</style>