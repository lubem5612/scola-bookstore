<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\User\CreateUser;
use Transave\ScolaBookstore\Actions\User\DeleteUser;
use Transave\ScolaBookstore\Actions\User\GetUser;
use Transave\ScolaBookstore\Actions\User\SearchUser;
use Transave\ScolaBookstore\Actions\User\UpdateUser;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\User;

class UserController extends Controller
{
    use ResponseHelper;
    private  User $user;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(['user'])->only(['update']);
    }


    /**
     * Get a listing of users
     *
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function index()
    {
        return (new SearchUser(User::class, ['school']))->execute();
    }



    /**
     * Show a specified user
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function show($id)
    {
        return (new GetUser(['id' => $id]))->execute();
    }



    /**
     * Update a specified user
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function update(Request $request, $id)
    {
        $inputs = $request->merge(['user_id' => $id])->all();
        return (new UpdateUser($inputs))->execute();
    }


    /**
     * Delete a specified user
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function destroy($id)
    {
        return (new DeleteUser(['id' => $id]))->execute();
    }
}