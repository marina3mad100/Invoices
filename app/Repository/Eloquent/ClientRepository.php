<?php

namespace App\Repository\Eloquent;

use App\Model\User;
use App\Models\Client;
use App\Repository\ClientRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{

   /**
    * UserRepository constructor.
    *
    * @param Client $model
    */
   public function __construct(Client $model)
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