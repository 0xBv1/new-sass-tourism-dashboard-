<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\ChangeProfileImageRequest;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use App\Traits\Response\HasApiResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use HasApiResponse;

    public function me()
    {
        return $this->send(auth()->user());
    }

    public function changeProfileImage(ChangeProfileImageRequest $request)
    {
        $path = Storage::disk('public')->put('/clients', $request->file('image'));
        auth()->user()->update([
            'image' => asset(Storage::url($path))
        ]);
        return $this->ok();
    }

    public function update(UpdateProfileRequest $request)
    {
        auth()->user()->update($request->getSanitized());

        return $this->ok();
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        return $this->send(message: __('messages.client.logout'));
    }
}
