<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->create($request);
        event(new Registered($user));

        Auth::login($user);

        return redirect($this->redirectTo);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        $data = $request->all();

        $avatarUploadedFile = $request->file('avatar');
        $avatarImageName = md5($avatarUploadedFile->getFilename().time()) . '.' . $avatarUploadedFile->extension();
        $avatarImage = Image::make($avatarUploadedFile->path());

        Storage::put(
            'public/users/avatar/' . $avatarImageName,
            $avatarImage->stream($avatarUploadedFile->extension())
        );

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'birth_date' => $data['birth_date'],
            'avatar' => $avatarImageName,
            'password' => Hash::make($data['password']),
        ]);
    }
}
