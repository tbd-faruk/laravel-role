<?php

namespace Technobd\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];
    public function permissions() {

        return $this->belongsToMany(Permission::class,'roles_permissions');

    }

    public function users() {
        return $this->belongsToMany(User::class,'users_roles');
    }

    public function hasPermission($permission) {
        return $this->permissions->where('slug',$permission)->count() ? true : false;
    }
}
