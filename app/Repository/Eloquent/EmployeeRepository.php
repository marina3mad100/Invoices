<?php

namespace App\Repository\Eloquent;

use App\Model\User;
use Illuminate\Support\Collection;
use App\Models\Employee;
use App\Repository\EmployeeRepositoryInterface;
use \Illuminate\Database\Eloquent\Model;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param Employee $model
    */
   public function __construct(Employee $model)
   {
       parent::__construct($model);
   }
   
	/**
   *@return Collection
    */
    public function allAccepted(): Collection
	{
	    try{
            return $this->model->where($this->where)->with($this->with)->accepted()->get();

        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }	
	}

}