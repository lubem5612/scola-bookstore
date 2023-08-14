<?php

namespace Transave\ScolaBookstore\Http\Controllers;

use Illuminate\Http\Request;
use Transave\ScolaBookstore\Actions\Auth\ChangeEmail;
use Transave\ScolaBookstore\Actions\Auth\ChangePassword;
use Transave\ScolaBookstore\Actions\Auth\Login;
use Transave\ScolaBookstore\Actions\Auth\Register;
use Transave\ScolaBookstore\Actions\Auth\ResendEmailVerification;
use Transave\ScolaBookstore\Actions\Auth\VerifyEmail;
use Transave\ScolaBookstore\Helpers\ResponseHelper;
use Transave\ScolaBookstore\Http\Models\User;

class AuthController extends Controller
{
    use ResponseHelper;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['user', 'logout']);

    }

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function login(Request $request)
    {
        $data = $request->only(['email', 'password']);
        return (new Login($data))->execute();
    }

    /**
     * Register a new account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function register(Request $request)
    {
        return (new Register($request->except(['profile_image'])))->execute();
    }
    

    /**
     * Get authenticated user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function user(Request $request)
    {
        try {
            return $this->sendSuccess($request->user(), 'authenticated user retrieved successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    /**
     * Log out user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return $this->sendSuccess(null, 'user logged out successfully');
        }catch (\Exception $e) {
            return $this->sendServerError($e);
        }
    }

    /**
     * Verify created account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function verifyEmail(Request $request)
    {
        return (new VerifyEmail($request->all()))->execute();
    }

    /**
     * Resend account verification token for auth user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Transave\ScolaCbt\Helpers\Response
     */
    public function resendToken(Request $request)
    {
        return (new ResendEmailVerification($request->user()))->execute();
    }


    public function updateEmail(Request $request, User $user)
    {
        $input = $request->all();

        $action = new ChangeEmail($user, $input);
        $response = $action->execute();

        return response()->json($response->getContent(), $response->getStatusCode());

    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();

        return (new ChangePassword($user, $inputs))->execute();
    }
}