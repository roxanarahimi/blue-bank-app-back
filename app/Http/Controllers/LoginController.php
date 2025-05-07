<?php

namespace App\Http\Controllers;

use App\Models\RxoRedis;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function getOtp(Request $request)
    {
//        Redis::set($request['mobile'], Random::generate(4, '0-9'));

        $user = User::where('mobile', $request->mobile)->first();
        if ($user) {

            if ((($user['scope'] == 'company' && $request['scope'] == 'company') || ($user['scope'] == 'user' && $request['scope'] == 'user'))

            ) {
                $xo = RxoRedis::where('key', $request->mobile)->first();
                if ($xo) {
                    $xo->update(['value' => Random::generate(4, '0-9')]);
                } else {
                    $xo = RxoRedis::create(['key' => $request['mobile'], 'value' => Random::generate(4, '0-9')]);
                }
                return response($xo, 200);

            } else {

                if ($user['scope'] === 'user' && $request['scope'] === 'company') {
                    $response = ["message" => ['شما قبلا به عنوان کاربر ثبت نام کردید. لطفا با شماره دیگری وارد شوید']];
                    return response($response, 422);
                }
                if ($user['scope'] === 'company' && $request['scope'] === 'user') {
                    $response = ["message" => ['شما قبلا به عنوان کارفرما ثبت نام کردید. لطفا با شماره دیگری وارد شوید']];
                    return response($response, 422);
                }
                if ($user['scope'] !== 'user' && $user['scope'] !== 'company') {
                    $response = ["message" => ['کاربر مجاز نیست با شماره دیگری وارد شوید']];
                    return response($response, 422);
                }

            }
        } else {
            $xo = RxoRedis::where('key', $request->mobile)->first();
            if ($xo) {
                $xo->update(['value' => Random::generate(4, '0-9')]);
            } else {
                $xo = RxoRedis::create(['key' => $request['mobile'], 'value' => Random::generate(4, '0-9')]);
            }
            return response($xo, 200);

        }


    }
    public function verifyOtp(Request $request)
    {
        try {
            $user = User::where('mobile', $request->mobile)->first();
            $code = RxoRedis::where('key', $request->mobile)->first();
//            if ($code['created_at'] > date() + 1) {
//                $code->delete();
//                $response = ["password" => ["کد وارد شده اشتباه است"]];
//                return response($response, 422);
//            }//todo check codes time.

            if ($user && $code) {

                if (((($user['scope'] == 'company' && $request['scope'] == 'company') || ($user['scope'] == 'user' && $request['scope'] === 'user')) && $request['password'] == $code['value'])) {

                    $token = $user->createToken('user')->accessToken;
                    $date = new \DateTime();
                    $date->add(new \DateInterval('PT2H'));
                    $user->update(['last_activity' => $date->format('Y-m-d H:i:s')]);
                    $code->delete();
                    return response(['user' => new UserResource($user), 'access_token' => $token, 'scope' => $request['scope'], 'expire' => date_format($date, 'Y-m-d H:i:s')], 200);
                } else {
                    $response = ["password" => ["کد وارد شده اشتباه است"]];
                    return response($response, 422);
                }
            } else if(!$user && $code) {

                if (request('password') == $code['value']) {
                    $date = new \DateTime();
                    $date->add(new \DateInterval('PT2H'));

                    $user = User::create(['mobile' => $request->mobile, 'scope' => $request['scope'], 'last_activity' => $date->format('Y-m-d H:i:s')]);
                    if($user['scope'] === 'company'){
                        Company::create(['user_id' => $user->id]);
                    }
                    $token = $user->createToken('user')->accessToken;
                    $code->delete();
                    return response(['user' => new UserResource($user), 'access_token' => $token, 'scope' => $request['scope'], 'expire' => date_format($date, 'Y-m-d H:i:s')], 200);

                } else {
                    $response = ["password" => ["کد وارد شده اشتباه است"]];
                    return response($response, 422);
                }
            }
        } catch (\Exception $exception) {
            return response($exception);

        }

    }

    public function currentUser()
    {
        try {
            return response(Auth()->user(), 200);
        } catch (\Exception $exception) {
            return response($exception->getMessage(), $exception->getCode());

        }

    }
}
