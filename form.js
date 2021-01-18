async function encrypt(message) {
    return (await openpgp.encrypt({
        message: openpgp.message.fromText(message),
        publicKeys: (await openpgp.key.readArmored(PUBLIC_KEY)).keys
    })).data;
}
async function submitForm() {
    document.getElementById("submitButton").innerHTML = '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
    let message = document.getElementById("message").value;
    if(message.length >= 1) {
        let email = document.getElementById("email").value;
        if(email.length >= 1) {
            email = await encrypt(email);
            message = await encrypt(message);
            document.getElementById("message").value = message;
            document.getElementById("email").value = email;
            document.getElementById("contactForm").action = FORM_ACTION;
            document.getElementById("contactForm").method = "POST";
            document.getElementById("contactForm").submit();
        } else {
            document.getElementById("error").innerHTML = ```<p class="alert alert-danger"><b>Error:</b> Please enter your e-mail address.```;
        }
    } else {
        document.getElementById("error").innerHTML = ```<p class="alert alert-danger"><b>Error:</b> Please enter a message.```;
    }
}