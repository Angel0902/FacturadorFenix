<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Pagination.vue';
</script>


<template>
    <app-layout title="Personas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Personas
            </h2>
        </template>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Nombre</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Organizacion</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Ciudad</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Telefono</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">

                    <tr v-for="persona in personas.data" :key="persona.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ persona.nombre + " "+persona.apellido }}</td>
                        <td class="px-6 py-4">{{ persona.organizacion.nombre }}</td>
                        <td class="px-6 py-4">{{persona.ciudad}}</td>
                        <td class="px-6 py-4">{{ persona.telefono }}</td>                        
                        <td class="px-6 py-4">
                        <div class="flex justify-end gap-4">
                            <a x-data="{ tooltip: 'Delete' }" href="#">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a x-data="{ tooltip: 'Edite' }" href="#">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                        </td>
                    </tr> 
                    
                </tbody>
            </table>
            <pagination></pagination>
            </div>
        </div>
        
    </app-layout>
    
</template>

<script>
    export default {
        props:{
            personas:{
                type: Object,
                required:true
            }
        },
        components:{
            Pagination
        }

    }
</script>

<style>

</style>