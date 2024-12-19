<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\Permission;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ModelHasPermissions extends Pivot
{
		
    protected $table = 'model_has_permissions';



    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

}
