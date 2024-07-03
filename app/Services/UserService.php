<?php

namespace App\Services;

use App\Http\Resources\User\FullUserResource;
use App\Mail\SendRecoveryToken;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Mail, Password,};
use Illuminate\Support\Str;

class UserService
{
    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function attempt(Request $request): string
    {
        return Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);
    }

    public function getUserData(Request $request, User|Authenticatable $user, string $token): array
    {
        return array_merge(
            (new FullUserResource($user))->toArray($request),
            compact('token')
        );
    }

    public function sendRecoveryToken(Request $request): void
    {
        $email = $request->get('email');

        $token = Password::createToken(User::where(compact('email'))->firstOrFail());

        Mail::to($email)->queue(new SendRecoveryToken($email, $token));
    }

    public function resetPassword(Request $request)
    {
        return Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user
                    ->forceFill(['password' => Hash::make($password)])
                    ->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
    }
}
