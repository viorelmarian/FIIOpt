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
<body onload="getCourses(), displayUsername()">
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
            <form class="form-inline my-2 my-lg-0" action="/users/logout/usr" method="post" >
                    <div id="username" class="username" style="color:white; margin-right:10px"></div>
                <button class="btn btn-primary my-2 my-sm-0" type="submit">{ Logout }</button>
            </form>
        </div>
    </div>
        
    </nav>
    <div class = "container aligner">
        <div class="filter-bar">
            <input class="form-control mr-sm-2 filter-name" type="search" placeholder="Search" aria-label="Search" id="search" oninput="getCourses()">               
        </div>
        <div class="package" id="package1">
            <h4>Pachet 1 - Alegeti optiunile in ordinea dorita!</h4>
        </div>
        <span class = "row" id="root1">  
        </span>
        <div class="package" id="package2">
            <h4>Pachet 2 - Alegeti optiunile in ordinea dorita</h4>
        </div>
        <span class = "row" id="root2">  
        </span>
        <div class="package" id="package3">
            <h4>Pachet 3 - Alegeti optiunile in ordinea dorita</h4>
        </div>
        <span class = "row" id="root3">  
        </span>
        <div class="package" id="package4">
            <h4>Pachet 4 - Alegeti optiunile in ordinea dorita</h4>
        </div>
        <span class = "row" id="root4">  
        </span>
        <div class="package" id="package5">
            <h4>Pachet 5 - Alegeti optiunile in ordinea dorita</h4>
        </div>
        <span class = "row" id="root5">  
        </span>
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
                <div class="modal-body">
                    <p>After confirmation this action can't be undone!</p>
                    <p>You can see your choices in <b>{ Assigned_Opt }</b> tab.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveCourseChoice()">Confirm</button>
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
    <script>
        function getCourses() {
            var request = new XMLHttpRequest()

            request.open('GET', 'courses/get/' + search.value, true);

            request.onload = function() {
                var data = JSON.parse(this.response)

                document.getElementById('root1').innerHTML = ""
                document.getElementById('root2').innerHTML = ""
                document.getElementById('root3').innerHTML = ""
                document.getElementById('root4').innerHTML = ""
                document.getElementById('root5').innerHTML = ""

                document.getElementById('package1').classList.add("hide")
                document.getElementById('package2').classList.add("hide")
                document.getElementById('package3').classList.add("hide")
                document.getElementById('package4').classList.add("hide")
                document.getElementById('package5').classList.add("hide")

                if (request.status >= 200 && request.status < 400) {
                    data.forEach(item => {
                        console.log(item.package)
                        switch (item.package) {
                            case '1':
                                row = document.getElementById('root1')
                                document.getElementById('package1').classList.remove("hide")
                                break;
                            case '2':
                                row = document.getElementById('root2')         
                                document.getElementById('package2').classList.remove("hide")                       
                                break;
                            case '3':
                                row = document.getElementById('root3')        
                                document.getElementById('package3').classList.remove("hide")                        
                                break;
                            case '4':
                                row = document.getElementById('root4')                   
                                document.getElementById('package4').classList.remove("hide")             
                                break;
                            case '5':
                                row = document.getElementById('root5')                 
                                document.getElementById('package5').classList.remove("hide")               
                                break;                        
                            default:
                                break;
                        }
                        const col = document.createElement('div')
                        col.setAttribute('class', 'col-sm card-align')

                        row.appendChild(col)

                        const card = document.createElement('div')
                        card.setAttribute('class', 'card center')

                        col.appendChild(card)

                        const card_body = document.createElement('div')
                        card_body.setAttribute('class', 'card-body')

                        card.appendChild(card_body)

                        const content = document.createElement('div')
                        content.setAttribute('class', 'container')

                        card_body.appendChild(content)

                        const h5 = document.createElement('h5')
                        h5.setAttribute('class', 'card-title')
                        h5.textContent = item.name                    

                        content.appendChild(h5)

                        const p1 = document.createElement('p')
                        p1.setAttribute('class', 'card-text')                    
                        p1.innerHTML ="<b>Professors:<br></b>" + item.professor_1

                        content.appendChild(p1)

                        const p2 = document.createElement('p')
                        p2.setAttribute('class', 'card-text')
                        if (item.professor_2) {
                            p2.innerHTML = item.professor_2                            
                        } else {
                            p2.innerHTML = "<br>"
                        }              

                        content.appendChild(p2)

                        const p3 = document.createElement('p')
                        p3.setAttribute('class', 'card-text-small')                    
                        p3.innerHTML = "<b>Year:</b> " + item.year

                        content.appendChild(p3)

                        const p4 = document.createElement('p')
                        p4.setAttribute('class', 'card-text-small')                    
                        p4.innerHTML = "<b>Package:</b> " + item.package

                        content.appendChild(p4)

                        const container = document.createElement('div')
                        container.setAttribute('class', 'container')

                        card_body.appendChild(container)

                        const btn1 = document.createElement('a')
                        btn1.setAttribute('href', item.link)
                        btn1.setAttribute('class', 'btn btn-info btn-card')
                        btn1.textContent = '{ Fisa Disciplinei }'

                        container.appendChild(btn1)

                        const btn2 = document.createElement('a')
                        btn2.setAttribute('class', 'btn btn-card btn-success')
                        btn2.setAttribute('id' , item.course_id)
                        btn2.setAttribute('onclick', 'openConfirmationSetChoice(event)')
                        btn2.textContent = '{ Alege }'
                        
                        container.appendChild(btn2)
                    })
                } else {
                    console.log('error')
                }
            }
            request.send()
        }
        function openConfirmationSetChoice(e) {   
            this.choice = e.target.id
            $("#confirmModal").modal()            
        }
        function saveCourseChoice() {
            var request = new XMLHttpRequest()
            
            request.open('POST', 'choices/insert/' + this.choice, true)
            request.onload = function() {
                var data = JSON.parse(this.response) 
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }
            request.send()           
        }
        const capitalize = (s) => {
            if (typeof s !== 'string') 
                return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        }
        function displayUsername() {
            var request = new XMLHttpRequest()

            request.open('GET', 'users/getLoggedUser', true);

            request.onload = function() {
                if (request.status >= 200 && request.status < 400) {
                    var data = JSON.parse(this.response)
                    username = document.getElementById('username')
                    console.log(data)
                    stud_name = data.split('.')
                    username.innerHTML =   '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' 
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