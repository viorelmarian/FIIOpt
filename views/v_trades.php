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

<body onload="getTrades(), getAssignedCourses(), getTradableOptions(), displayUsername(), getTransferRequests()">
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
                        <a class="nav-link" href="/courses/display">{ Choose_Opt }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/choices/display">{ Assigned_Opt }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/trades/display">{ Trade_Opt }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/notifications/display">{ Notifications }</a>
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
        <div class="filter-bar mt-4">
            <input class="form-control mr-sm-2 filter-name" type="search" placeholder="Search" aria-label="Search" id="search" oninput="getTrades()">
            <button id="go-to-transfers" class="btn btn-primary my-2 my-sm-0" style="margin-right:10px;" onclick="hideAll(), showTransfers()">{ Transfer_Requests }</button>
            <button id="go-to-trades" class="btn btn-primary my-2 my-sm-0 hide" style="margin-right:10px;" onclick="hideAll(), showTrades()">{ Trades }</button>
        </div>
        <div class="columns mt-4">
            <div class="left-column">
                <div id="trades-root"></div>
                <div id="transfers-root" class="hide"></div>
            </div>

            <div class="right-column">
                <div id="post_trade" style="width:100%">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputCourse">
                                    <h4>Change</h4>
                                </label>
                                <select id="inputCourse" class="form-control" onchange="getTradableOptions()">
                                    <option selected>Choose a course</option>
                                </select><br>
                                <h4>For</h4>
                            </div>
                        </div>
                        <div class="form-group" style="margin-left: 20px;">
                            <div class="form-check" id="inputChk">
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" style="position:relative" onclick="postCourseTrade()">{ Post_Trade }</button>
                    </form>
                </div>
                <div id="transfer" class="hide" style="width:100%">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputFromCourse">
                                    <h4>Transfer from</h4>
                                </label>
                                <select id="inputFromCourse" class="form-control" onchange="getTransferableOptions()">
                                    <option selected>Choose a course</option>
                                </select><br>
                                <label for="inputToCourse">
                                    <h4>To</h4>
                                </label>
                                <select id="inputToCourse" class="form-control">
                                    <option selected>Choose a course</option>
                                </select><br>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success" style="position:relative" onclick="insertTransferRequest()">{ Request_Transfer }</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Choice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="confirmModalMsg">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveCourseOffer()">Confirm</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalStatus"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="infoModalMsg">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload()">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/s_trades.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>