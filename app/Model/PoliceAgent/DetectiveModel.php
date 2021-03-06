<?php

namespace App\Model;
use CreateRolePolice;
use CreateDetective;
use Illuminate\Database\Eloquent\Model;

class DetectiveModel extends Model
{
    const TABLE_NAME = "detective";
    const COL_ID = "detective_id";

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::COL_ID;

    protected $fillable = [
        "detective_id"
    ];
    
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(RolePoliceModel::class, "rolePolice_id");
    }
}
