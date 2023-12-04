function validatePassword() {
    var newPassword = document.getElementById("newpass").value;
    var confirmPassword = document.getElementById("confirmpass").value;

    var errorMessageElement = document.getElementById("error-message");

    if (newPassword !== confirmPassword) {
        errorMessageElement.textContent = "Password and Confirm Password do not match!";
        return false;
    } else {
        Post('', {changePass1: newPassword});
    }

    errorMessageElement.textContent = "";

    return true;
}