<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string', 'in:buyer,vendor'],
            'id_card' => ['required_if:role,vendor', 'nullable', 'image', 'max:4096'],
        ]);

        $role = $request->input('role', 'buyer') ?: 'buyer';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        // Assign role using Spatie Permission
        $roleModel = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        $user->assignRole($roleModel);

        // Handle merchant identity verification (KYC)
        if ($role === 'vendor') {
            $kycPhoto = null;
            if ($request->hasFile('id_card')) {
                try {
                    $media = $user->addMedia($request->file('id_card'))->toMediaCollection('kyc_documents');
                    $kycPhoto = $media->getUrl();
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('KYC document upload error: ' . $e->getMessage());
                }
            }

            $user->update([
                'id_card_photo' => $kycPhoto,
                'kyc_status' => 'pending'
            ]);
        } else {
            $user->update([
                'kyc_status' => 'verified' // Buyers are verified by default
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
