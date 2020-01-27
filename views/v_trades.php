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
            <input class="form-control mr-sm-2 filter-name" type="search" placeholder="Search" aria-label="Search" id="search" oninput="getTrades()">  
            <button id="go-to-transfers" class="btn btn-primary my-2 my-sm-0" style="margin-right:10px;" onclick="hideAll(), showTransfers()">{ Transfer_Requests }</button>       
            <button id="go-to-trades" class="btn btn-primary my-2 my-sm-0 hide" style="margin-right:10px;" onclick="hideAll(), showTrades()">{ Trades }</button>       
        </div>
        <div class="columns">
            <div class="left-column" >
                <div id="trades-root"></div>
                <div id="transfers-root" class="hide"></div>
            </div>

            <div class="right-column">
                <div id="post_trade" style="width:100%">
                    <form>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputCourse"><h4>Change</h4></label>
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
                                <label for="inputFromCourse"><h4>Transfer from</h4></label>
                                <select id="inputFromCourse" class="form-control" onchange="getTransferableOptions()">
                                    <option selected>Choose a course</option>
                                </select><br>       
                                <label for="inputToCourse"><h4>To</h4></label>
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
    <script>
        function saveCourseOffer(){
            var request = new XMLHttpRequest()

            request.open('POST', 'trades/insertTradeOffer/' + this.choice, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }

            request.send()
        }
        function openConfirmationOfferCourse(e) {
            this.choice = e.target.id
            var request = new XMLHttpRequest()

            request.open('POST', 'trades/determineTradeOffer/' + this.choice, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                
                $('#confirmModal').modal()
                document.getElementById("confirmModalMsg").innerHTML = data.msg
            }

            request.send()         
        }
        function postCourseTrade() {
            var e = document.getElementById("inputCourse")
            var courseForTrade = e.options[e.selectedIndex].value
            
            var data = courseForTrade

            var courseOptions = document.getElementsByClassName('form-check-input')
            for(courseId in courseOptions) {
                if(courseOptions[courseId].checked) {
                    data = data + '.' + courseOptions[courseId].value
                }
            }
            console.log(data)
            var request = new XMLHttpRequest()
            request.open('POST', 'trades/insert/' + data, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }

            request.send()
        }
        function getTradableOptions() {
            var e = document.getElementById("inputCourse")
            
            var courseId = e.options[e.selectedIndex].value
            var request = new XMLHttpRequest()

            request.open('GET', 'trades/getTradableCourses/' + courseId, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                
                const c = document.getElementById('inputChk')
                c.innerHTML = ""

                if (request.status >= 200 && request.status < 400) {
                        data.forEach(item => {
                            const r = document.createElement("div")
                            r.setAttribute("class", "row")

                            c.appendChild(r)

                            const l = document.createElement("label")
                            l.setAttribute("for", item.course_id)
                            l.innerHTML=item.name

                            r.appendChild(l)

                            const i = document.createElement("input")
                            i.setAttribute("type", "checkbox")
                            i.setAttribute("class", "form-check-input")
                            i.setAttribute("value", item.course_id)

                            r.appendChild(i)                         
                        })
                    } else {
                        console.log('error')
                    }
            }
            request.send()
        }

        function getTransferableOptions() {
            var e = document.getElementById("inputFromCourse")
            var s = document.getElementById("inputToCourse")
            var courseId = e.options[e.selectedIndex].value
            var request = new XMLHttpRequest()

            request.open('GET', 'trades/getTradableCourses/' + courseId, true)

            request.onload = function() {
                var data = JSON.parse(this.response)
                $("#inputToCourse").empty();
                if (request.status >= 200 && request.status < 400) {
                        data.forEach(item => {
                            const o = document.createElement("option")
                            o.setAttribute("value", item.course_id)
                            o.innerHTML = item.name

                            s.appendChild(o)                           
                        })
                    } else {
                        console.log('error')
                    }
            }
            request.send()
        }
        
        function getTrades() {
            var request = new XMLHttpRequest() 

            request.open('GET', 'trades/get/' + search.value, true)

            request.onload = function() {
                
                var data = JSON.parse(this.response)
                const root = document.getElementById('trades-root')
                root.innerHTML = "";
                if(request.status >= 200 && request.status < 400) {
                    data.forEach(item => {
                        const d = document.createElement("div")
                        d.setAttribute("class", "trades-card")
                        d.setAttribute("trade_id", item.trade_id)
                        
                        root.appendChild(d)

                        const p = document.createElement("p")
                        stud_name = item.username.split('.')
                        p.innerHTML =   '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' + ' ofera ' + 
                                        '<b>' + item.name + '</b>' + ' in schimbul unuia dintre urmatoarele cursuri: <br>'

                        d.appendChild(p)
                        
                        if (item.option_1) {
                            const p1 = document.createElement("p")
                            p1.innerHTML = '- ' + item.option_1
                            d.appendChild(p1)
                        }
                        if (item.option_2) {
                            const p1 = document.createElement("p")
                            p1.innerHTML = '- ' + item.option_2
                            d.appendChild(p1)
                        }
                        if (item.option_3) {
                            const p1 = document.createElement("p")
                            p1.innerHTML = '- ' + item.option_3
                            d.appendChild(p1)
                        }
                        const btn = document.createElement('a')
                        btn.setAttribute('class', 'btn btn-notif btn-success')
                        btn.setAttribute('id', item.trade_id)
                        btn.setAttribute('onclick', 'openConfirmationOfferCourse(event)')
                        btn.textContent = '{ Alege }'
                        
                        d.appendChild(btn)
                    })
                } else {
                    console.log('error')
                }
            }
            request.send()
        }
        function getAssignedCourses() {
            var request = new XMLHttpRequest()

            request.open('GET', 'assignations/get/trade', true)

            request.onload = function() {
                    var data = JSON.parse(this.response)

                    const s1 = document.getElementById('inputCourse')
                    const s2 = document.getElementById('inputFromCourse')

                    if (request.status >= 200 && request.status < 400) {
                        data.forEach(item => {
                            const o1 = document.createElement("option")
                            o1.setAttribute("value", item.course_id)
                            o1.innerHTML = item.name

                            s1.appendChild(o1)

                            const o2 = document.createElement("option")
                            o2.setAttribute("value", item.course_id)
                            o2.innerHTML = item.name

                            s2.appendChild(o2)

                        })
                    } else {
                        console.log('error')
                    }
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
                    
                    stud_name = data.split('.')
                    username.innerHTML =   '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' 
                } else {
                    console.log('error')
                }
            }
            request.send()
        }
        function hideAll() {
            document.getElementById("post_trade").className = "hide"
            document.getElementById("trades-root").className = "hide"
            document.getElementById("transfer").className = "hide"
            document.getElementById("transfers-root").className = "hide"
            document.getElementById("go-to-transfers").classList.add("hide")
            document.getElementById("go-to-trades").classList.add("hide")
        }
        function showTrades() {
            document.getElementById("post_trade").className = "show"
            document.getElementById("trades-root").className = "show"
            document.getElementById("go-to-transfers").classList.remove("hide")
        }
        function showTransfers() {
            document.getElementById("transfer").className = "show"
            document.getElementById("transfers-root").className = "show"
            document.getElementById("go-to-trades").classList.remove("hide")
        }
        function getTransferRequests() {
            var request = new XMLHttpRequest() 

            request.open('GET', 'trades/getTransferRequests', true)

            request.onload = function() {
                
                var data = JSON.parse(this.response)
                const root = document.getElementById('transfers-root')
                root.innerHTML = "";
                console.log(data)
                if(request.status >= 200 && request.status < 400) {
                    data.forEach(item => {
                        const d = document.createElement("div")
                        d.setAttribute("class", "trades-card")
                        
                        root.appendChild(d)

                        const p = document.createElement("p")
                        p.innerHTML = 'You have requested to be transferred to <b>' + item.name + '</b>.'

                        d.appendChild(p)
                        
                        const s = document.createElement("p")
                        s.innerHTML = 'Status: <b>' + item.status + '</b>'

                        d.appendChild(s)
                        
                        if (item.status == 'Pending') {
                            const btn = document.createElement('a')
                            btn.setAttribute('class', 'btn btn-notif btn-danger')
                            btn.setAttribute('id', item.transfer_id)
                            btn.setAttribute('onclick', 'openConfirmationCancel(event)')
                            btn.textContent = '{ Cancel }'
                            
                            d.appendChild(btn)
                        }
                        
                    })
                } else {
                    console.log('error')
                }
            }
            request.send()
        }
        function openConfirmationCancel(e) {
            this.choice = e.target.id
            var request = new XMLHttpRequest()

            request.open('POST', 'trades/cancelTransferRequest/' + this.choice, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            }

            request.send()
        }
        function insertTransferRequest() {
            var e = document.getElementById("inputFromCourse")
            var transferFromCourse = e.options[e.selectedIndex].value

            var e = document.getElementById("inputToCourse")            
            var transferToCourse = e.options[e.selectedIndex].value

            var data = transferFromCourse + '.' + transferToCourse

            var request = new XMLHttpRequest()
            console.log(data)
            request.open('POST', 'trades/insertTransferRequest/' + data, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }

            request.send()
        }
    </script>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>