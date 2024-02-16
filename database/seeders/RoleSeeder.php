<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Usuario']);

        Permission::create(['name' => 'dashboard','description'=>'Ver el dashboard'])->syncRoles([$role1,$role2]);

        Permission::create(['name' => 'organizacions.index','description'=>'Ver listado de empresas'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.index','description'=>'Ver listado de usuarios'])->syncRoles([$role1]);
        
        Permission::create(['name' => 'ventas.index','description'=>'ver listado de facturas'])->syncRoles([$role2]);
        Permission::create(['name' => 'notas.index','description'=>'ver listado de notas de credito'])->syncRoles([$role2]);
        
        /*Permission::create(['name' => 'admin.categories.edit','description'=>'Editar  categorias'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.categories.destroy','description'=>'Eliminar categorias'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.tags.index','description'=> 'Ver listado de etiquetas'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'admin.tags.create','description'=>'Crear etiquetas'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.tags.edit','description'=>'Editar etiquetas'])->syncRoles([$role1]);
        Permission::create(['name' => 'admin.tags.destroy','description'=>'Eliminar etiquetas'])->syncRoles([$role1]);

        Permission::create(['name' => 'admin.posts.index','description'=>'Ver listado de Post'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'admin.posts.create','description'=>'Crear Post'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'admin.posts.edit','description'=>'Editar'])->syncRoles([$role1,$role2]);
        Permission::create(['name' => 'admin.posts.destroy','description'=>'Eliminar'])->syncRoles([$role1,$role2]);*/
    }
}
