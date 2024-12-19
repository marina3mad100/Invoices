<?php

namespace App\Services;

use App\Repository\ClientRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ClientService
{
    public function __construct(
        protected ClientRepositoryInterface $ClientRepository
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

            $model =  $this->ClientRepository->create($data);
            
            $path = Storage::url('client'.$model->id);
            
            \File::makeDirectory($path, $mode = 0777, true, true);
        
            if($data['image'] != NULL){
                $file->move(('storage/client'.$model->id.'/'),$model->image);

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
            
            $model =  $this->ClientRepository->update($data, $id);
            
            $path = Storage::url('client'.$id);
            
            \File::makeDirectory($path, $mode = 0777, true, true);
        
            if(isset($data['image']) && $data['image'] != NULL){
                $file->move(('storage/client'.$id.'/'),$data['image']);

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
        return $this->ClientRepository->delete($id);
    }

    public function all()
    {
        return $this->ClientRepository->with(['user:id,userable_id,userable_type'])->all();
    }
    public function allAccepted($where=[])
    {
        return $this->ClientRepository->where($where)->with(['user:id,userable_id,userable_type'])->allAccepted();
    }
    public function changeStatus($id , $status)
    {
        return $this->ClientRepository->change_status($id , $status);
        
    }

    public function find($id)
    {
        return $this->ClientRepository->find($id);
    }
}