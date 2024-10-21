<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/register.css">
  <title>Login</title>
</head>

<body>
  <div class="wrapper">
    <div class="form-holder">
      <h2>SIGN IN</h2>

      @if ($errors->any())
      <div>
      <strong class="error">{{ $errors->first() }}</strong>
      </div>
    @endif

      <form class="form" action="{{ route('login') }}" method="POST">
        @csrf
        <div>
          <div class="form-group">
            <input placeholder="Username" id="username" name="username" required>
          </div>
          <div class="form-group">
            <input placeholder="Password" type="password" id="password" name="password" required>
          </div>
          <div class="form-group">
            <button type="submit">Login</button>
          </div>
          <div class="form-group text-end">
            You don't have an account? <a href="signup.php">Sign Up</a>
          </div>
      </form>
    </div>
  </div>
</body>

</html>