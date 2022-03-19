<?php

namespace App\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\TraitClass\ModelTrait as ModelTrait;

class Admin extends Authenticatable implements JWTSubject
{
    use SoftDeletes,
        ModelTrait;

    /**
    * 与模型关联的数据表
    */
    protected $table = 'admin';

    protected $dateFormat = 'U';

    protected $fillable = ['login_name','password', 'status'];

    protected $dates = ['deleted_at'];

    public function getList()
    {
    	return $this->get();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'login_name' => '',
        ];
    }
}
