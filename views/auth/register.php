<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TaskFlow</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="/css/register-index.css" rel="stylesheet">
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container">
        <h1>Create Account</h1>
        
        <form action="/register" method="POST">
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="e.g. JohnDoe" required>
            </div>

            <div>
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="name@example.com" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Create a strong password" required>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        
        <p class="footer-text">
            Already have an account? <a href="/login">Log In</a>
        </p>
    </div>

</body>
</html>