<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Mail\welcomeMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RegisterController extends Controller
{
    public function create()
    {
        return view('register.create');
    }

    public function store(RegisterRequest $request)
    {
        $attributes = $request->validated();
        $user  = User::create($attributes);
        Auth::login($user);
        $user->assignRole('reader');
        $response = Mail::to($user->email)->send(new welcomeMail());
        return redirect('/');
    }
}
