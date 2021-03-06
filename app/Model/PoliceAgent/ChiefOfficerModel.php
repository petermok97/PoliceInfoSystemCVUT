<?php

namespace App\Model;
use CreateRolePolice;
use CreateChiefOfficer;

use Illuminate\Database\Eloquent\Model;

class ChiefOfficerModel extends Model
{
    const TABLE_NAME = "chiefOfficer";
    const COL_ID = "chiefOfficer_id";

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::COL_ID;

    protected $fillable = [
        "chiefOfficer_id"
    ];

    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(RolePoliceModel::class, "rolePolice_id");
    }
}
