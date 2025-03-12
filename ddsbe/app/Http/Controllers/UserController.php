<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Traits\ApiResponser;
use DB;

Class UserController extends Controller {
    private $request;
    public function __construct(Request $request){
        $this->request = $request;
 }

    protected function successResponse($data, $code = Response::HTTP_OK){
        return response()->json(['data' => $data], $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    public function index(){
        $users = User::all();
        return $this->successResponse($users);
    }

    public function getUsers(){
        $users = User::all();
        return $this->successResponse($users);
    }

    public function add(Request $request){
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender' => 'required|in:Male,Female',
        ];
        
        $this->validate($request, $rules);
        $user = User::create($request->all());
        return $this->successResponse($user, Response::HTTP_CREATED);
    }

    public function show($id){
        $user = User::findOrFail($id);

        /*if (!$user) {
            return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);
        }*/

        return $this->successResponse($user);
    }

    public function update(Request $request, $id){
        $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'gender' => 'in:Male,Female',
        ];

        $this->validate($request, $rules);
        $user = User::findOrFail($id);

        /*if (!$user) {
            return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);
        }*/

        $user->fill($request->all());
        //if no changes happen
        if ($user->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->save();
        return $this->successResponse($user);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->errorResponse('User ID does not exist', Response::HTTP_NOT_FOUND);

        //return $this->successResponse(['message' => 'User deleted successfully']);
    }
}