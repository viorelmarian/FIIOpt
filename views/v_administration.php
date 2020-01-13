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
    <title>{ FII_Opt } - Home</title>
</head>
<body style = 'background-image: url("assets/pictures/background_admin.jpg");' onload="getCourses(), getCycles()">
<div class = "screen_page"></div>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class = "container">
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
            <form class="form-inline my-2 my-lg-0" action="/users/logout" method="post" >
                    <div id="username" class="username" style="color:white; margin-right:10px"></div>
                <button class="btn btn-primary my-2 my-sm-0" type="submit">{ Logout }</button>
            </form>
        </div>
    </div>
        
    </nav>
    <div class = "container aligner">       
        <div class="columns" style="justify-content:center;">
            <div class="left-column" style="width: 100%">
                <div class="form-group col-md-6">
                    <label for="inputCourse"><h4>Edit Course</h4></label>
                    <select id="inputCourse" class="form-control" onchange="fillForm()">
                        <option selected>Choose a course</option>
                    </select><br>
                </div>  
                <form>
                    <div class="form-column">
                        <div class="form-group col-md-6">
                            <label for="name">Course Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Name" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="year">Course Year</label>
                            <input type="text" class="form-control" id="year" placeholder="Year" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="package">Package</label>
                            <input type="text" class="form-control" id="package" placeholder="Package" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="cycle">Study Cycle</label>
                            <select id="cycle" class="form-control">
                                <option selected>Choose a study cycle</option>
                            </select>
                        </div> 
                        <div class="form-group col-md-10">
                            <label for="link">Link</label>
                            <input type="text" class="form-control" id="link" placeholder="Link" required>
                        </div>
                        <div class="form-group col-md-6">
                            <button type="button" class="btn btn-primary" onclick="saveCourse()">Save</button>
                        </div>
                    </div>
                </form>
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
    <script>
        function getCycles() {
            var request = new XMLHttpRequest()

            request.open('GET', 'courses/getStudyCycles', true)

            request.onload = function() {
                    var data = JSON.parse(this.response)
                    const s = document.getElementById('cycle')

                    if (request.status >= 200 && request.status < 400) {
                        data.forEach(item => {
                            const o = document.createElement("option")
                            o.setAttribute("value", item.study_cycle_id)
                            o.innerHTML = item.study_cycle_id + '.   ' + item.name

                            s.appendChild(o)
                        })
                    } else {
                        console.log('error')
                    }
                }
            request.send()
        }

        function saveCourse() {
            var request = new XMLHttpRequest()
            data =  'id='       + document.getElementById("inputCourse").value  + '&'
            data += 'name='     + document.getElementById("name").value         + '&'
            data += 'year='     + document.getElementById("year").value         + '&'
            data += 'package='  + document.getElementById("package").value      + '&'
            data += 'cycle='    + document.getElementById("cycle").value        + '&'
            data += 'link='     + document.getElementById("link").value
            console.log(data);
            request.open('GET', 'courses/saveCourse/' + data, true)

            request.onload = function() {
                var data = JSON.parse(this.response)

                    // if (request.status >= 200 && request.status < 400) {
                    //     data.forEach(item => {
                    //         const o = document.createElement("option")
                    //         o.setAttribute("value", item.course_id)
                    //         o.innerHTML = item.course_id + '.   ' + item.name

                    //         s.appendChild(o)
                    //     })
                    // } else {
                    //     console.log('error')
                    // }
                }
            request.send()
        }

        function getCourses() {
            var request = new XMLHttpRequest()

            request.open('GET', 'courses/getAllCourses', true)

            request.onload = function() {
                    var data = JSON.parse(this.response)
                    const s = document.getElementById('inputCourse')

                    if (request.status >= 200 && request.status < 400) {
                        data.forEach(item => {
                            const o = document.createElement("option")
                            o.setAttribute("value", item.course_id)
                            o.innerHTML = item.course_id + '.   ' + item.name

                            s.appendChild(o)
                        })
                    } else {
                        console.log('error')
                    }
                }
            request.send()
       }

       function fillForm() {
        var e = document.getElementById("inputCourse")

        var courseId = e.options[e.selectedIndex].value
        var request = new XMLHttpRequest()
        console.log(courseId)
        request.open('GET', 'courses/getById/' + courseId, true)

        request.onload = function() {
            var data = JSON.parse(this.response)
            console.log(data)
            if (request.status >= 200 && request.status < 400) {
                    document.getElementById("name").value = data[0].name
                    document.getElementById("year").value = data[0].year
                    document.getElementById("package").value = data[0].package
                    document.getElementById("cycle").value = data[0].study_cycle_id
                    document.getElementById("link").value = data[0].link
                } else {
                    console.log('error')
                }
            }
            request.send()
       }
    </script>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>