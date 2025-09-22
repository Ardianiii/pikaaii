<!-- templates/login.php -->
<?php if (!empty($error)): ?>
    <div class="alert"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="index.php?page=login">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>


<p>Don't have an account? <a href="index.php?page=register">Register</a></p>
