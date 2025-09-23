<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #28243d;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .error-container {
            max-width: 600px;
        }
        h1 {
            font-size: 120px;
            font-weight: bold;
            color: red;
        }
        p {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .btn-home {
            background-color: #0d6efd;
            color: #fff;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background-color: #084298;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>STOP</h1>
        <h3>Access Forbidden</h3>
        <p>You Can't Access This Page as <b style="text-transform: capitalize;">{{ Session::get('role') }}</b> Role</p>
        <a href="{{ url('/admin/') }}" class="btn-home">Go to Home Page</a>
    </div>
</body>
</html>
