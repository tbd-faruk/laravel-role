<?php

namespace Technobd\Permission\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug'];
    public function permissions(){
        return $this->hasMany(Permission::class);
    }
}
