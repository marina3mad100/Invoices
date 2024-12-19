<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService

        )
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users =  $this->userService->get();
        return $this->sendResponse(
            $this->getList($users, $request, 'UserProfile'),
            "Fetch All Users."
        );
       // return $users;
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user =  $this->userService->findAllDetails($id);
        return $this->sendResponse(new UserProfileResource($user), "User retrieved successfully."); 
    }

  


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $user =  $this->userService->find($id);
        }catch (\Exception $e) {
            return $this->sendError("User does not exist");
        }
        
        $user->userable->delete();
        $this->userService->delete($id);
  

        return $this->sendResponse([], "user deleted successfully.");



    }
}
