openpgp.config.show_version = false;
openpgp.config.show_comment = false;

async function encrypt(message) {
  return (
    await openpgp.encrypt({
      message: openpgp.message.fromText(message),
      publicKeys: (await openpgp.key.readArmored(PUBLIC_KEY)).keys,
    })
  ).data;
}
async function submitForm() {
  if (window.crypto && window.crypto.getRandomValues) {
    document.getElementById("submitButton").innerHTML =
      '<div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div>';
    let message = document.getElementById("message").value;
    if (message.length >= 1) {
      let email = document.getElementById("email").value;
      if (email.length >= 1) {
        const compiled = await encrypt(
          "<p><b>E-mail address:</b></p><p>" +
            email +
            "</p><br><p><b>Message:</b></p><p>" +
            message +
            "</p>"
        );
        email = await encrypt(email);
        message = await encrypt(message);
        document.getElementById("message").value = message;
        document.getElementById("email").value = email;
        document.getElementById("compiled").value = compiled;
        document.getElementById("contactForm").action = FORM_ACTION;
        document.getElementById("contactForm").method = "POST";
        document.getElementById("contactForm").submit();
      } else {
        document.getElementById(
          "error"
        ).innerHTML = ```<p class="alert alert-danger"><b>Error:</b> Please enter your e-mail address.</p>```;
      }
    } else {
      document.getElementById(
        "error"
      ).innerHTML = ```<p class="alert alert-danger"><b>Error:</b> Please enter a message.</p>```;
    }
  } else {
    document.getElementById(
      "error"
    ).innerHTML = ```<p class="alert alert-danger"><b>Error:</b> Your browser does not support a cryptographically secure random number generator. Please use a newer browser.</p>```;
  }
}
