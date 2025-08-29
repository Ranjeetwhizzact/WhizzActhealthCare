<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Services\EncryptService;
// use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Crypt;
class PatientLogMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $encryptedproposal_number;
    public function __construct($patient)
    {
        $this->patient = $patient;
$this->encryptedproposal_number = Crypt::encrypt($patient->proposal_number);
    }
   
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Patient Log Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.patient',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        
      
        return $this->to($this->patient->email) // Ensure this line is included
            ->subject('New Patient Log Entry')
            ->view('emails.patient')
            ->with([
                'first_name' => $this->patient->first_name,
                'email' => $this->patient->email,
                'address' => $this->patient->address,
                'encryptedproposal_number' => $this->encryptedproposal_number,  // Correct way to pass encrypted email
                // 'insurance_company_email' => $this->patient->email,
            ]);
                        // ->when(file_exists($pdfPath), function ($mail) use ($pdfPath) {
                        //     $mail->attach($pdfPath, [
                        //         'as' => 'PatientLog.pdf',
                        //         'mime' => mime_content_type($pdfPath),
                        //     ]);
                        // })
                      
                        // ->when(file_exists($profileImagePath), function ($mail) use ($profileImagePath) {
                        //     $mail->attach($profileImagePath, [
                        //         'as' => 'ProfileImage.jpg',
                        //         'mime' => mime_content_type($profileImagePath),
                        //     ]);
                        // });
        }
        // private function encryptEmail($email)
        // {
        //     return $this->encryptService->encryptEmail($email);
        // }
    
        
    

    }
