//assets/scripts/register.js
// Dans register.js
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.password-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const fieldId = button.getAttribute('data-target');
            const input = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            
            if (input && icon) {
                input.type = input.type === "password" ? "text" : "password";
                icon.classList.toggle('fa-eye-slash');
                icon.classList.toggle('fa-eye');
            }
        });
    });
});