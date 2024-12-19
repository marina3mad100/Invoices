<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\UserRequest;
use App\Models\Client;
use App\Models\User;
use App\Notifications\InvoiceUpdate;
use App\Services\ClientService;
use App\Services\InvoiceService;
use App\Services\UserService;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

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
    public function index()
    {

        if(\Auth::user()->userable_type != Client::class){
            $invoices =  $this->invoiceService->all();
        }else{
            $invoices =  $this->invoiceService->all(['user_id'=>\Auth::user()->id]);
        }
        
       // return $invoices;

        return view('invoicesSystem.pages.invoices.index' , get_defined_vars());
    }

    public function search(Request $request){
        $length = ($request->has('length')) ? $request->length : 1 ;
        if(\Auth::user()->userable_type != Client::class){
            $invoices =  $this->invoiceService->filter($length,$request);
        }else{
            $invoices =  $this->invoiceService->filter($length ,$request , ['user_id'=>\Auth::user()->id]);
        }
        $html = view('invoicesSystem.pages.invoices.search-items', compact('invoices'))->render();
        return response()->json(['success' => true, 'html' => $html]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(\Auth::user()->userable_type != Client::class){
            $users =  $this->clientService->allAccepted();
        }else{
            $users =  $this->clientService->allAccepted(['id'=>\Auth::user()->userable_id]);
        }    
        
        //return $users;
       // return $permissions;
        return view('invoicesSystem.pages.invoices.create' , get_defined_vars());
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
                $model = $this->invoiceService->create($data);
                
                \DB::commit();
                // all good
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error' => $e->getMessage()]);
            }              
             return response()->json(['success' => 'Form submitted successfully.' , 'data'=>$model , 'user'=>$model]);

        }
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice =  $this->invoiceService->find($id);
        if(\Auth::user()->userable_type != Client::class){
            $users =  $this->clientService->allAccepted();
        }else{
            $users =  $this->clientService->allAccepted(['id'=>\Auth::user()->userable_id]);
        }   
         return view('invoicesSystem.pages.invoices.edit' , get_defined_vars());
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
                $this->invoiceService->update($data , $id);
                
                \DB::commit();
                // all good
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error' => $e->getMessage()]);
            }              
             return response()->json(['success' => 'Form submitted successfully.']);

        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $this->invoiceService->delete($id);
        return redirect()->back()->with('success' , 'Invoice deleted successfully');

    }
}
