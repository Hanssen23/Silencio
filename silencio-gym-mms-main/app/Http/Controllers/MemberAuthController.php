<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Member;

class MemberAuthController extends Controller
{
    public function showRegister()
    {
        return view('members.register');
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:members,email|unique:users,email',
                'mobile_number' => 'required|string|regex:/^9\d{2}\s\d{3}\s\d{4}$/',
                'password' => 'required|min:6|confirmed',
                'accept_terms' => 'required|accepted',
            ], [
                'first_name.required' => 'First name is required for member registration',
                'last_name.required' => 'Last name is required for member registration',
                'email.required' => 'Email is required for member registration',
                'email.email' => 'Please enter a valid email address',
                'email.unique' => 'This email is already registered',
                'mobile_number.required' => 'Mobile number is required for member registration',
                'mobile_number.regex' => 'Please enter a valid 10-digit Philippine mobile number (e.g., 912 345 6789)',
                'password.required' => 'Password is required for member registration',
                'password.min' => 'Password must be at least 6 characters for member registration',
                'password.confirmed' => 'Password confirmation does not match',
                'accept_terms.required' => 'You must accept the Terms and Conditions to register',
                'accept_terms.accepted' => 'You must accept the Terms and Conditions to register',
            ]);

            // Clean mobile number (remove spaces and add +63 prefix)
            $mobileNumber = '+63' . preg_replace('/\D/', '', $validated['mobile_number']);

            // Get an available UID from the pool
            $availableUid = Member::getAvailableUid();
            
            if (!$availableUid) {
                return redirect()->back()->with('error', 'No UIDs available in the pool. Please contact administrator.')->withInput();
            }

            try {
                $payload = [
                    'uid' => $availableUid,
                    'member_number' => Member::generateMemberNumber(),
                    'membership' => null, // No plan assigned initially
                    'subscription_status' => 'not_subscribed',
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'mobile_number' => $mobileNumber,
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'status' => 'active',
                    'role' => 'member', // Explicitly assign member role
                    'accepted_terms_at' => now(), // Record terms acceptance timestamp
                ];
                
                $member = Member::create($payload);
            } catch (\Exception $e) {
                // If member creation fails, return the UID to the pool
                Member::returnUidToPool($availableUid);
                throw $e; // Re-throw the exception to be caught by outer catch block
            }

            // Do NOT auto-login after registration for security
            // Redirect to login page with success message
            return redirect()->route('login')->with('success', 'Registration successful! Please log in with your credentials.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return back with validation errors
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            // Log the error and return back with a generic error message
            \Log::error('Registration error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Registration failed. Please try again.')->withInput();
        }
    }
}


