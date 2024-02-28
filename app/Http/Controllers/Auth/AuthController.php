<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function index(Request $request)
    {

        $response = Http::get('https://sso.impellink.net/get-info', [
            'token' => $request->token
        ]);
        // return response body
        $result = json_decode($response->body());
        // return $result;
        if ($result) {
            if ($result->status == 'success') {
                // make new session from result
                $request->session()->put('id', $result->decode->user_id);
                $request->session()->put('name', $result->decode->name);
                $request->session()->put('email', $result->decode->email);
                $request->session()->put('role', $result->decode->role);
                $request->session()->put('telephone', $result->decode->telephone);

                // return redirect('/');
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else {
            abort(403, 'Token Invalid.');
        }
    }
}
