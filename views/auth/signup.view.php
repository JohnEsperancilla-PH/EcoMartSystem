<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Ecomart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/styles.css">
</head>

<body class="login-page">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center mb-3">Sign Up</h3>

            <form id="signupForm" onsubmit="handleSignup(event)">
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mobile Number (PH)</label>
                    <input type="text" name="mobile_number" class="form-control" pattern="09[0-9]{9}" placeholder="09XXXXXXXXX" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Re-enter password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" placeholder="Enter first name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Enter last name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-control" name="gender" required>
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Birthdate</label>
                    <input type="date" name="birthdate" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="terms_accepted" required>
                    <label class="form-check-label">
                        I agree to the <a href="terms.php" class="text-danger">Terms and Conditions</a>
                    </label>
                </div>
                <button type="submit" class="btn btn-danger w-100">Sign Up</button>
                <p class="text-center mt-3">
                    Already have an account? <a href="/login" class="text-danger">Login</a>
                </p>
            </form>
        </div>
    </div>

    <script>
        function handleSignup(event) {
            event.preventDefault();

            const formData = {
                email: document.querySelector('input[name="email"]').value,
                mobile_number: document.querySelector('input[name="mobile_number"]').value,
                password: document.querySelector('input[name="password"]').value,
                confirm_password: document.querySelector('input[name="confirm_password"]').value,
                first_name: document.querySelector('input[name="first_name"]').value,
                last_name: document.querySelector('input[name="last_name"]').value,
                gender: document.querySelector('select[name="gender"]').value,
                birthdate: document.querySelector('input[name="birthdate"]').value,
                terms_accepted: document.querySelector('input[name="terms_accepted"]').checked
            };

            if (formData.password !== formData.confirm_password) {
                showError('Passwords do not match');
                return;
            }

            fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect || '/login';
                    } else {
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).join('\n');
                            showError(errorMessages);
                        } else {
                            showError(data.error || 'Registration failed. Please try again.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError(error.message || 'An error occurred. Please try again.');
                });
        }

        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-3';
            errorDiv.textContent = message;

            const existingError = document.querySelector('.alert-danger');
            if (existingError) {
                existingError.remove();
            }

            document.querySelector('.card').prepend(errorDiv);
        }
    </script>
</body>

</html>