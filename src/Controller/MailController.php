<?php

namespace App\Controller;

use App\Message\Email as EmailMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;

class MailController extends AbstractController
{
    /**
     * Подготавливает письма и отправляет их в RabbitMQ
     *
     * @param MessageBusInterface $bus
     * @return Response
     */
    #[Route('/prepare_emails_to_rabbit')]
    public function prepareEmails(MessageBusInterface $bus): Response
    {
        // список адресов
        $mails = [
            'aaaa77771@yandex.ru',
            'aaaa77772@yandex.ru',
            'aaaa77773@yandex.ru',
            'aaaa77774@yandex.ru',
            'aaaa77775@yandex.ru',
            'aaaa77776@yandex.ru',
            'aaaa77777@yandex.ru',
            'aaaa77778@yandex.ru',
        ];

        foreach ($mails as $index => $mailAddress) {
            // подготовка письма
            $email = (object)[
                "from" => "mailer@sprut.com",
                "to" => $mailAddress,
                "priority" => Email::PRIORITY_HIGH,
                "subject" => "Test mail $index",
                "text" => "Test $index",
                "html" => "<p>test $index</p>"
            ];

            $message = new EmailMessage(json_encode($email));
            // отправка в RabbitMQ
            $bus->dispatch($message);
        }

        return new Response(sprintf(count($mails)." messages was published, check RabbitMQ", $message->getContent()));
    }
}