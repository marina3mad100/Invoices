<?php

namespace App\Services;

use App\Repository\EmployeeRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository
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
            
            $model =  $this->employeeRepository->create($data);
            
            $path = Storage::url('employee'.$model->id);
            
            \File::makeDirectory($path, $mode = 0777, true, true);
        
            if($data['image'] != NULL){
                $file->move(('storage/employee'.$model->id.'/'),$model->image);

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
            
            $model =  $this->employeeRepository->update($data, $id);
            
            $path = Storage::url('employee'.$id);
            
            \File::makeDirectory($path, $mode = 0777, true, true);
        
            if(isset($data['image']) && $data['image'] != NULL){
                $file->move(('storage/employee'.$id.'/'),$data['image']);

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
        return $this->employeeRepository->delete($id);
    }

    public function all()
    {
        return $this->employeeRepository->with(['user:id,userable_id,userable_type'])->all();

        // return $this->ContractorRepository->with(['user:id,userable_id,userable_type'])->query()->get();
       // return $this->ContractorRepository->all();

    }

    public function changeStatus($id , $status)
    {
        return $this->employeeRepository->change_status($id , $status);
        
    }

    public function find($id)
    {
        return $this->employeeRepository->find($id);
    }
}