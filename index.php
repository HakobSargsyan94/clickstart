<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <style>
        /* Добавьте стили для удобства */
    </style>
</head>
<body>
<h1>Add User</h1>
<form id="userForm">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required><br><br>
    <button type="submit">Add User</button>
</form>

<p id="responseMessage"></p>

<script>
    document.getElementById('userForm').onsubmit = async function (e) {
        e.preventDefault();
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;

        try {
            const response = await fetch('/api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ name, email })
            });
            const result = await response.json();
            document.getElementById('responseMessage').innerText = result.status || result.error;
        } catch (error) {
            document.getElementById('responseMessage').innerText = 'Error: ' + error.message;
        }
    };
</script>
</body>
</html>
