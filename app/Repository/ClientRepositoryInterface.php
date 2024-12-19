<?php
namespace App\Repository;

use App\Model\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ClientRepositoryInterface
{

	/**
   *@return Collection
    */
    public function allAccepted(): Collection;
}