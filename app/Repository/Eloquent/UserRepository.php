<?php

namespace App\Repository\Eloquent;

use App\Models\Models;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


class UserRepository extends BaseRepository implements UserRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param User $model
    */
   public function __construct(User $model)
   {
       parent::__construct($model);
   }


    /**
    * @param Model $user
    * @param array $permissions
    */
    public function create_role_permisions_of_user($user , $permissions) :void
    {
        //$user->permissions()->detach();
        $user->permissions()->sync($permissions);
    } 
    
     /**
    *@param Request $request
    *@param int $length 
    *@return LengthAwarePaginator
    */
    public function search_users($request , $length): LengthAwarePaginator {
        // $this->newQuery()->eagerLoadRelations();
        try{
            $users = $this->model->with(['userable'])
            ->when($request->search , function($query) use($request){
                $query->where('userable_type', 'LIKE', "%".$request->search."%");
                $query->orWhereHas('userable',function($q)use($request){
                    $q->whereAny(['first_name', 'last_name','email','mobile_phone','office_phone','address'], 'LIKE', "%".$request->search."%");
                });

            })->paginate($length);
            return $users;    
 
         }catch (\Exception $e) {
             throw new \Exception($e->getMessage());
         }
         
    }


  
}