<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EcoMart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        <h1>Admin Dashboard</h1>
        <?php if (isset($session)): ?>
            <p>Welcome, <?php echo htmlspecialchars($session->get('email')); ?></p>
        <?php endif; ?>

        <form action="/logout" method="POST" class="mt-3">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>

</html>