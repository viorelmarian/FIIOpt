<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="shortcut icon" href="../assets/pictures/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <title>FIIOpt - Login</title>
</head>
<body style = 'background-image: url("assets/pictures/background_admin.jpg");'>
    <div class="screen">
        <div class = "container">
            <div class="row align-items-center">
                <img src="../assets/pictures/banner.png" alt="" class="mx-auto" width="60%" height="20%">
                <div class = "col-lg-4 col-md-6 col-sm-8 col-xs-4 mx-auto">
                    <form action="/admin/login" method="post" class="login-form">
                        <div class="form-group">
                            <label for="login_usr">Username</label>
                            <input type="username" class="form-control" id="login_usr" placeholder="john.doe" name="login_usr" value=<?php echo isset($_SESSION["login_usr"]) ? $_SESSION["login_usr"] : ""; ?>>
                            <p style="color:red"><?php echo isset($_SESSION["error_usr"]) ? $_SESSION["error_usr"] : ""; ?></p>
                        </div>
                        <div class="form-group">
                            <label for="login_pwd">Password</label>
                            <input type="password" class="form-control" id="login_pwd" placeholder="**********" name="login_pwd">
                            <p  style="color:red"><?php echo isset($_SESSION["error_pwd"]) ? $_SESSION["error_pwd"] : ""; ?></p>    
                        </div>
                        <button type="submit" class="btn btn-primary" name="login_sub">Log In</button>
                    </form>    
                </div>                  
            </div>     
        </div>
    </div>   
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>