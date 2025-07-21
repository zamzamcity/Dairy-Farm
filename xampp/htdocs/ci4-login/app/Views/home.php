<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Zam Zam City</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: #0d0d0d;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .home-box {
            background: #1a1a1a;
            padding: 40px;
            border-radius: 12px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 0 20px rgba(229, 9, 20, 0.3);
        }

        h2 {
            margin-bottom: 20px;
            font-size: 28px;
        }

        .welcome {
            font-size: 18px;
            margin-bottom: 30px;
            color: #ccc;
        }

        a.logout-btn {
            display: inline-block;
            padding: 12px 24px;
            background: #e50914;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        a.logout-btn:hover {
            background: #b00710;
        }
    </style>
</head>
<body>

<div class="home-box">
    <h2>Welcome to Zam Zam City</h2>
    <div class="welcome">Hello, <strong><?= session()->get('username') ?></strong>! You're logged in.</div>
    <a class="logout-btn" href="<?= base_url('login/logout') ?>">Logout</a>
</div>

</body>
</html>
