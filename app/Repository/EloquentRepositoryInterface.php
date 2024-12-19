<?php
namespace App\Repository;


use  Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
/**
* Interface EloquentRepositoryInterface
* @package App\Repositories
*/
interface EloquentRepositoryInterface
{
  /**
   *@return Collection
    */
    public function all(): Collection;

    /**
    *@return LengthAwarePaginator
    */
    public function paginate($length): LengthAwarePaginator;

   /**
    * @param array $attributes
    * @return Model
    */
   public function create(array $attributes): Model;

      /**
    * @param array $attributes
    * @param int $id
    * @return bool
    */
    public function update(array $attributes , $id): bool;

   /**
    * @param $id
    * @return Model
    */
   public function find($id): Model;
   /**
    * @param $id
    * @return boolean
    */
    public function delete($id);
}