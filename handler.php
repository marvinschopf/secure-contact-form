<?php
// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require "vendor/autoload.php";

// Config
define("DEBUG", false);

define("DESTINATION_EMAIL", "");
define("FROM_EMAIL", "");
define("USE_SMTP", false);

define("SMTP_HOST", "");
define("SMTP_USERNAME", "");
define("SMTP_PASSWORD", "");
define("SMTP_PORT", 0);

// Sample handler written in PHP

// Instantiate mailer
$mail = new PHPMailer(DEBUG);

// Check if form fields have been sent
if (
    isset($_POST["email"]) &&
    isset($_POST["message"]) &&
    isset($_POST["compiled"])
) {
    // Check if transmitted fields are PGP encrypted
    // str_starts_with is only compatible with PHP8
    // TODO: Ensure compatibility with PHP7
    if (
        str_starts_with($_POST["email"], "-----BEGIN PGP MESSAGE-----") &&
        str_starts_with($_POST["message"], "-----BEGIN PGP MESSAGE-----") &&
        str_starts_with($_POST["compiled"], "-----BEGIN PGP MESSAGE-----")
    ) {

        $email = $_POST["email"];
        $message = $_POST["message"];
        $compiled = $_POST["compiled"];

        if (USE_SMTP) {
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = SMTP_PORT;
        } else {
            $mail->IsMail();
        }

        $mail->XMailer = " ";

        $mail->IsHTML(false);

        $mail->setFrom(FROM_EMAIL);
        $mail->addAddress(DESTINATION_EMAIL);

        $mail->Subject = "Encrypted Message";
        $mail->Body = $compiled;

        $mail->send();

        // Show transmitted encrypted fields as HTML
        // TODO: Add more complex examples such as PGP-encrypted email or adding to a database.
        ?>
        <b>E-Mail:</b>
        <br>
        <code>
            <?php echo htmlspecialchars($email); ?>
        </code>
        <br><br>
        <b>Message:</b>
        <br>
        <code>
            <?php echo htmlspecialchars($message); ?> 
        </code>
        <br><br>
        <b>Compiled:</b>
        <br>
        <code>
            <?php echo htmlspecialchars($compiled); ?>
        </code>
        <?php
    } else {
        // TODO: Show error message in form
        //http_response_code(302);
        //die(header("Location: form.html"));
        echo "Not PGP";
    }
} else {
    // Set response code to 302 (temporary redirect) and redirect back to form
    // TODO: Still needs to be improved so that error message is also displayed in the form
    //http_response_code(302);
    //die(header("Location: form.html"));
    echo "Not transmitted.";
}
