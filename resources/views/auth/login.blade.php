<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIDAC</title>
    <style>
        body { font-family: sans-serif; display: grid; place-items: center; min-height: 90vh; background-color: #f4f4f4; }
        form { background: #fff; border: 1px solid #ccc; padding: 25px; border-radius: 8px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
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