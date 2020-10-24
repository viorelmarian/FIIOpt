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
    <div class="container">
        <div class="row mt-4 filter-bar">
            <div class="col-md-4">
                <select class="form-control" id="package-select" onchange="changePackage()">
                </select>
            </div>
            <div class="col-md-8 d-flex justify-content-end">
                <button class="btn btn-primary my-2 my-sm-0" type="submit">{ Save Options }</button>
            </div>
        </div>
        <div class="row mt-2">
            <div id="target" class="col-md-6">
            </div>
            <div id="source" class="col-md-6">
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
    <script>
        function onDragStart(event) {
            event
                .dataTransfer
                .setData('text/plain', event.target.id);

            event
                .currentTarget
        }

        function onDragOver(event) {
            event.preventDefault();
        }

        function onDrop(event) {
            if (event.target !== event.currentTarget) {
                return;
            }
            const id = event
                .dataTransfer
                .getData('text');
            const draggableElement = document.getElementById(id);
            const dropzone = event.target;
            dropzone.appendChild(draggableElement);
            event
                .dataTransfer
                .clearData();
        }

        function onSingleDrop(event) {
            if (event.target !== event.currentTarget) {
                return;
            }
            const id = event
                .dataTransfer
                .getData('text');
            const draggableElement = document.getElementById(id);
            const dropzone = event.target;
            if (!dropzone.hasChildNodes()) {
                dropzone.appendChild(draggableElement);
                event
                    .dataTransfer
                    .clearData();

            }
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
                    username.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>'
                } else {
                    console.log('Error when getting logged user')
                }
            }
            request.send()
        }

        function getCourses() {
            var request = new XMLHttpRequest()

            request.open('GET', 'courses/get', true);

            request.onload = function() {
                var data = JSON.parse(this.response);
                const source = document.getElementById("source");
                const target = document.getElementById("target");
                const select = document.getElementById("package-select");

                if (request.status >= 200 && request.status < 400) {

                    var packages = data.map(item => item.package)
                        .filter((value, index, self) => self.indexOf(value) === index)

                    packages.forEach(package => {

                        const target_div = document.createElement('div');
                        target_div.setAttribute('id', 'target-package-' + package);
                        target_div.setAttribute('class', 'card my-3');

                        target.appendChild(target_div);

                        const target_header = document.createElement('div');
                        target_header.setAttribute('id', 'target-header-package-' + package);
                        target_header.setAttribute('class', 'card-header');
                        target_header.innerHTML = 'Optiuni in ordinea preferintelor';

                        target_div.appendChild(target_header);

                        const target_body = document.createElement('div');
                        target_body.setAttribute('id', 'target-body-package-' + package);
                        target_body.setAttribute('class', 'card-body min-vh-26 d-flex flex-column');

                        target_div.appendChild(target_body);

                        const source_div = document.createElement('div');
                        source_div.setAttribute('id', 'source-package-' + package);
                        source_div.setAttribute('class', 'card my-3');

                        source.appendChild(source_div);

                        const source_header = document.createElement('div');
                        source_header.setAttribute('id', 'source-header-package-' + package);
                        source_header.setAttribute('class', 'card-header');
                        source_header.innerHTML = 'Cursuri disponibile in acest pachet';

                        source_div.appendChild(source_header);

                        const source_body = document.createElement('div');
                        source_body.setAttribute('id', 'source-body-package-' + package);
                        source_body.setAttribute('class', 'card-body min-vh-26 d-flex flex-column');
                        source_body.setAttribute('ondragover', 'onDragOver(event)');
                        source_body.setAttribute('ondrop', 'onDrop(event)');

                        source_div.appendChild(source_body);

                        const option = document.createElement('option');
                        if (package == 1) {
                            option.setAttribute('selected', 'selected');
                        }
                        option.setAttribute('value', package);
                        option.innerHTML = 'Package ' + package;

                        select.appendChild(option);
                    })

                    packages.forEach(package => {
                        var i = 0;
                        data.forEach(item => {
                            if (item.package == package) {
                                i++;
                                const target_parent = document.getElementById('target-body-package-' + package);
                                const source_parent = document.getElementById('source-body-package-' + package);

                                const target_div = document.createElement('div');
                                target_div.setAttribute('class', 'card my-3');
                                target_div.setAttribute('id', 'p' + package + 'o' + i)

                                target_parent.appendChild(target_div);

                                const target_header = document.createElement('div');
                                target_header.setAttribute('id', 'header-option-' + i);
                                target_header.setAttribute('class', 'card-header');
                                target_header.innerHTML = 'Optiunea ' + i;

                                target_div.appendChild(target_header);

                                const target_body = document.createElement('div');
                                target_body.setAttribute('id', 'body-option-' + i);
                                target_body.setAttribute('class', 'card-body d-flex flex-column');
                                target_body.setAttribute('ondragover', 'onDragOver(event);');
                                target_body.setAttribute('ondrop', 'onSingleDrop(event)');

                                target_div.appendChild(target_body);

                                const source_div = document.createElement('div');
                                source_div.setAttribute('class', 'list-group-item');
                                source_div.setAttribute('id', item.course_id);
                                source_div.setAttribute('draggable', 'true');
                                source_div.setAttribute('ondragstart', 'onDragStart(event)');
                                source_div.innerHTML = item.name;

                                source_parent.appendChild(source_div);

                            }
                        })
                    })

                    document.getElementById('source-package-2').hidden = true;
                    document.getElementById('source-package-3').hidden = true;
                    document.getElementById('source-package-4').hidden = true;
                    document.getElementById('source-package-5').hidden = true;

                    document.getElementById('target-package-2').hidden = true;
                    document.getElementById('target-package-3').hidden = true;
                    document.getElementById('target-package-4').hidden = true;
                    document.getElementById('target-package-5').hidden = true;
                } else {
                    console.log('Error when getting courses')
                }
            }
            request.send()
        }

        function changePackage() {
            selectedPackage = document.getElementById('package-select').value;
            //Hide All Sources
            document.getElementById('source-package-1').hidden = true;
            document.getElementById('source-package-2').hidden = true;
            document.getElementById('source-package-3').hidden = true;
            document.getElementById('source-package-4').hidden = true;
            document.getElementById('source-package-5').hidden = true;
            //Hide All Targets
            document.getElementById('target-package-1').hidden = true;
            document.getElementById('target-package-2').hidden = true;
            document.getElementById('target-package-3').hidden = true;
            document.getElementById('target-package-4').hidden = true;
            document.getElementById('target-package-5').hidden = true;
            //Display source and target for slected package only
            document.getElementById('source-package-' + selectedPackage).hidden = false;
            document.getElementById('target-package-' + selectedPackage).hidden = false;
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>