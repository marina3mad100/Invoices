<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Employee;
use App\Repository\UserRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function create(array $data)
    {
        \DB::beginTransaction();
        try {
            
            
            $data['name'] = $data['first_name'].'_'.$data['last_name'];
            $data['password'] = Hash::make($data['password']);
            $model =  $this->userRepository->create($data);

            //$this->userRepository->create_role_permisions_of_user($model->id , $data);
           
            \DB::commit();
        // all good
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }
            
            

        return $model;
    }

    public function update(array $data, $id)
    {
        \DB::beginTransaction();
        try {            
            $data['name'] = $data['first_name'].'_'.$data['last_name'];
            if(!empty($data['password'])){
                $data['password'] = Hash::make($data['password']);

            }else{
                unset($data['password']);
            }
            $model =  $this->userRepository->update($data , $id);

            //$this->userRepository->create_role_permisions_of_user($model->id , $data);
           
            \DB::commit();
        // all good
        } catch (\Exception $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }
            
            

        return $model;

    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }
    public function get()
    {
        $items =  $this->userRepository->with(['userable','permissions'])->all();
        $items->map(function($row){
            if($row->userable_type == Admin::class){
                $row->user_type = 'Admin';
            }

            else if($row->userable_type == Employee::class){
                $row->user_type = 'Employee';
            }

            else if($row->userable_type == Client::class){
                $row->user_type = 'Client';
            }
            return $row;

        });
        
        return $items;
        
    }

    public function all()
    {
        $items =  $this->userRepository->with(['userable'])->paginate(1);
        $items->getCollection()->transform(function ($row) {
            if($row->userable_type == Admin::class){
                $row->user_type = 'Admin';
            }

            else if($row->userable_type == Employee::class){
                $row->user_type = 'Employee';
            }

            else if($row->userable_type == Client::class){
                $row->user_type = 'Client';
            }
            return $row;
        });
        return $items;
        
    }

    public function filter($length , $request)
    {
        $items =  $this->userRepository->search_users($request , $length);
        $items->getCollection()->transform(function ($row) {
            if($row->userable_type == Admin::class){
                $row->user_type = 'Admin';
            }

            else if($row->userable_type == Employee::class){
                $row->user_type = 'Employee';
            }

            else if($row->userable_type == Client::class){
                $row->user_type = 'Client';
            }
            return $row;
        });
        return $items;
        
    }

    public function find($id)
    {
        $permission_list = [];
        $user =  $this->userRepository->with(['userable','permissions:id'])->find($id);
        if($user->permissions->count() > 0){
            foreach($user->permissions as $premission){
                $permission_list[] = $premission->id;
            }
        }
        $user->permission_list = $permission_list;
        return $user;
    }

    public function findAllDetails($id)
    {
        $user =  $this->userRepository->with(['userable','permissions','invoices'])->find($id);
        if($user->userable_type == Admin::class){
            $user->user_type = 'Admin';
        }

        else if($user->userable_type == Employee::class){
            $user->user_type = 'Employee';
        }

        else if($user->userable_type == Client::class){
            $user->user_type = 'Client';
        }
        return $user;
    }


    public function createRolePermissionsOfUser($user, $permissions){
        return $this->userRepository->create_role_permisions_of_user($user , $permissions);
    }


}