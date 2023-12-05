<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\ResendTripPurchaseEmail;
use App\Models\Customer;
use App\Models\EmailManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PharIo\Manifest\Email;

class EmailManagingController extends Controller
{
    function getEmails()
    {
        $emails = EmailManager::all();

        return response()->json($emails);
    }
    function resendMail(Request $request)
    {
        $customer = Customer::where('id', $request->customer_id)->first();
        $user = User::where('id', $customer->user_id)->first();

        // Get the contents of the email template file
        $path = public_path($request->path_of_email);
        $emailContent = file_get_contents($path);

        // dd($emailContent);

        try {
            Mail::html($emailContent, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Resend Trip Purchase Email');
            });
            EmailManager::create([
                'customer_id' => $request->customer_id,
                'path_of_email' => $request->path_of_email
            ]);
        } catch (\Exception $e) {
            // Log or echo the exception message
            return response()->json(['error' => 'Failed to send email'], 500);
        }
    }
}
