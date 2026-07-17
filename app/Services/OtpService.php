<?php
namespace App\Services;
use App\Models\User;
use App\Models\EmailOtp;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendOtpMail;


class OtpService{
    /**
     * Generate a random 6-digit OTP.
     */
   public function generateOtp(){
     return (string) rand(100000, 999999);
   }
   
   /**
     * Delete any existing OTP for the user.
     */
   public function deleteExistingOtp(User $user)
   {
       EmailOtp::where('user_id', $user->id)->delete();
   }

   public function sendOtp(User $user){
    //Remove previous otp
      $this->deleteExistingOtp($user);
    //Generate new otp
    $otp = $this->generateOtp();
      //Save Otp
      EmailOtp::create([
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => Carbon::now()->addMinutes(10)
      ]);

      //Send Mail
      Mail::to($user->email)->send(new SendOtpMail($otp));
   }

       /**
     * Verify OTP.
     */
    public function verifyOtp(User $user, string $otp): bool
    {
        $userOtp = EmailOtp::where('user_id', $user->id)
            ->where('otp', $otp)
            ->first();

        if (!$userOtp) {
            return false;
        }

        // Check expiry
        if (Carbon::now()->gt($userOtp->expires_at)) {
            $userOtp->delete();
            return false;
        }

        // OTP is valid
        $userOtp->delete();

        return true;
    }
}