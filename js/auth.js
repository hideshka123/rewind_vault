document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            
            emailError.textContent = '';
            passwordError.textContent = '';
            
            let hasError = false;
            
            if (!email.value.trim()) {
                emailError.textContent = 'Email обязателен';
                hasError = true;
            } else if (!isValidEmail(email.value)) {
                emailError.textContent = 'Некорректный email';
                hasError = true;
            }
            
            if (!password.value) {
                passwordError.textContent = 'Пароль обязателен';
                hasError = true;
            }
            
            if (hasError) return;
            
            const formData = new FormData(loginForm);
            
            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect || '../index.html';
                } else {
                    if (result.message.includes('email')) {
                        emailError.textContent = result.message;
                    } else {
                        passwordError.textContent = result.message;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                passwordError.textContent = 'Ошибка соединения. Попробуйте позже.';
            }
        });
    }
    
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            const usernameError = document.getElementById('usernameError');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const confirmPasswordError = document.getElementById('confirmPasswordError');
            
            usernameError.textContent = '';
            emailError.textContent = '';
            passwordError.textContent = '';
            confirmPasswordError.textContent = '';
            
            let hasError = false;
            
            if (!username.value.trim()) {
                usernameError.textContent = 'Имя пользователя обязательно';
                hasError = true;
            } else if (username.value.length < 3) {
                usernameError.textContent = 'Минимум 3 символа';
                hasError = true;
            } else if (!/^[a-zA-Z0-9_-]+$/.test(username.value)) {
                usernameError.textContent = 'Только буквы, цифры, _ и -';
                hasError = true;
            }
            
            if (!email.value.trim()) {
                emailError.textContent = 'Email обязателен';
                hasError = true;
            } else if (!isValidEmail(email.value)) {
                emailError.textContent = 'Некорректный email';
                hasError = true;
            }
            
            if (!password.value) {
                passwordError.textContent = 'Пароль обязателен';
                hasError = true;
            } else if (password.value.length < 6) {
                passwordError.textContent = 'Минимум 6 символов';
                hasError = true;
            }
            
            if (password.value !== confirmPassword.value) {
                confirmPasswordError.textContent = 'Пароли не совпадают';
                hasError = true;
            }
            
            if (hasError) return;
            
            const formData = new FormData(registerForm);
            
            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    window.location.href = result.redirect || '../index.html';
                } else {
                    if (result.message.includes('email')) {
                        emailError.textContent = result.message;
                    } else if (result.message.includes('именем') || result.message.includes('username')) {
                        usernameError.textContent = result.message;
                    } else {
                        passwordError.textContent = result.message;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                passwordError.textContent = 'Ошибка соединения. Попробуйте позже.';
            }
        });
        
        const usernameInput = document.getElementById('username');
        if (usernameInput) {
            usernameInput.addEventListener('input', function() {
                const error = document.getElementById('usernameError');
                if (this.value.length > 0 && this.value.length < 3) {
                    error.textContent = 'Минимум 3 символа';
                } else {
                    error.textContent = '';
                }
            });
        }
        
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const error = document.getElementById('passwordError');
                if (this.value.length > 0 && this.value.length < 6) {
                    error.textContent = 'Минимум 6 символов';
                } else {
                    error.textContent = '';
                }
            });
        }
        
        const confirmInput = document.getElementById('confirm_password');
        if (confirmInput) {
            confirmInput.addEventListener('input', function() {
                const error = document.getElementById('confirmPasswordError');
                if (this.value && this.value !== passwordInput.value) {
                    error.textContent = 'Пароли не совпадают';
                } else {
                    error.textContent = '';
                }
            });
        }
    }
});

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}