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
<body onload="getTradeOffers(), displayUsername()">
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
            <input class="form-control mr-sm-2 filter-name" type="search" placeholder="Search" aria-label="Search" id="search" oninput="getTradeOffers()">               
        </div>
        <div class="columns" style="justify-content:center;">
            <div class="left-column" id="trade_offers-root" style="width: 80%">
                
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
        function getTradeOffers() {
            var request = new XMLHttpRequest() 

            request.open('GET', 'trades/getTradeOffers', true)

            request.onload = function() { 

                var data = JSON.parse(this.response)
                
                const root = document.getElementById('trade_offers-root')
                if(request.status >= 200 && request.status < 400) {
                    data.forEach(item => {
                        const d = document.createElement("div")
                        d.setAttribute("class", "trades-card")
                        
                        root.appendChild(d)

                        const p = document.createElement("p")
                        stud_name = item.offer_student_name.split('.')
                        p.innerHTML =   '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' + ' ofera ' + 
                                        '<b>"' + item.offer_course_name + '"</b>' + ' in schimbul cursului ' + 
                                        '<b>"' + item.donor_course_name + '"</b>.' + '<br><br>' + 'Accepti?'

                        d.appendChild(p)
                        
                        const btn_accept = document.createElement('a')
                        btn_accept.setAttribute('class', 'btn btn-notif btn-success')
                        btn_accept.setAttribute('id', item.offer_id)
                        btn_accept.setAttribute('onclick', 'acceptTrade(event)')
                        btn_accept.textContent = '{ Accept }'

                        d.appendChild(btn_accept)

                        const btn_decline = document.createElement('a')
                        btn_decline.setAttribute('class', 'btn btn-notif btn-danger')
                        btn_decline.setAttribute('id', item.offer_id)
                        btn_decline.setAttribute('onclick', 'declineTrade(event)')
                        btn_decline.textContent = '{ Decline }'
                        
                        d.appendChild(btn_decline)
                    })
                } else {
                    console.log('error')
                }
            }
            request.send()
        }

        function acceptTrade(e) {
            this.choice = e.target.id
            console.log(this.choice)
            var request = new XMLHttpRequest()

            request.open('POST', 'trades/acceptTrade/' + this.choice, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                console.log(data)
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            }

            request.send()         
        }
        
        function declineTrade(e) {
            this.choice = e.target.id
            console.log(this.choice)
            var request = new XMLHttpRequest()

            request.open('POST', 'trades/declineTrade/' + this.choice, true)
            request.onload = function() {
                var data = JSON.parse(this.response)
                console.log(data)
                
                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
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