<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/home.css">

  <title>Trang Chủ</title>
</head>

<body>
  <div class="container">
    <x-nav-bar />
    <div class="wrapper">
      <div class="form-holder">
        <h2>Welcome, {{ $user->full_name }}</h2>
      </div>
    </div>
  </div>
</body>

</html>