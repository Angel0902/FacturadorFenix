<script setup>
    import AppLayout from '@/Layouts/AppLayout.vue';
    import TextInput from '@/Components/TextInput.vue';
    import ValidationsErrors from '@/Components/ValidationsErrors.vue';
</script>
<template>
    <app-layout title="Usuarios">
          
          <template #header>
              <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                  Usuarios
              </h2>
          </template>
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
              <ValidationsErrors/>
              <!-- component -->
              <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">            
                  <h1 class="text-3xl font-semibold text-gray-700 mb-3">
                      <span class="text-indigo-500" >Usuarios / </span> Editar
                  </h1>
                  
                  <div class="bg-white px-6 py-8 rounded-lg shadow">
                      
  
                      <div class="grid grid-cols-2 gap-6" >
                          <div>
                              <label >
                                  Nombre
                                  <TextInput v-model="form.name" type="text" class="w-full"/>    
                              </label>  
                          </div>
                          <div>
                              <label >
                                  Correo Electronico
                                  <TextInput v-model="form.email" type="email" class="w-full"/>    
                              </label>
                          </div>
                          <!-- <div>
                              <label >
                                  Clave
                                  <TextInput v-model="form.password" type="text" class="w-full"/>    
                              </label>
                          </div> -->
                          <div>
                              <label >
                                  Rol
                                  <select v-model="form.roles[0].id " class="w-full rounded" >
                                      <option v-for="rol in roles"  :key="rol.id" :value="rol.id">
                                          {{ rol.name }}
                                      </option>
                                  </select>
                              </label>
                          </div>
                          <div>
                              <label >
                                  Empresa
                                  <select v-model="form.organizacion_id" class="w-full rounded" >
                                      <option v-for="organizacion in organizacions" :key="organizacion.id" :value="organizacion.id">
                                          {{ organizacion.razon_social }}
                                      </option>
                                  </select>
                              </label>
                          </div>
                          
                          
                      </div>
  
                      <div class="bg-white px-6 py-8 rounded-lg shadow">
                          <div class="grid grid-cols-2 gap-6">
                          </div>
                          <div class="flex justify-end">
                              <button @click="update" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                  Actualizar
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
            form:this.user
        }
    },
    props:{
        user:Object,
        organizacions:Array,
        roles:Object
    },
    methods:{
        update(){
            this.$inertia.form(this.form).put(this.route('users.update',this.user));
        }
    }

}
</script>

<style>

</style>