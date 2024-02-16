<template>
    <div id="table-products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead  class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-4 font-medium text-gray-900">Codigo</th>
            <th scope="col" class="px-6 py-4 font-medium text-gray-900">Descripcion</th>
            <th scope="col" class="px-6 py-4 font-medium text-gray-900">Cantidad</th>
            <th scope="col" class="px-6 py-4 font-medium text-gray-900">Precio</th>
            <th scope="col" class="px-6 py-4 font-medium text-gray-900">Importe</th>
            <th scope="col" class="px-6 py-4 font-medium text-gray-900"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="producto in productos" :key="producto.id">
            <td class="px-6 py-4">{{ producto.codProducto}}</td>
            <td class="px-6 py-4">{{ producto.descripcion }}</td>
            <td class="px-6 py-4">{{ producto.cantidad}}</td>
            <td class="px-6 py-4">{{ producto.mtoValorUnitario}}</td>
            <td class="px-6 py-4">{{ producto.mtoValorVenta}}</td>
            <td class="px-6 py-4">
              <div class="flex justify-end gap-4">
                  <a  @click="$emit('delete-producto', producto.id)">
                      <i class="fa-solid fa-trash-can"></i>
                  </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
    import {Link} from '@inertiajs/vue3';
    export default{
        name: 'table-products',
        props: {
            productos: Array,
        },
        components:{
            Link
        }
    }
  </script>
  
  <style scoped></style>