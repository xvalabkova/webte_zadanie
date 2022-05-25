

    <?php
    ob_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    header('Content-Type: application/json; charset=utf-8');
    require_once "../config.php";
    require_once "../classes/Command.php";
    require '../mailSender/vendor/autoload.php';

    $data = $myPdo->run("SELECT * FROM logs")->fetchAll();

    //Source of code: https://www.w3docs.com/snippets/php/automatic-download-file.html

    $filename = '../files/logs.csv';
    $f = fopen($filename, 'w');
    if ($f === false) {
        die('Error opening the file ' . $filename);
    }

    fputcsv($f, ['id', 'command', 'exit_code', 'error_message', 'timestamp'], ';');
    foreach ($data as $row) {
        fputcsv($f, $row, ';');
    }
    fclose($f);
    if (isset($_GET['mode']) && $_GET['mode'] == "down") {
        if (file_exists($filename)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filename));
            flush(); // Flush system output buffer
            //sendMail($email);
            readfile($filename);
            //header("location: ../index.php");
            die();
        } else {
            http_response_code(404);
            die();
        }
    }
    if (isset($_GET['mode']) && $_GET['mode'] == "mail") {
        sendMail($email);
        //header("location: ../index.php");
    }
    //echo json_encode($data);

    function sendMail($email)
    {

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'frantisekbazos@gmail.com';                     //SMTP username
            $mail->Password   = 'qequrhwnapkfelbl';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('frantisekbazos@gmail.com', 'Hancin');
            $mail->addAddress($email, 'Joe User');     //Add a recipient
            //$mail->addAddress('ellen@example.com');               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            $mail->addAttachment('../files/logs.csv');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'web logs';
            $mail->Body    = 'File containing database logs <b>(logs.csv)</b> is in attachment.';
            $mail->AltBody = 'File containing database logs (logs.csv) is in attachment.';

            $mail->send();
            echo 'Message has been sent';
            header('location: ../index.php');
            //header('location: ../index.php?sended="1"');
            //header('Refresh: 3; URL=../index.php?sended=1');
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
   
   