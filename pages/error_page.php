<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }

        .error-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
        }

        h1 {
            color: #ff6347;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <h1>Error</h1>

        <?php if (isset($_GET['error'])): ?>
            <p><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php else: ?>
            <p>An error occurred. Please try again later.</p>
        <?php endif; ?>

        <a href="javascript:history.go(-1)">Return</a>
    </div>
</body>

</html>