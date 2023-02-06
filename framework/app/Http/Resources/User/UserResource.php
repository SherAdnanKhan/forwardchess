<?php

namespace App\Http\Resources\User;

use App\Models\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 * @package App\Http\Resources\User
 */
class UserResource extends JsonResource
{
    protected $rememberToken;

    /**
     * @param mixed $rememberToken
     *
     * @return UserResource
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id'             => $user->id,
            'email'          => $user->email,
            'firstName'      => $user->firstName,
            'lastName'       => $user->lastName,
            'mobile'         => $user->mobile,
            'emailConfirmed' => $user->email_verified_at !== null,
            'nickname'       => $user->nickname,
            'subscribed'     => $user->subscribed,

            $this->mergeWhen($request->user() && $request->user()->isAdmin, [
                'superAdmin' => $user->isSuperAdmin(),
                'active'     => $user->active,
                'lastLogin'  => $user->lastLogin,
            ]),

            'created_at' => $user->getCreatedAtFormatted(),
            'updated_at' => $user->getUpdatedAtFormatted()
        ];
    }
}
