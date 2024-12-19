<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\AdminService;
use App\Services\ClientService;
use App\Services\EmployeeService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AdminService $adminService,
        protected ClientService $clientService,
        protected EmployeeService $employeeService,

        )
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users =  $this->userService->all();
       // return $users;
        return view('invoicesSystem.pages.users.index' , get_defined_vars());
    }

    public function search(Request $request){
        $length = ($request->has('length')) ? $request->length : 1 ;
        $users =  $this->userService->filter($length , $request);
        $html = view('invoicesSystem.pages.users.search-items', compact('users'))->render();
        return response()->json(['success' => true, 'html' => $html]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::select('id as value' , 'name as label')->get();
       // return $permissions;
        return view('invoicesSystem.pages.users.create' , get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        \DB::beginTransaction();
        if($request->validated()){
            try{         
                $data = $request->validated();
                //dd($data['user_type']);
                $data['user_name']=$data['first_name'].'_'.$data['last_name'];
                if($data['user_type'] == 'client'){
                    $model = $this->clientService->create($data);
                    $data['profile_photo_path'] = $model->image;
                    $user = $this->userService->create($data);
                    $model->user()->save($user);   
                }else if($data['user_type'] == 'employee'){
                    $model = $this->employeeService->create($data);
                    $data['profile_photo_path'] = $model->image;
                    $user = $this->userService->create($data);
                    $model->user()->save($user);
                    $this->userService->createRolePermissionsOfUser($user , $data['permissions']);             

                }else if($data['user_type'] == 'admin'){
                    $model = $this->adminService->create($data);
                    $data['profile_photo_path'] = $model->image;
                    $user = $this->userService->create($data);
                    $model->user()->save($user);  
                    $this->userService->createRolePermissionsOfUser($user , $data['permissions']);             
              
                }
                
                \DB::commit();
                // all good
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error' => $e->getMessage()]);
            }              
             return response()->json(['success' => 'Form submitted successfully.' , 'data'=>$model , 'user'=>$user]);

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
        $user =  $this->userService->find($id);
        $permissions = Permission::select('id as value' , 'name as label')->get();
        return view('invoicesSystem.pages.users.edit' , get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(UserRequest $request, string $id)
    {
        \DB::beginTransaction();
        if($request->validated()){
            try{         
                $data = $request->validated();
                $user =  $this->userService->find($id);
                //dd($data['user_type']);
                $data['user_name']=$data['first_name'].'_'.$data['last_name'];
                if($data['user_type'] == 'client'){
                    $model = $this->clientService->update($data , $user->userable_id);
                    $user_morph =  $this->clientService->find($user->userable_id);
                    $data['profile_photo_path'] = $user_morph->image;                   
                    $user = $this->userService->update($data , $id);
                    //$model->user()->save($user);   
                }else if($data['user_type'] == 'employee'){
                    $model = $this->employeeService->update($data ,  $user->userable_id);
                  //  return $user->userable_id;
                    $user_morph =  $this->employeeService->find($user->userable_id);
                   // dd($user_morph->image);
                    $data['profile_photo_path'] = $user_morph->image;
                    $this->userService->update($data , $id);
                    
                    $this->userService->createRolePermissionsOfUser($user , $data['permissions']);             

                }else if($data['user_type'] == 'admin'){
                    $model = $this->adminService->update($data , $user->userable_id);
                    $user_morph =  $this->adminService->find($user->userable_id);
                    $data['profile_photo_path'] = $user_morph->image;                    
                    $this->userService->update($data , $id);
                    $this->userService->createRolePermissionsOfUser($user , $data['permissions']);             
              
                }
                
                \DB::commit();
                // all good
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json(['error' => $e->getMessage()]);
            }              
             return response()->json(['success' => 'Form submitted successfully.' , 'data'=>$model , 'user'=>$user]);

        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user =  $this->userService->find($id);
        $user->userable->delete();
        $this->userService->delete($id);
        return redirect()->back()->with('success' , 'User deleted successfully');

    }
}
