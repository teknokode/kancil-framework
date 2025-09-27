<?php

namespace Kancil\Core;

class Email
{
    private $smtpConfig = [];
    private $fromEmail;
    private $fromName;
    private $toEmail;
    private $toName;
    private $subject;
    private $message;
    private $headers = [];
    private $attachments = [];
    private $boundary;

    public function __construct()
    {
        $this->boundary = md5(uniqid(time()));
    }

    // Set SMTP configuration
    public function setSMTPConfig($host, $port, $username, $password, $encryption = 'tls')
    {
        $this->smtpConfig = [
            'host' => $host,
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'encryption' => $encryption
        ];
    }

    // Set sender email and name
    public function setFrom($email, $name = '')
    {
        $this->fromEmail = $email;
        $this->fromName = $name;
    }

    // Set recipient email and name
    public function setTo($email, $name = '')
    {
        $this->toEmail = $email;
        $this->toName = $name;
    }

    // Set email subject
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    // Set HTML message
    public function setHtmlMessage($htmlContent)
    {
        $this->message = $htmlContent;
    }

    // Add attachment
    public function addAttachment($filePath)
    {
        if (file_exists($filePath)) {
            $this->attachments[] = $filePath;
        } else {
            throw new Exception("File not found: $filePath");
        }
    }

    // Send email using SMTP
    public function send()
    {
        if (empty($this->smtpConfig)) {
            throw new Exception("SMTP configuration is not set.");
        }

        $host = $this->smtpConfig['host'];
        $port = $this->smtpConfig['port'];
        $username = $this->smtpConfig['username'];
        $password = $this->smtpConfig['password'];
        $encryption = $this->smtpConfig['encryption'];

        $socket = fsockopen(($encryption === 'ssl' ? "ssl://" : "") . $host, $port, $errno, $errstr, 30);

        if (!$socket) {
            throw new \Exception("Failed to connect to SMTP server: $errstr ($errno)");
        }

        $this->smtpCommand($socket, "EHLO localhost");
        if ($encryption === 'tls') {
            $this->smtpCommand($socket, "STARTTLS");
            stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->smtpCommand($socket, "EHLO localhost");
        }

        $this->smtpCommand($socket, "AUTH LOGIN");
        $this->smtpCommand($socket, base64_encode($username));
        $this->smtpCommand($socket, base64_encode($password));
        $this->smtpCommand($socket, "MAIL FROM: <{$this->fromEmail}>");
        $this->smtpCommand($socket, "RCPT TO: <{$this->toEmail}>");
        $this->smtpCommand($socket, "DATA");

        $emailHeaders = $this->generateHeaders();
        $emailBody = $this->generateBody();

        $this->smtpCommand($socket, $emailHeaders . "\r\n" . $emailBody . "\r\n.");
        $this->smtpCommand($socket, "QUIT");

        fclose($socket);
    }

    // Generate email headers
    private function generateHeaders()
    {
        $from = $this->fromName ? "{$this->fromName} <{$this->fromEmail}>" : $this->fromEmail;
        $to = $this->toName ? "{$this->toName} <{$this->toEmail}>" : $this->toEmail;

        $headers = "From: $from\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: {$this->subject}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"{$this->boundary}\"";

        return $headers;
    }

    // Generate email body
    private function generateBody()
    {
        $body = "--{$this->boundary}\r\n";
        $body .= "Content-Type: text/html; charset=\"UTF-8\"\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "{$this->message}\r\n";

        foreach ($this->attachments as $filePath) {
            $fileName = basename($filePath);
            $fileData = chunk_split(base64_encode(file_get_contents($filePath)));

            $body .= "--{$this->boundary}\r\n";
            $body .= "Content-Type: application/octet-stream; name=\"{$fileName}\"\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= "{$fileData}\r\n";
        }

        $body .= "--{$this->boundary}--";

        return $body;
    }

    // Execute SMTP command and check response
    private function smtpCommand($socket, $command)
    {
        fwrite($socket, $command . "\r\n");
        $response = fgets($socket, 512);

        if (strpos($response, "4") === 0 || strpos($response, "5") === 0) {
            throw new Exception("SMTP error: $response");
        }

        return $response;
    }
}

// Contoh penggunaan 
/*
try {
    $email = new Email();
    $email->setSMTPConfig("smtp.example.com", 587, "your_email@example.com", "your_password");
    $email->setFrom("your_email@example.com", "Your Name");
    $email->setTo("recipient@example.com", "Recipient Name");
    $email->setSubject("Test Email with SMTP");
    $email->setHtmlMessage("<h1>Hello</h1><p>This is a test email using SMTP.</p>");
    $email->addAttachment("/path/to/file.pdf");
    $email->send();

    echo "Email sent successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/

/*
<?php

namespace Kancil\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class Email
{
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->isHTML(true);
        $this->mailer->CharSet = 'UTF-8';
    }

    //  * Set SMTP configuration
    public function setSMTPConfig(
        string $host,
        int $port,
        string $username,
        string $password,
        string $encryption = 'tls'
    ): void {
        $this->mailer->Host       = $host;
        $this->mailer->Port       = $port;
        $this->mailer->Username   = $username;
        $this->mailer->Password   = $password;
        $this->mailer->SMTPAuth   = true;
        $this->mailer->SMTPSecure = $encryption; // 'tls' atau 'ssl'
    }

    //  * Set sender
    public function setFrom(string $email, string $name = ''): void
    {
        $this->mailer->setFrom($email, $name);
    }

    //  * Add recipient
    public function addTo(string $email, string $name = ''): void
    {
        $this->mailer->addAddress($email, $name);
    }

    //  * Add CC
    public function addCc(string $email, string $name = ''): void
    {
        $this->mailer->addCC($email, $name);
    }

    //  * Add BCC
    public function addBcc(string $email, string $name = ''): void
    {
        $this->mailer->addBCC($email, $name);
    }

    //  * Add attachment
    public function addAttachment(string $path, string $name = ''): void
    {
        $this->mailer->addAttachment($path, $name);
    }

    //  * Set subject & body
    public function setContent(string $subject, string $htmlBody, string $altBody = ''): void
    {
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $htmlBody;
        $this->mailer->AltBody = $altBody ?: strip_tags($htmlBody);
    }

    //  * Send the email
    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (PHPMailerException $e) {
            throw new \Exception("Email gagal dikirim: " . $e->getMessage());
        }
    }
}
*/