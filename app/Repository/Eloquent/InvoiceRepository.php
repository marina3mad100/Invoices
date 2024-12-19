<?php

namespace App\Repository\Eloquent;

use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\Invoice;
use App\Repository\InvoiceRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{

   /**
    * InvoiceRepository constructor.
    *
    * @param Invoice $model
    */
   public function __construct(Invoice $model)
   {
       parent::__construct($model);
   }


    
     /**
    *@param Request $request
    *@param int $length 
    *@return LengthAwarePaginator
    */
    public function search_invoices($request ,$where,  $length): LengthAwarePaginator {
        // $this->newQuery()->eagerLoadRelations();
        
        try{
            $sub1 = \DB::raw('(select users.id , clients.first_name,clients.last_name,clients.email,clients.mobile_phone from users join clients on users.userable_id = clients.id)clients');
            $invoices = $this->model->where($where)->join($sub1,function($join){
                $join->on('clients.id','=','user_id');       
            })
            ->when($request->search , function($query) use($request){
                $query->whereAny(['number','title','amount','date','description','payment_status'], 'LIKE', "%".$request->search."%");
               // $query->orWhereHas('user.userable',function($q)use($request){
                 //   $q->whereAny(['first_name', 'last_name','email','mobile_phone'], 'LIKE', "%".$request->search."%");
                //});
                $query->orwhereAny(['first_name','last_name','email','mobile_phone'], 'LIKE', "%".$request->search."%");


            })->paginate($length);
            return $invoices;    
 
         }catch (\Exception $e) {
             throw new \Exception($e->getMessage());
         }
         
    }


  
}