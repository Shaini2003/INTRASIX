<!doctype html>
<html lang="en">

<head>

  <title>OTP Login Demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</head>

<body>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <div class="container">
    <h1>PHP OTP Login</h1>
    <div class="alert alert-primary" role="alert">
      <?php
      if (isset($_REQUEST['msg']))
        echo $_REQUEST['msg'];
      ?>

    </div>
    <div class="mb-3">
      <form action="log.php" method="post">
        <label for="exampleFormControlInput1" class="form-label">Enter OTP:</label>
        <input type="number" class="form-control" name="user_otp" id="user_otp" placeholder="5 Digits OTP">
    </div>
    <div class="mb-3">
      <button type="submit" class="btn btn-success">Verify OTP</button>
    </div>
    </form>
  </div>
</body>

</html>