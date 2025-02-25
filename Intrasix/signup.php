<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intrasix</title>
    <link rel="icon" href="images/wink.png" type="image/png" sizes="16x16">
    <link rel="stylesheet" href="css/main.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/vender/bootstrap.css">
    <link rel="stylesheet" href="css/vender/bootstrap.min.css">
    <link rel="stylesheet" href="css/vender/main.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function enableSubmitBtn() {
            document.getElementById("mySubmitBtn").disabled = false;
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="login">
            <div class="images d-none d-lg-block">
                <div class="frame">
                    <img src="./images/home-phones.png" alt="picutre frame">
                </div>
                <div class="sliders">
                    <div id="carouselExampleSlidesOnly" class="carousel slide carousel-fade" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="./images/screenshot1.png" class="d-block" alt="screenshot1">
                            </div>
                            <div class="carousel-item">
                                <img src="./images/screenshot2.png" class="d-block" alt="screenshot2">
                            </div>
                            <div class="carousel-item">
                                <img src="./images/screenshot3.png" class="d-block" alt="screenshot3">
                            </div>
                            <div class="carousel-item">
                                <img src="./images/screenshot4.png" class="d-block" alt="screenshot4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content">
                <h1 style="text-align: center;color: #8e44ad; font-family:Verdana, Geneva, Tahoma, sans-serif;">CREATE A
                    ACCOUNT</h1>
                <div class="log-on border_insc">
                <form action="includes/signup.inc.php" method="post">
                        <div>
                            <label for="dob">Full Name:</label>
                            <input type="text" name="name" id="name" placeholder="Full Name">
                        </div>
                        <div>
                            <label for="dob">Email Address:</label>
                            <input type="email" name="email" id="email" placeholder="email address">
                        </div>
                        <div>
                            <label for="dob">Date Of Birth:</label>
                            <input type="date" name="dob" id="dob" placeholder="Date Of Birth" required>
                        </div>
                       
                        <div>
                            <label>Gender:</label>
                            <input type="text" name="gender" id="gender" placeholder="Gender" required>
                        </div>
                        <div>
                        <label>password:</label>
                        
                            <input type="password" name="password" id="password" placeholder="password">
                        </div>
                        <div>
                            <label> Confirm Password:</label>
                            
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm_password">
                            </div>
                        <div>
                            <label for="dob">District:</label>
                            <input type="text" name="town" id="town" placeholder="Town" required>
                        </div>

                       
                        <div class="g-recaptcha" data-sitekey="6LcbHM8qAAAAAEEgTTw3LaoT9o1fp5WeqTOnAB00"  data-callback="enableSubmitBtn"></div>
                        <button  name="submit" id="mySubmitBtn" disabled="disabled" type="submit" class="log_btn" style="background-color: #8e44ad;">
                            CREATE
                        </button>
                    </a>
                    </form>

                    <a href="index.php" style="color: #8e44ad; font-weight: bold;">
                        <p style="text-align: center;font-weight: bold">Already Have an account?
                            <a href="login.php" style="color: #8e44ad; font-weight: bold;">Login</a>
                        </p>

                </div>


            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ"
        crossorigin="anonymous"></script>
    <script data-cfasync="false" src="../../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"
        integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js"
        integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ"
        crossorigin="anonymous"></script>



    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script> -->
</body>

</html>