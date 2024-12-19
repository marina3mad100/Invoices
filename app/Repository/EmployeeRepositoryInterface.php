<?php
namespace App\Repository;

use App\Model\User;
use App\Model\Contractor;
use \Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;

interface EmployeeRepositoryInterface
{


	/**
   *@return Collection
    */
    public function allAccepted(): Collection;
}