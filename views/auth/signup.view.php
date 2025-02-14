<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | Ecomart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/styles.css">
</head>

<body>
    <div class="signup-page">
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
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="terms_accepted" required>
                        <label class="form-check-label">
                            I have read and agree to the <a href="terms.php">Terms and Conditions</a>
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">Next</button>
                </form>

                <script>
                function handleSignup(event) {
                    event.preventDefault();
                    
                    // Get form data
                    const formData = {
                        email: document.querySelector('input[name="email"]').value,
                        mobile_number: document.querySelector('input[name="mobile_number"]').value,
                        password: document.querySelector('input[name="password"]').value,
                        confirm_password: document.querySelector('input[name="confirm_password"]').value,
                        terms_accepted: document.querySelector('input[name="terms_accepted"]').checked
                    };

                    // Basic validation
                    if (!formData.email || !formData.mobile_number || !formData.password || 
                        !formData.confirm_password || !formData.terms_accepted) {
                        alert('Please fill out all fields');
                        return;
                    }

                    if (formData.password !== formData.confirm_password) {
                        alert('Passwords do not match');
                        return;
                    }

                    // Send data to server
                    fetch('/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirect to setup profile page
                            window.location.href = '/setup-profile';
                        } else {
                            if (data.errors) {
                                // Display validation errors
                                const errorMessages = Object.values(data.errors).join('\n');
                                alert(errorMessages);
                            } else {
                                alert('Registration failed. Please try again.');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
                }
                </script>


                </form>
            </div>
        </div>
    </div>
</body>

</html>
