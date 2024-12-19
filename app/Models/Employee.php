<?php

namespace App\Models;

use App\Enums\UserStatusEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
	use LogsActivity;

    // protected $with = ['projects']; 
    protected $table = 'employees';

    protected $fillable = ['first_name', 'last_name','user_name','email','mobile_phone','office_phone','address','image','status'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['first_name', 'last_name','user_name','email','mobile_phone','office_phone'
		,'address','image','status'])
		 ->logOnlyDirty()
        ->dontSubmitEmptyLogs()
		->useLogName('Employee');
        // Chain fluent methods for configuration options
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        date_default_timezone_set('Africa/Cairo');
        $activity->created_at = date('Y-m-d H:i:s');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => UserStatusEnum::class,
        ];
    }

    public function scopeAccepted(Builder $query): Builder
    {
        return $query->where('status',1);
    }
    protected function getimageAttribute(): string
    {
        if($this->attributes['image'] != NULL){
            return Storage::url('employee'.$this->attributes['id'].'/'.$this->attributes['image']);
        }else{
            return asset('images/depositphotos_133351928-stock-illustration-default-placeholder-man-and-woman.webp');
        }
        
    }
    /**
     *
     * @return collection
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
 
}
