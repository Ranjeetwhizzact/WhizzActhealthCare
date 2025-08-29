<?php
namespace App\Services;
use Illuminate\Support\Facades\Crypt;

use App\Models\Product;

class EncryptService
{
    public function encryptEmail($email)
    {
        return substr(bin2hex(Crypt::encryptString($email)), 0, 8);
    }

    
    public function decryptEmail($encryptedEmail, $fullEmail)
    {
        return (strpos(bin2hex(Crypt::encryptString($fullEmail)), $encryptedEmail) === 0) ? $fullEmail : "Invalid Email";
    }
}
