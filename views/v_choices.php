<!DOCTYPE html>
<html lang="en">

<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/home.css">
    <link rel="shortcut icon" href="../assets/pictures/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <title>{ FII_Opt } - Home</title>
</head>

<body onload="getChoices(), getAssignations(), displayUsername(), getOffersNumber()">
    <div class="screen_page"></div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="../assets/pictures/banner.png" alt="" width="100px">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/courses/display">
                            <span>{ Choose_Opt }</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/choices/display">
                            <span>{ Assigned_Opt }</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trades/display">
                            <span>{ Trade_Opt }</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link notification" href="/notifications/display">
                            <span>{ Notifications }</span>
                            <span id="notification_number" class="badge"></span>
                        </a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="/users/logout/usr" method="post">
                    <div id="username" class="username" style="color:white; margin-right:10px"></div>
                    <button class="btn btn-primary my-2 my-sm-0" type="submit">{ Logout }</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container aligner">
        <div class="table-responsive table-container">
            <h1 style="color:white">Chosen Courses</h1>
            <table class="table table-striped table-light table-container table-hover">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Professor</th>
                        <th scope="col">Year</th>
                        <th scope="col">Package</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="root-choices">

                </tbody>
            </table>
        </div>
    </div>
    <div class="container aligner">
        <div class="table-responsive table-container">
            <h1 style="color:white">Assigned Courses</h1>
            <table class="table table-striped table-light table-container table-hover">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Professor</th>
                        <th scope="col">Year</th>
                        <th scope="col">Package</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="root-assignations">

                </tbody>
            </table>
        </div>
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script src="../assets/js/s_choices.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('d17be8547939203b0379', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('trades');

        channel.bind('new_offer', function(data) {
            getOffersNumber()
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>