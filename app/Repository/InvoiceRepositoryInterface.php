<?php
namespace App\Repository;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;


interface InvoiceRepositoryInterface
{

    /**
    *@param Request $request
    *@param array $where 
    *@param int $length 
    *@return LengthAwarePaginator
    */
    public function search_invoices($request ,$where , $length): LengthAwarePaginator;
    

}