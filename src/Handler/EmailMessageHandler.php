<?php

namespace App\Handler;

use App\Message\Email as EmailMessage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class EmailMessageHandler
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Обработчик email-сообщений с RabbitMQ по AMQP
     *
     * @param EmailMessage $message
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function __invoke(EmailMessage $message): Response
    {
        $mail = json_decode($message->getContent(),false);

        // magically invoked when an instance of SampleMessage is dispatched
        $email = (new Email())
            ->from($mail->from)
            ->to($mail->to)
            ->priority($mail->priority)
            ->subject($mail->subject)
            ->text($mail->text)
            ->html($mail->html);

        $this->mailer->send($email);

        $response = new Response(json_encode([
            "result" => "ok",
            "details" => "Message to {$mail->to} sent"
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}