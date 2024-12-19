<?php

namespace App\Models;

use App\Notifications\InvoiceUpdate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log; 
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
	use LogsActivity;
    protected $fillable = ['number', 'title','amount','date','description','payment_status','user_id'];
	
    protected static function booted(): void
    {
        static::updated(function (Invoice $invoice) {
            Log::info('invoice updated');


            $changes = $invoice->getChanges();
            $messge = '';
            if(count($changes) > 0){
                $messge = 'Your invoice has been updated';
                foreach($changes as $key => $value){
                    if($key != 'updated_at'){
                        $messge.= '<p>'.$key.' : '.$value.' </p> ';
                    }
                }
            }
            $user = User::find($invoice->user_id);
            $user->notify(new InvoiceUpdate($messge,$invoice->id));

            
        });
    }
	
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['number', 'title','amount','date','description','user_id','payment_status'])
		 ->logOnlyDirty()
        ->dontSubmitEmptyLogs()
		->useLogName('Invoice');
        // Chain fluent methods for configuration options
    }
	
    public function tapActivity(Activity $activity, string $eventName)
    {
        date_default_timezone_set('Africa/Cairo');
        $activity->created_at = date('Y-m-d H:i:s');
    }
	
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

}
