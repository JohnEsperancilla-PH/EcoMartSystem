<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Account | EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="signup-page">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-sm" style="width: 350px;">
            <h3 class="text-center mb-3">Complete Your Profile</h3>

            <?php if (isset($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="setupForm" onsubmit="handleSetup(event)">

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
                    <select class="form-control" name="gender">

                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Birthdate</label>
                    <input type="date" name="birthdate" class="form-control" required>

                </div>
                <button type="submit" class="btn btn-danger w-100">Finish</button>
            </form>

            <script>
            function handleSetup(event) {
                event.preventDefault();
                
                // Get form data
                const formData = {
                    first_name: document.querySelector('input[name="first_name"]').value,
                    last_name: document.querySelector('input[name="last_name"]').value,
                    gender: document.querySelector('select[name="gender"]').value,
                    birthdate: document.querySelector('input[name="birthdate"]').value
                };

                // Basic validation
                if (!formData.first_name || !formData.last_name || 
                    !formData.gender || !formData.birthdate) {
                    alert('Please fill out all fields');
                    return;
                }

                // Get form data only (signup data is handled server-side)
                const completeData = {
                    ...formData
                };


                // Submit data to server
                fetch('/setup-profile', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(completeData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {

                        window.location.href = '/dashboard';
                    } else {
                        alert(data.message || 'Setup failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            }
            </script>

        </div>
    </div>
</body>

</html>
