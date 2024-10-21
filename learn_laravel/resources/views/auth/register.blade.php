<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký</title>
  <link rel="stylesheet" href="/css/register.css">
</head>

<body>
  <div class="wrapper">
    <div class="form-holder">
      <h2>Create New Account</h2>
      <form class="form" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="form-group">
          <input placeholder="Full name" type="text" name="fullname" id="fullname" required>
          @error('fullname')
        <span>{{ $message }}</span>
      @enderror
        </div>
        <div class="form-group">
          <input placeholder="Username" type="text" name="username" id="username" required>
          @error('username')
        <span>{{ $message }}</span>
      @enderror
        </div>
        <div class="form-group">
          <input placeholder="Email" type="email" name="email" id="email" required>
          @error('email')
        <span>{{ $message }}</span>
      @enderror
        </div>
        <div class="form-group">
          <input placeholder="Phone number" type="text" name="phone_number" id="phone_number" required>
          @error('phone_number')
        <span>{{ $message }}</span>
      @enderror
        </div>
        <div class="form-group">
          <input placeholder="Address" type="text" name="user_address" id="user_address" required>
          @error('user_address')
        <span>{{ $message }}</span>
      @enderror
        </div>
        <div class="form-group">
          <input placeholder="Password" type="password" name="password" id="password" required>
          @error('password')
        <span>{{ $message }}</span>
      @enderror
        </div>
        <div class="form-group">
          <input placeholder="Confirm Password" type="password" name="password_confirmation" id="password_confirmation"
            required>
        </div>
        <div class="form-group">
          <button type="submit">Sign Up</button>
        </div>
        <div class="form-group text-end">
          You have an account?<a href="login"> Sign In</a>
        </div>
      </form>
      @if (session('success'))
      <div>{{ session('success') }}</div>
    @endif
    </div>
  </div>
</body>

</html>