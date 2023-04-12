// Add event listener to the form submit button
document.querySelector('form').addEventListener('submit', function(event) {
    // Prevent form submission if reCAPTCHA is not completed
    if (grecaptcha.getResponse().length === 0) {
        event.preventDefault();
        alert("Please complete the reCAPTCHA");
    }
});
