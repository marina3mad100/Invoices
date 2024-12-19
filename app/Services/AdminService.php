<?php

namespace App\Services;

use App\Repository\AdminRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use DB;
use File;

class AdminService
{
    public function __construct(
        protected AdminRepositoryInterface $adminRepository
    ) {
    }

    public function create(array $data)
    {
        \DB::beginTransaction();
        try { 

            if(isset($data['image']) && $data['image'] != NULL){
                $file = $data['image'];
                $fileName = md5(time()).'.'.$file->extension();            
                $data['image'] = $fileName;

            }else{
                    $data['image'] = NULL;
            }

            $model =  $this->adminRepository->create($data);
            
            $path = Storage::url('admin'.$model->id);
            
            \File::makeDirectory($path, $mode = 0777, true, true);
        
            if($data['image'] != NULL){
                $file->move(('storage/admin'.$model->id.'/'),$model->image);

            } 
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
            if(isset($data['image']) && $data['image'] != NULL){
                $file = $data['image'];
                $fileName = md5(time()).'.'.$file->extension();            
                $data['image'] = $fileName;

            }
            
            $model =  $this->adminRepository->update($data, $id);
            
            $path = Storage::url('admin'.$id);
            
            \File::makeDirectory($path, $mode = 0777, true, true);
        
            if(isset($data['image']) && $data['image'] != NULL){
                $file->move(('storage/admin'.$id.'/'),$data['image']);

            } 
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
        return $this->adminRepository->delete($id);
    }

    public function all()
    {
        return $this->adminRepository->with(['user:id,userable_id,userable_type'])->all();
    }


    public function changeStatus($id , $status)
    {
        return $this->adminRepository->change_status($id , $status);
        
    }

    public function find($id)
    {
        return $this->adminRepository->find($id);
    }
}