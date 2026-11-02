<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Mengembalikan user yang sedang login sebagai model User.
     * Dengan tipe ini, editor tahu bahwa hasRole() (dari Spatie) tersedia.
     */
    protected function authUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }
}
