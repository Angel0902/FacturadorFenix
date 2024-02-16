<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import Pagination from '@/Components/Pagination.vue';
    import Menu from '@/Components/Menu.vue';
</script>


<template>
    
    <app-layout title="Usuarios">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Usuarios
            </h2>
        </template>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- component -->
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                <div class="px-6 py-4 flex items-center float-right">
                    <Link :href="route('users.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nuevo</Link>
                </div>

                <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Nombre</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Correo Electronico</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Empresa</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900">Rol</th>
                        <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ user.name}}</td>
                            <td class="px-6 py-4">{{ user.email}}</td>
                            <td class="px-6 py-4">{{user.organizacion.razon_social}}</td>
                            <td class="px-6 py-4">{{user.roles[0].name}}</td>                        
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-4">
                                    <!-- x-data="{ tooltip: 'Delete' }" -->
                                    <Link  :href="route('users.edit',user)">
                                        <i class="fa-solid fa-pen"></i>
                                    </Link>
                                    <!-- x-data="{ tooltip: 'Edite' }" -->
                                    <Link  @click="destroy(user)">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </Link>
                                </div>
                            </td>
                        </tr> 
                        
                    </tbody>
                </table>
                <pagination :pagination="users"></pagination>
            </div>
        </div>
        
    </app-layout>
    
</template>

<script>
import {Link} from '@inertiajs/vue3';
    export default {
        props:{
            users:{
                type: Object,
                required:true
            },
        },
        components:{
            Pagination,
            Link
        },
        methods:{
            destroy(user){
                this.$inertia.delete(this.route('users.destroy',user));
            }
        }

    }
</script>

<style>

</style>