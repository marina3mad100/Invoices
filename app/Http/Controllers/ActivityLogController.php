<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class ActivityLogController extends Controller
{
    public function logs(){
        $logs = Activity::with('subject','causer')->orderby('id','desc')->paginate(10);
        $logs->getCollection()->transform(function ($row) {
            if($row->log_name == 'Invoice'){
                $row->subject = $row->subject?->title??'';
            }
            else if($row->log_name == 'Employee' || $row->log_name == 'Client' | $row->log_name == 'Admin' ){
                $row->subject = $row->subject?->first_name?? ''.' '.$row->subject?->last_name??'';
            }else{
                $row->subject = $row->subject?->name??'';
            }

            $row->causer = $row->causer?->name??'';
            $attr  = json_encode($row->properties??'');
            $attr2 = json_decode($row->properties??'' , true);
           /* $attr  = json_encode($row->properties);
            $attr2 = json_decode($attr);*/
            $text_attributes = '';
            $text_old = '';
            
            $text_new = '';
            $text_delete = '';             
                    
            if(isset($attr2['attributes'])){
                $text_attributes = '<h3 class="text-red-500">New</h3>';
                foreach ($attr2['attributes'] as $key => $value) {
                    
                    $text_attributes.= $key.' : '.$value.' , ';
                }
            }
            
            if(isset($attr2['old'])){
                $text_old = '<h3 class="text-red-500">Old</h3>';
                foreach ($attr2['old'] as $key => $value) {
                    $text_old.= $key.' : '.$value.' , ';
                }
            }


            if(isset($attr2['new'])){
                $text_new = '<h3 class="text-red-500">New</h3>';
                foreach ($attr2['new'][0] as $key => $value) {
                    $text_new.= $key.' : '.$value.' , ';
                }
            }

            if(isset($attr2['deleted'])){
                $text_delete = '<h3 class="text-red-500" >Delete</h3>'; 
                foreach ($attr2['deleted'][0] as $key => $value) {
                    $text_delete.= $key.' : '.$value.' , ';
                }
            }    
             
            $row->properties_text = ' '.$text_attributes.' '.$text_old.' '.$text_new.' '.$text_delete.' ';  
               
            //$row->properties = $row->properties->toArray();
            
           // dd($attr2->attributes);
            return $row;
        });
        //return $logs;
        return view('invoicesSystem.pages.logs.index' , get_defined_vars());
    }

    public function destroy($id){
        $log = Activity::findOrFail($id);
        $log->delete();
        return redirect()->back()->with('success' , 'Log deleted successfully');
    }
}