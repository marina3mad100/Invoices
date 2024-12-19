<?php
namespace App\Repository;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


interface UserRepositoryInterface
{

    /**
    * @param Model $user
    * @param array $permissions
    */
    public function create_role_permisions_of_user($user,$permissions): void; 

    /**
    *@param Request $request
    *@param int $length 
    *@return LengthAwarePaginator
    */
    public function search_users($request , $length): LengthAwarePaginator;
    

}