<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIDAC</title>
    <style>
        /* ===== RESET & FONT ===== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #ecececff;
        }

        /* ===== FORM CONTAINER ===== */
        form {
            background: #fff;
            padding: 40px 35px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== TITLE ===== */
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-weight: 600;
        }

        /* ===== INPUT GROUP ===== */
        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 0.9rem;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #4e73df;
            box-shadow: 0 0 5px rgba(78, 115, 223, 0.3);
        }

        div {
            margin-bottom: 18px;
        }

        /* ===== BUTTON ===== */
        button {
            width: 100%;
            padding: 12px;
            background-color: #4e73df;
            color: #fff;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #2e59d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
        }

        /* ===== ERROR MESSAGE ===== */
        .error {
            background: #ffeaea;
            color: #d9534f;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.9rem;
            margin-top: 10px;
            border: 1px solid #f5c2c2;
        }

        .error ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <form method="POST" action="/login">
        @csrf
        <h2>Login SIDAC</h2>

        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="Username" value="{{ old('Username') }}" required autofocus>
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="Password" required>
        </div>

        <button type="submit">Login</button>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</body>
</html>
