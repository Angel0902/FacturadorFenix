<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Pagination.vue';
    import Menu from '@/Components/Menu.vue';
</script>


<template>
    
    <app-layout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Empresas
            </h2>
        </template>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                <div class="px-6 py-4 flex items-center float-right">
                    <Link :href="route('organizacions.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nuevo</Link>
                </div>

                <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">RUC</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Razón Social</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Dirección</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Producción</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        
                        <tr v-for="organizacion in organizacions.data" :key="organizacion.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ organizacion.ruc}}</td>
                            <td class="px-6 py-4">{{ organizacion.razon_social}}</td>
                            <td class="px-6 py-4">{{ organizacion.direccion}}</td>
                            <td v-if="!organizacion.production" class="px-6 py-4">Beta</td>
                            <td v-else class="px-6 py-4">Produccion</td>
                            <td class="px-6 py-4">
                            <div class="flex justify-end gap-4">
                                <!-- x-data="{ tooltip: 'Delete' }" -->
                                <Link  :href="route('organizacions.edit',organizacion)">
                                    <i class="fa-solid fa-pen"></i>
                                </Link>
                                <!-- x-data="{ tooltip: 'Edite' }" -->
                                <Link  @click="destroy(organizacion)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </Link>
                            </div>
                            </td>
                        </tr> 
                        
                    </tbody>
                </table>
                <pagination :pagination="organizacions"></pagination>
            </div>
        </div>
        
    </app-layout>
    
</template>

<script>
import {Link} from '@inertiajs/vue3';
    export default {
        props:{
            organizacions:{
                type: Object,
                required:true
            },
        },
        components:{
            Pagination,
            Link
        },
        methods:{
            destroy(organizacion){
                this.$inertia.delete(this.route('organizacions.destroy',organizacion));
            }
        }

    }
</script>

<style>

</style>