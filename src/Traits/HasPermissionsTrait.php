<?php
namespace Technobd\Permission\Traits;

use Technobd\Permission\Models\Permission;
use Technobd\Permission\Models\Role;

trait HasPermissionsTrait {

    public function givePermissionsTo(... $permissions) {

        $permissions = $this->getAllPermissions($permissions);
        if($permissions === null) {
            return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

    public function withdrawPermissionsTo( ... $permissions ) {

        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);
        return $this;

    }

    public function refreshPermissions( ... $permissions ) {

        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    public function hasPermissionTo($permission) {

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermissionThroughRole($permission) {
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole( ... $roles ) {

        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function roles() {

        return $this->belongsToMany(Role::class,'users_roles');

    }
    public function permissions() {

        return $this->belongsToMany(Permission::class,'users_permissions');

    }
    protected function hasPermission($permission) {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }
    protected function checkUserPermissions($permission) {
        if(is_string($permission)){
            return (bool) $this->permissions->where('slug', $permission)->count();
        }
    }
    protected function getAllPermissions(array $permissions) {

        return Permission::whereIn('slug',$permissions)->get();

    }
    public function hasAnyPermission(...$permissions): bool
    {
        if(auth()->user()->hasRole('super-admin')){
            return true;
        }else{
        $permissionCol = collect($permissions)->flatten();
        $permissions = Permission::whereIn('slug',$permissionCol)->get();

        foreach ($permissions as $permission) {
//            dd($this->checkUserPermissions($permission));
            if ($this->hasPermissionTo($permission)) {
                return true;
            }
        }
        }
        return false;
    }

    public function assignRole(...$roles)
    {
       $roles = Role::whereIn('slug',$roles)->get();
       $this->roles()->attach($roles);
    }
    public function removeRole(...$roles)
    {
       $roles = Role::whereIn('slug',$roles)->get();
       $this->roles()->detach($roles);
    }

    public function hasPermissionByPath($path)
    {
        $permission = Permission::where('path',$path)->first();
        if($permission) {
            if ($this->hasPermissionTo($permission)) {
                return true;
            }
        }else{
            return true;
        }

    }

}
