<?php

namespace Modules\Auth\Http\Controllers;

use App\Core\Template\TemplateInterface;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Modules\Auth\Traits\ResetPasswordsUsers;
use Modules\Auth\Traits\AuthenticatesUsers;
use Modules\Auth\Traits\RegistersUsers;
use Modules\User\Models\User;

class AuthController extends AdminController
{
    use AuthenticatesUsers, RegistersUsers, ResetPasswordsUsers;

    public function __construct(TemplateInterface $template)
    {
        parent::__construct($template);

        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Auth index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->tpl->setData('title', trans('auth::language.home'));
        $this->tpl->setTemplate('auth::home');

        return $this->tpl->render();
    }

    /**
     * Action controller
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function action(Request $request)
    {
        if (! $request->ajax()) {
            abort(405);
        }

        $status = 200;
        $response = [];

        switch ($request->action) {
            case 'login':
                $response = $this->login($request);
                break;
            case 'register':
                $response = $this->register($request);
                break;
            case 'forgot':
                $response = $this->forgot($request);
                break;
            case 'reset':
                $response = $this->resetPassword($request);
                break;
            default:
                $response = [
                    'status' => 500,
                    'message' => trans('language.error_unknown_message')
                ];
                break;
        }

        return response()->json($response, $status);
    }

    /**
     * Activation Controller
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activation($token)
    {
        $this->tpl->setData('title', trans('auth::language.activation'));
        $this->tpl->setTemplate('auth::activation');

        $user = User::where('token', $token)->where('activated', false)->firstOrFail();
        $user->update(['activated' => true, 'token' => '']);

        return $this->tpl->render();
    }

    /**
     * Reset password user
     *
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reset($token)
    {
        $this->tpl->setData('title', trans('auth::language.reset_password'));
        $this->tpl->setTemplate('auth::reset');

        $user = User::where('token', $token)->firstOrFail();

        $this->tpl->setData('user', $user);
        return $this->tpl->render();
    }

    /**
     * Create token user
     *
     * @param User $user
     */
    protected function createTokenForUser(User $user)
    {
        $hash = encrypt($user->id . $user->email . time());
        $hash  = md5($hash);
        $user->update(['token' => $hash]);
    }
}
