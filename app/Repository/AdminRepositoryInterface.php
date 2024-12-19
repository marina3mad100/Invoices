<?php
namespace App\Repository;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface AdminRepositoryInterface
{



	/**
   *@return Collection
    */
    public function allAccepted(): Collection;
}