<?php

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer extends AbstractController{
    public function sendMail(User $user){
        // Instantiation and passing true enables exceptions
        $mail = new PHPMailer(true);
        try { $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = 3; //Set PHPMailer to use SMTP.
            $mail->isSMTP(); //Set SMTP host name
            $mail->Host = "smtp.gmail.com"; //Set this to true if SMTP host requires authentication to send email
            $mail->SMTPAuth = true; //Provide username and password
            $mail->Username = "rfrancois6.ServerSTMP@gmail.com";
            $mail->Password = "noreply@STMP"; //If SMTP requires TLS encryption then set it
            $mail->SMTPSecure = "tls"; //Set TCP port to connect to
            $mail->Port = 587; //Recipients
            $mail->setFrom('rfrancois6.ServerSTMP@gmail.com', 'DuelQuizz');
            $mail->addAddress($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName());// Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Bienvenue sur DuelQuizz !';
            $mail->Body = '<header><h1>Bienvenue sur DuelQuizz !</h1></header><p>Grâce à votre inscription vous avez accès au quizz solo et duel. Amusez-vous bien !</p>';
            $mail->AltBody = 'Bienvenue sur DuelQuizz !Grâce à votre inscription vous avez accès au quizz solo et duel. Amusez-vous bien !';
            $mail->send(); echo 'Message has been sent'; // redirection page d'accueil//
            //header();
        }
        catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: . $mail->ErrorInfo";
        }
    }   
}
