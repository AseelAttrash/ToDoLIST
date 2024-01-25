function validateEmailRegister() {
    const emailInput = document.getElementById('email-register');
    const email = emailInput.value;
    const emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;

    if (emailRegex.test(email)) {
        return true;
    } else {
        alert('Please enter a valid email address');
        emailInput.focus();
        return false;
    }
}
