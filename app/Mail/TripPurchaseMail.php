<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TripPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tripDetails;
    public $customerDetails;
    public $members;
    public $fileName;
    /**
     * Create a new message instance.
     */
    public function __construct($tripDetails, $customerDetails, $members, $fileName)
    {

        $this->tripDetails = $tripDetails;
        $this->customerDetails = $customerDetails;
        $this->members = $members;
        $this->fileName = $fileName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trip Purchase Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.tripPurchase',
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
}
