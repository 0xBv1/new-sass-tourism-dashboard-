<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Password\ForgetPasswordRequest;
use App\Http\Requests\Api\Password\OtpVerifyRequest;
use App\Http\Requests\Api\Password\ResetPasswordRequest;
use App\Models\Client;
use App\Models\PasswordReset;
use App\Notifications\Client\ForgetPasswordNotification;
use App\Traits\Response\HasApiResponse;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class PasswordController extends Controller
{
    use HasApiResponse;

    public function forget(ForgetPasswordRequest $request)
    {
        $otp = rand(100000, 999999);

        if ($reset = PasswordReset::whereEmail($request->get('email'))->first()) {
            if (now()->diffInSeconds($reset->created_at) < 60) {
                return $this->send(
                    message: __('messages.notifications.password.forget.try_after_60'),
                    statusCode: Response::HTTP_BAD_REQUEST
                );
            }
        }

        PasswordReset::updateOrCreate(
            $request->only('email'), [
            'email' => $request->get('email'),
            'token' => $otp,
            'created_at' => now(),
        ]);

        $client = Client::whereEmail($request->get('email'))->first();

        $client->notify(new ForgetPasswordNotification($otp));

        return $this->send(
            message: __('messages.password.forget')
        );
    }

    public function otpVerify(OtpVerifyRequest $request)
    {

        $reset = PasswordReset::where([
            'email' => $request->get('email'),
        ])->first();

        if ($reset->expired()) {
            return $this->send(
                message: __('messages.password.otp_expired'),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }

        if ($reset->token != $request->get('otp')) {
            return $this->send(
                message: __('messages.password.otp_invalid'),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }

        return $this->ok();
    }

    public function reset(ResetPasswordRequest $request)
    {
        $reset = PasswordReset::where([
            'email' => $request->get('email'),
        ])->first();

        if ($reset->expired()) {
            return $this->send(
                message: __('messages.password.otp_expired'),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }

        if ($reset->token != $request->get('otp')) {
            return $this->send(
                message: __('messages.password.otp_invalid'),
                statusCode: Response::HTTP_BAD_REQUEST
            );
        }

        Client::whereEmail($request->get('email'))
            ->first()
            ->update([
                'password' => Hash::make($request->get('password'))
            ]);

        $reset->delete();

        return $this->send(
            message: __('messages.password.reset')
        );
    }
}
