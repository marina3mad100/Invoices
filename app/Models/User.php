<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Models\Invoice;
use App\Models\Permission;
use App\Models\Role;
use GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    use HasApiTokens;
	use HasPermissions;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
	use LogsActivity;
	use PivotEventTrait;
    use EagerLoadPivotTrait;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path'
    ];

	protected $guard_name = 'sanctum';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    public  static function boot(){
		
        parent::boot();
        static::pivotSynced(function ($model, $relationName, $changes) {
			//dd($relationName);
           	$newPermission = \App\Models\Permission::select(\DB::raw("GROUP_CONCAT(name) AS Permissions"))
			->whereIn('id',$changes['attached'])->get();     

			$deletedPermission = \App\Models\Permission::select(\DB::raw("GROUP_CONCAT(name) AS Permissions"))
			->whereIn('id',$changes['detached'])->get();

			$attrs['new'] = $newPermission;
			$attrs['deleted'] = $deletedPermission;			
			activity()
			->useLog('User Premission')
			->performedOn($model)
			->withProperties((object)$attrs)
			->log('updated:sync Permissions');			
			
			


        });    
    
        static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
			// dd($pivotIdsAttributes);
        });
        
        static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {

        });
    
        static::pivotDetached(function ($model, $relationName, $pivotIds) {

        });
 
       
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['password'])
		 ->logOnlyDirty()
        ->dontSubmitEmptyLogs()
		->useLogName('User');
        // Chain fluent methods for configuration options
    }


    public function tapActivity(Activity $activity, string $eventName)
    {
        date_default_timezone_set('Africa/Cairo');
        $activity->created_at = date('Y-m-d H:i:s');
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
   /* protected $appends = [
        'profile_photo_url',
    ];*/

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     *
     * @return collection
     */
    public function userable(): MorphTo
    {
        return $this->morphTo();
    }

    public function invoices(): hasMany
    {
        return $this->hasMany(Invoice::class);
    }

    protected function getDefaultGuardName(): string { return 'sanctum'; }


}
