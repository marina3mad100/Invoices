<?php

namespace App\Repository\Eloquent;

use App\Model\User;
use App\Models\Admin;
use App\Repository\AdminRepositoryInterface;
use App\Repository\Eloquent\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param Admin $model
    */
   public function __construct(Admin $model)
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