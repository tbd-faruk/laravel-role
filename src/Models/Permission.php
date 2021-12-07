<?php

namespace Technobd\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Technobd\Permission\Models\PermissionGroup;
class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','permission_group_id','path'];

    public function roles() {

        return $this->belongsToMany(Role::class,'roles_permissions');

    }

    public function users() {

        return $this->belongsToMany(User::class,'users_permissions');

    }
    public function category() {
        return $this->belongsTo(PermissionGroup::class,'permission_group_id','id');
    }
}
