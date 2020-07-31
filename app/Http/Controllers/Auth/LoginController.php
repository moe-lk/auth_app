<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */



    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var string
     */
    protected $username = 'username';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    /**
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    public function userOrg(Request $request)
    {
        $client = new Client(['base_uri' => env('GRAFANA_URL').'/api/']);
        if ($request->user() && (!($request->user()->principal->isEmpty()))  && $request->user()->principal[0]->roles) {
            dd($request->user()->principal[0]->roles->code);
            switch ($request->user()->principal[0]->roles->code) {
                case 'PRINCIPAL':
                    $response = $client->request('post','orgs/2/users',[
                        'headers' => [
                            'Authorization' => 'Bearer '.env('GRAFANA_KEY'),
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json'
                        ],
                        'body' => [
                            'role' => 'Viewer',
                            'loginOrEmail' => $request->user()->email
                        ]
                    ]);
                    dd($response);
                    if($response){
                        $data =
                        [
                            [
                                "orgId" => 2,
                                "userId" => $request->user()->id,
                                "role" => 'Viewer',
                                "name" => 'Schools',
                                "email" => $request->user()->email,
                                "login" => 'Schools',
                            ]
                        ];
                    }
                    break;
                case 'PROVINCIAL_COORDINATOR':
                    $data =
                        [
                            [
                                "orgId" => 3,
                                "userId" => $request->user()->user()->id,
                                "role" => 'Viewer',
                                "name" => 'province',
                                "email" => $request->user()->user()->email,
                                "login" => 'province',
                            ]
                        ];
                case 'ZONAL_COORDINATOR':
                    $data =
                        [
                            [
                                "orgId" => 3,
                                "userId" => $request->user()->user()->id,
                                "role" => 'Viewer',
                                "name" => 'zone',
                                "email" => $request->user()->user()->email,
                                "login" => 'zone',
                            ]
                        ];
                default:
                    # code...
                    break;
            }

        }
        return $data;
    }
}
