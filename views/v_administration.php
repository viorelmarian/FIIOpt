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
<body style = 'background-image: url("assets/pictures/background_admin.jpg");' onload="getCourses(), getCycles(), getProfessors()">
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
                    <a class="nav-link" onclick="displayCourses()">{ Courses }</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link" onclick="displayProfessors()">{ Professors }</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link">{ Change_Requests }</a>
                </li>    
                <li class="nav-item">
                    <a class="nav-link">{ Statistics }</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="/admin/logout/adm" method="post" >
                    <div id="username" class="username" style="color:white; margin-right:10px"></div>
                <button class="btn btn-primary my-2 my-sm-0" type="submit">{ Logout }</button>
            </form>
        </div>
    </div>
        
    </nav>
    <div class = "container aligner">       
        <div class="columns" style="justify-content:center;">
            <div class="left-column" style="width: 100%">
                <div id="courses" class="show">
                    <div class="form-group col-md-6">
                        <label for="inputCourse"><h4>Courses</h4></label>
                        <select id="inputCourse" class="form-control" onchange="fillFormCourses()" style="padding-left: 30px;">
                            <option selected>Choose a course</option>
                        </select><br>
                    </div>  
                    <div class="card-body form-box">
                        <form>
                            <div class="form-column" style="padding-top: 15px;">
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
                                    <label for="professor1">Professor 1</label>
                                    <select id="professor1" class="form-control">
                                        <option selected>Choose a professor</option>
                                    </select>
                                </div> 
                                <div class="form-group col-md-6">
                                    <label for="professor2">Professor 2</label>
                                    <select id="professor2" class="form-control">
                                        <option selected>Choose a professor</option>
                                    </select>
                                </div> 
                                <div class="form-group col-md-6">
                                    <button type="button" class="btn btn-primary" onclick="saveCourse()">Save</button>
                                </div>
                                <div class="form-group col-md-6">
                                    <button type="button" class="btn btn-danger" onclick="deleteCourse()">Delete Course</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
                <div id="professors" class="hide">
                    <div class="form-group col-md-6">
                        <label for="inputProfessor"><h4>Professors</h4></label>
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
        function hideAll() {
            courses = document.getElementById("courses")
            courses.className = "hide"
            courses = document.getElementById("professors")
            courses.className = "hide"
        }
        function displayCourses() {
            hideAll()
            courses = document.getElementById("courses")
            courses.className = "show"
        }
        function displayProfessors() {
            hideAll()
            courses = document.getElementById("professors")
            courses.className = "show"
        }
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

        function getProfessors() {
            var request = new XMLHttpRequest()

            request.open('GET', 'courses/getProfessors', true)

            request.onload = function() {
                    var data = JSON.parse(this.response)
                    const s1 = document.getElementById('professor1')
                    const s2 = document.getElementById('professor2')
                    const s3 = document.getElementById('inputProfessor')
                    
                    if (request.status >= 200 && request.status < 400) {
                        data.forEach(item => {
                            const p1 = document.createElement("option")
                            p1.setAttribute("value", item.professor_id)
                            p1.innerHTML = item.professor_id + '.   ' + item.title + ' ' + item.f_name + ' ' + item.l_name

                            s1.appendChild(p1)

                            const p2 = document.createElement("option")
                            p2.setAttribute("value", item.professor_id)
                            p2.innerHTML = item.professor_id + '.   ' + item.title + ' ' + item.f_name + ' ' + item.l_name

                            s2.appendChild(p2)

                            const p3 = document.createElement("option")
                            p3.setAttribute("value", item.professor_id)
                            p3.innerHTML = item.professor_id + '.   ' + item.title + ' ' + item.f_name + ' ' + item.l_name

                            s3.appendChild(p3)
                        })
                    } else {
                        console.log('error')
                    }
                }
            request.send()
        }

        function saveCourse() {
            var request = new XMLHttpRequest()
            data =  'id='           + document.getElementById("inputCourse").value  + '&'
            data += 'name='         + document.getElementById("name").value         + '&'
            data += 'year='         + document.getElementById("year").value         + '&'
            data += 'package='      + document.getElementById("package").value      + '&'
            data += 'cycle='        + document.getElementById("cycle").value        + '&'
            data += 'link='         + document.getElementById("link").value         + '&'
            data += 'professor1='   + document.getElementById("professor1").value   + '&'
            data += 'professor2='   + document.getElementById("professor2").value
            
            request.open('GET', 'courses/saveCourse/' + data, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
                }
            request.send()
        }

        function saveProfessor() {
            var request = new XMLHttpRequest()
            data =  'id='       + document.getElementById("inputProfessor").value   + '&'
            data += 'l_name='   + document.getElementById("l_name").value           + '&'
            data += 'f_name='   + document.getElementById("f_name").value           + '&'
            data += 'title='    + document.getElementById("title").value      
            
            request.open('GET', 'courses/saveProfessor/' + data, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
                }
            request.send()
        }

        function deleteCourse() {
            courseId = document.getElementById("inputCourse").value

            var request = new XMLHttpRequest()

            request.open('GET', 'courses/deleteCourse/' + courseId, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
                }
            request.send()
        }

        function deleteProfessor() {
            professorId = document.getElementById("inputProfessor").value

            var request = new XMLHttpRequest()

            request.open('GET', 'courses/deleteProfessor/' + professorId, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
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

       function fillFormCourses() {
            var e = document.getElementById("inputCourse")

            var courseId = e.options[e.selectedIndex].value
            var request = new XMLHttpRequest()
            
            request.open('GET', 'courses/getById/' + courseId, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                
                if (request.status >= 200 && request.status < 400) {
                    document.getElementById("name").value = data[0].name
                    document.getElementById("year").value = data[0].year
                    document.getElementById("package").value = data[0].package
                    document.getElementById("cycle").value = data[0].study_cycle_id
                    document.getElementById("link").value = data[0].link
                    document.getElementById("professor1").value = data[0].professor_1
                    document.getElementById("professor2").value = data[0].professor_2
                } else {
                    console.log('error')
                }
            }
            request.send()
       }

       function fillFormProfessors() {
            var e = document.getElementById("inputProfessor")

            var professorId = e.options[e.selectedIndex].value
            var request = new XMLHttpRequest()

            request.open('GET', 'courses/getProfessorById/' + professorId, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                
                if (request.status >= 200 && request.status < 400) {
                    document.getElementById("title").value = data[0].title
                    document.getElementById("l_name").value = data[0].l_name
                    document.getElementById("f_name").value = data[0].f_name
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