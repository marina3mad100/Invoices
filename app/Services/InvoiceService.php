<?php

namespace App\Services;

use App\Repository\InvoiceRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class InvoiceService
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepository
    ) {
    }

    public function create(array $data)
    {
        \DB::beginTransaction();
        try {

            $model =  $this->invoiceRepository->create($data);           
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

            $model =  $this->invoiceRepository->update($data , $id);

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
        return $this->invoiceRepository->delete($id);
    }

    public function all($where=[])
    {
        $items =  $this->invoiceRepository->where($where)->with(['user.userable'])->paginate(1);
        return $items;
        
    }

    public function get()
    {
        $items =  $this->invoiceRepository->with(['user.userable'])->all();        
        return $items;
        
    }

    public function filter($length , $request , $where=[])
    {
        $items =  $this->invoiceRepository->search_invoices($request ,$where, $length);
        return $items;
        
    }

    public function find($id)
    {
        $permission_list = [];
        $user =  $this->invoiceRepository->with(['user.userable'])->find($id);
        return $user;
    }

}