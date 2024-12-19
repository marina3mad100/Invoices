<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Notifications\InvoiceUpdate;
use App\Services\ClientService;
use App\Services\InvoiceService;
use App\Services\UserService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\InvoiceResource;


class InvoiceController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService,
        protected UserService $userService,
        protected ClientService $clientService,

        )
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices =  $this->invoiceService->get();
        return $this->sendResponse(
            $this->getList($invoices, $request, 'Invoice'),
            "Fetch All Invoices."
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        \DB::beginTransaction();
        if($request->validated()){
            try{         
                $data = $request->validated();
                $user = $this->userService->find($data['user_id']);
                if($user->userable->status->value != 1){
                    throw new \Exception('this user is not accepted');
                }

                $model = $this->invoiceService->create($data);
                $invoice =  $this->invoiceService->find($model->id);
                
                \DB::commit();
                // all good
            } catch (\Exception $e) {
                \DB::rollback();
                return $this->sendError('User is not Found or ane error has been occured');
            }              
            return $this->sendResponse(new InvoiceResource($invoice), "Invoice Created successfully."); 

        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $invoice =  $this->invoiceService->find($id);
        return $this->sendResponse(new InvoiceResource($invoice), "Invoice retrieved successfully."); 
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(InvoiceRequest $request, string $id)
    {
        \DB::beginTransaction();
        if($request->validated()){
            try{         
                $data = $request->validated();
                $user = $this->userService->find($data['user_id']);
                if($user->userable->status->value != 1){
                    throw new \Exception('this user is not accepted');
                }
                $this->invoiceService->update($data , $id);
                $invoice =  $this->invoiceService->find($id);
                
                \DB::commit();
                // all good
            } catch (\Exception $e) {
                \DB::rollback();
                return $this->sendError('User is not Found or ane error has been occured');
            }              
            return $this->sendResponse(new InvoiceResource($invoice), "Invoice Updated successfully."); 

        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */    
    public function destroy(string $id)
    {
        try{
            $user =  $this->invoiceService->find($id);
        }catch (\Exception $e) {
            return $this->sendError("Invoice does not exist");
        }
        $this->invoiceService->delete($id);
  

        return $this->sendResponse([], "Invoice deleted successfully.");



    }
}
