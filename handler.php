<?php
// Sample handler written in PHP

// Check if form fields have been sent
if(isset($_POST["email"]) && isset($_POST["message"]) && isset($_POST["compiled"])) {
    // Check if transmitted fields are PGP encrypted
    // str_starts_with is only compatible with PHP8
    // TODO: Ensure compatibility with PHP7
    if(str_starts_with($_POST["email"], "-----BEGIN PGP MESSAGE-----") || str_starts_with($_POST["message"], "-----BEGIN PGP MESSAGE-----") || str_starts_with($_POST["compiled"], "-----BEGIN PGP MESSAGE-----")) {
        $email = $_POST["email"];
        $message = $_POST["message"];
        $compiled = $_POST["compiled"];
        // Show transmitted encrypted fields as HTML
        // TODO: Add more complex examples such as PGP-encrypted email or adding to a database.
        ?>
        <b>E-Mail:</b>
        <br>
        <code>
            <?php echo(htmlspecialchars($email)); ?>
        </code>
        <br><br>
        <b>Message:</b>
        <br>
        <code>
            <?php echo(htmlspecialchars($message)); ?> 
        </code>
        <br><br>
        <b>Compiled:</b>
        <br>
        <code>
            <?php echo(htmlspecialchars($compiled)); ?>
        </code>
        <?php
    } else {
        // TODO: Show error message in form
        //http_response_code(302);
        //die(header("Location: form.html"));
        echo("Not PGP");
    }
} else {
    // Set response code to 302 (temporary redirect) and redirect back to form
    // TODO: Still needs to be improved so that error message is also displayed in the form
    //http_response_code(302);
    //die(header("Location: form.html"));
    echo("Not transmitted.");
}