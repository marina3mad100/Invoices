<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait;

class Permission extends SpatiePermission
{
    use EagerLoadPivotTrait;

    //
}
