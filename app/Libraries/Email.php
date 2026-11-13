<?php
// Envio de e-mails pelo SMTP configurado em app/configuracao_email.php (PHPMailer).

require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    // Retorna true se o e-mail foi aceito pelo servidor SMTP.
    public static function enviar($para, $nomeDestinatario, $assunto, $html, $textoSimples)
    {
        $config = require APP . '/configuracao_email.php';
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->Port = (int) $config['porta'];
            $mail->SMTPAuth = ($config['usuario'] ?? '') !== '';
            $mail->Username = $config['usuario'] ?? '';
            $mail->Password = $config['senha'] ?? '';

            $seguranca = $config['seguranca'] ?? 'tls';
            if ($seguranca === 'ssl') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // porta 465
            } elseif ($seguranca === 'nenhuma') {
                $mail->SMTPSecure = '';                               // só para testes locais (ex.: Mailpit)
                $mail->SMTPAutoTLS = false;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // porta 587 (Gmail)
            }
            $mail->Timeout = 20;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($config['remetente'], $config['nome_remetente'] ?? 'Histórias na Pérola');
            $mail->addAddress($para, $nomeDestinatario);

            $mail->isHTML(true);
            $mail->Subject = $assunto;
            $mail->Body = $html;
            $mail->AltBody = $textoSimples;

            $mail->send();
            return true;
        } catch (Throwable $erro) {
            // O erro detalhado vai para o log do Apache (C:\xampp\apache\logs\error.log).
            error_log('Erro ao enviar e-mail: ' . $mail->ErrorInfo . ' | ' . $erro->getMessage());
            return false;
        }
    }
}
