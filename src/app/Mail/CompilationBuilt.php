<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Compilations\Compilation;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class CompilationBuilt
 * @package App\Mail
 */
class CompilationBuilt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Compilation
     */
    protected $compilation;

    /**
     * Create a new message instance.
     *
     * @param Compilation $compilation
     */
    public function __construct(Compilation $compilation)
    {
        $this->compilation = $compilation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $name = 'YouCompilations. Compilation is collected! Date: ' . $this->compilation->created_at->toDateString();

        return $this
            ->subject($name)
            ->from(env('MAIL_FROM_ADDRESS'))
            ->view('emails.compilations.built')
            ->with(['compilation' => $this->compilation]);
    }
}
