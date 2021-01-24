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

<body class="full-height-body" style='background-image: url("assets/pictures/background_admin.jpg");' onload="getCourses(), getCycles(), getProfessors(), getTransferRequests(), getTradesRequests(), loadPage()">
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
                        <a class="nav-link" onclick="displayCourses()">{ Courses }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="displayProfessors()">{ Professors }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="displayTrades()">{ Trade_Requests }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="displayTransfers()">{ Transfer_Requests }</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" onclick="displayStatistics()">{ Statistics }</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0" action="/admin/logout/adm" method="post">
                    <div id="username" class="username" style="color:white; margin-right:10px"></div>
                    <button class="btn btn-primary my-2 my-sm-0" type="submit">{ Logout }</button>
                </form>
            </div>
        </div>

    </nav>
    <div class="container aligner full-height-content">
        <div class="columns mt-4 mb-4 full-height-content" style="justify-content:center;">
            <div class="left-column" style="width: 100%">
                <div id="courses" class="show">
                    <div class="text-white w-100 display-4 d-flex mb-3 justify-content-center position-relative" style="z-index: 1000">{ Courses }</div>
                    <div class="form-group col-md-6">
                        <select id="inputCourse" class="form-control" onchange="fillFormCourses()" style="padding-left: 30px;">
                            <option selected>Choose a course</option>
                        </select><br>
                    </div>
                    <div class="card-body form-box">
                        <form>
                            <div class="form-column" style="padding-top: 15px;">
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Course Name
                                        </div>
                                        <div class="card-body">
                                            <input type="text" class="form-control" id="course_name" placeholder="Name" required>
                                        </div>
                                    </div>
                                    <!-- <label for="course_name">Course Name</label>
                                    <input type="text" class="form-control" id="course_name" placeholder="Name" required> -->
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Course Year
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_year" id="course_year1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_year" id="course_year2" value="2">
                                                <label class="form-check-label" for="inlineRadio2">2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_year" id="course_year3" value="3">
                                                <label class="form-check-label" for="inlineRadio3">3</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <label for="course_year">Course Year</label>
                                    <input type="text" class="form-control" id="course_year" placeholder="Year" required> -->
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Package
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_package" id="course_package1" value="1">
                                                <label class="form-check-label" for="inlineRadio1">1</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_package" id="course_package2" value="2">
                                                <label class="form-check-label" for="inlineRadio2">2</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_package" id="course_package3" value="3">
                                                <label class="form-check-label" for="inlineRadio3">3</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_package" id="course_package4" value="4">
                                                <label class="form-check-label" for="inlineRadio3">4</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="course_package" id="course_package5" value="5">
                                                <label class="form-check-label" for="inlineRadio3">5</label>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <label for="course_package">Package</label>
                                    <input type="text" class="form-control" id="course_package" placeholder="Package" required> -->
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Study Cycle
                                        </div>
                                        <div class="card-body">
                                            <select id="course_cycle" class="form-control">
                                                <option selected>Choose a study cycle</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <label for="course_cycle">Study Cycle</label>
                                    <select id="course_cycle" class="form-control">
                                        <option selected>Choose a study cycle</option>
                                    </select> -->
                                </div>
                                <div class="form-group col-md-10">
                                    <div class="card">
                                        <div class="card-header">
                                            Link
                                        </div>
                                        <div class="card-body">
                                            <input type="text" class="form-control" id="course_link" placeholder="Link" required>
                                        </div>
                                    </div>
                                    <!-- <label for="course_link">Link</label>
                                    <input type="text" class="form-control" id="course_link" placeholder="Link" required> -->
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Number Of Students
                                        </div>
                                        <div class="card-body">
                                            <input type="text" class="form-control" id="course_no_studs" placeholder="Number" required>
                                        </div>
                                    </div>
                                    <!-- <label for="course_no_studs">Number Of Students</label>
                                    <input type="text" class="form-control" id="course_no_studs" placeholder="Number" required> -->
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Professors
                                        </div>
                                        <div class="card-body">
                                            <label for="course_professor1">Professor 1</label>
                                            <select id="course_professor1" class="form-control">
                                                <option selected>Choose a professor</option>
                                            </select>
                                            <br>
                                            <label for="course_professor2">Professor 2</label>
                                            <select id="course_professor2" class="form-control">
                                                <option selected>Choose a professor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="form-group col-md-6">
                                    <label for="course_professor1">Professor 1</label>
                                    <select id="course_professor1" class="form-control">
                                        <option selected>Choose a professor</option>
                                    </select>
                                </div> -->
                                <!-- <div class="form-group col-md-6">
                                    <label for="course_professor2">Professor 2</label>
                                    <select id="course_professor2" class="form-control">
                                        <option selected>Choose a professor</option>
                                    </select>
                                </div> -->
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">Dependencies</div>
                                        <div class="card-body" id="dependencies">
                                        </div>
                                        <div class="card-footer d-flex justify-content-between">
                                            <button type="button" class="btn btn-primary" onclick="addDependency()">Add dependency</button>
                                            <button type="button" class="btn btn-danger" onclick="deleteDependencies()">Delete Dependencies</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            Actions
                                        </div>
                                        <div class="card-body d-flex justify-content-between">
                                            <button type="button" class="btn btn-primary" onclick="saveCourse()">Save Course</button>
                                            <button type="button" class="btn btn-danger" onclick="deleteCourse()">Delete Course</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="professors" class="hide">
                    <div class="text-white w-100 display-4 d-flex mb-3 justify-content-center position-relative" style="z-index: 1000">{ Professors }</div>
                    <div class="form-group col-md-6">
                        <select id="inputProfessor" class="form-control" onchange="fillFormProfessors()" style="padding-left: 30px;">
                            <option selected>Choose a professor</option>
                        </select><br>
                    </div>
                    <div class="card-body form-box">
                        <form>
                            <div class="form-column" style="padding-top: 15px;">
                                <div class="form-group col-md-6">
                                    <label for="title">Professor Title</label>
                                    <input type="text" class="form-control" id="title" placeholder="Title" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="f_name">First Name</label>
                                    <input type="text" class="form-control" id="f_name" placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="l_name">Last Name</label>
                                    <input type="text" class="form-control" id="l_name" placeholder="Last Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="button" class="btn btn-primary" onclick="saveProfessor()">Save</button>
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="button" class="btn btn-danger" onclick="deleteProfessor()">Delete Professor</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="trades" class="hide">
                    <div class="text-white w-100 display-4 d-flex mb-3 justify-content-center position-relative" style="z-index: 1000">{ Trades }</div>
                    <div id="trades-root">
                    </div>
                </div>
                <div id="transfers" class="hide">
                    <div class="text-white w-100 display-4 d-flex mb-3 justify-content-center position-relative" style="z-index: 1000">{ Transfers }</div>
                    <div id="transfers-root">
                    </div>
                </div>
                <div id="statistics" class="hide">
                    <div class="card-body form-box">
                        <h1>Courses</h1>
                        <form>
                            <div class="form-column" style="padding-top: 15px;">

                                <div class="form-group col-md-6">
                                    <select id="inputCourseDownload" class="form-control" style="padding-left: 30px;">
                                        <option selected>Choose a course</option>
                                    </select><br>
                                    <button type="button" class="btn btn-primary" onclick="pdfStudentsOfCourse()">Download</button>
                                </div>
                                <div class="form-group col-md-3">
                                    <select id="inputYearDownload" class="form-control" style="padding-left: 30px;">
                                        <option selected>Choose a year</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select><br>
                                    <button type="button" class="btn btn-primary" onclick="pdfStudentsAssignations()">Download</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- <div class="card-body form-box">
                        <h1>Transfers</h1>
                        <form>
                            <div class="form-column" style="padding-top: 15px;">
                                <div class="form-group col-md-6">
                                    <label for="from_course_name">From Course</label>
                                    <input type="text" class="form-control" id="from_course_name" placeholder="Name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="to_course_name">To Course</label>
                                    <input type="text" class="form-control" id="to_course_name" placeholder="Name" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <label for="transf_year">Study Year</label>
                                    <input type="text" class="form-control" id="transf_year" placeholder="Year" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="button" class="btn btn-primary" onclick="pdfStudentsOfCourse()">Download</button>
                                </div>
                            </div>
                        </form>
                    </div> -->
                    <div class="card-body form-box">
                        <h1>Actions</h1>
                        <button type="button" class="btn btn-primary" onclick="assignCourses()">Assign Courses</button>
                    </div>
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
                <div id="loading-image" class="mt-5" style="margin: auto;">
                    <div class="loader"></div>
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
    <script src="../assets/js/s_administration.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>