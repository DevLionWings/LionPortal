<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        $server = env('DB_DATABASE_PGSQL');
        if($server == 'PRD'){
            return new Envelope(
                subject: 'Lion Wings - Helpdesk Ticket : '.  $this->mailData['ticketno']
            );
        } else {
            return new Envelope(
                subject: 'STAGING - Lion Wings - Helpdesk Ticket : '.  $this->mailData['ticketno']
            );
        }
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.template.general',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments() : array
    {
        return [];
    }

    public function build()
    {
        return $this->from('admin@lionwings.com', 'no-reply@helpdesk');
    }
}
