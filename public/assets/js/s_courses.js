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

function openConfirmationSetChoice(e) {
    this.choice = e.target.id
    $("#confirmModal").modal()
}

const capitalize = (s) => {
    if (typeof s !== 'string')
        return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
}

function displayUsername() {
    $(function() {
        $.ajax({
            type: "GET",
            url: "users/getLoggedUser",
            success: function(response) {
                var data = JSON.parse(response)
                username = document.getElementById('username')
                stud_name = data.split('.')
                username.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>'
            }
        })
    })
}

function getCourses() {
    $(function() {
        $.ajax({
            type: "GET",
            url: "courses/get",
            success: function(response) {
                var data = JSON.parse(response)

                const source = document.getElementById("source");
                const target = document.getElementById("target");
                const select = document.getElementById("package-select");

                var packages = data.map(item => item.package)
                    .filter((value, index, self) => self.indexOf(value) === index)

                packages.forEach((package, i) => {

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
                    if (i == 0) {
                        option.setAttribute('selected', 'selected');
                    }
                    option.setAttribute('value', package);
                    option.innerHTML = 'Package ' + package;

                    select.appendChild(option);
                })

                packages.forEach((package, index) => {
                    var i = 0;
                    data.forEach(item => {
                        if (item.package == package) {
                            i++;
                            const target_parent = document.getElementById('target-body-package-' + package);
                            const source_parent = document.getElementById('source-body-package-' + package);

                            const target_div = document.createElement('div');
                            target_div.setAttribute('class', 'card my-3');

                            target_parent.appendChild(target_div);

                            const target_header = document.createElement('div');
                            target_header.setAttribute('id', 'header-option-' + i);
                            target_header.setAttribute('class', 'card-header');
                            target_header.innerHTML = 'Optiunea ' + i;

                            target_div.appendChild(target_header);

                            const target_body = document.createElement('div');
                            target_body.setAttribute('id', 'p' + package + 'o' + i)
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
                    if (index == 0) {
                        document.getElementById('source-package-' + package).hidden = false;
                        document.getElementById('target-package-' + package).hidden = false;
                    } else {
                        document.getElementById('source-package-' + package).hidden = true;
                        document.getElementById('target-package-' + package).hidden = true;
                    }
                })
            },
            error: function() {
                getChoices()
            }
        })
    })
}

function getChoices() {
    $(function() {
        $.ajax({
            type: "GET",
            url: "choices/getAllChoices",
            success: function(response) {

                document.getElementById("saveChoicesButton").hidden = true;
                var data = JSON.parse(response)

                const source = document.getElementById("source");
                const target = document.getElementById("target");
                const select = document.getElementById("package-select");

                var packages = data.map(item => item.package)
                    .filter((value, index, self) => self.indexOf(value) === index)

                packages.forEach((package, i) => {

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
                    if (i == 0) {
                        option.setAttribute('selected', 'selected');
                    }
                    option.setAttribute('value', package);
                    option.innerHTML = 'Package ' + package;

                    select.appendChild(option);
                })

                packages.forEach((package, index) => {
                    var i = 0;
                    data.forEach(item => {
                        if (item.package == package) {
                            i++;
                            const target_parent = document.getElementById('target-body-package-' + package);

                            const target_div = document.createElement('div');
                            target_div.setAttribute('class', 'card my-3');

                            target_parent.appendChild(target_div);

                            const target_header = document.createElement('div');
                            target_header.setAttribute('id', 'header-option-' + i);
                            target_header.setAttribute('class', 'card-header');
                            target_header.innerHTML = 'Optiunea ' + i;

                            target_div.appendChild(target_header);

                            const target_body = document.createElement('div');
                            target_body.setAttribute('id', 'p' + package + 'o' + i)
                            target_body.setAttribute('class', 'card-body d-flex flex-column');

                            target_div.appendChild(target_body);

                            const option = document.createElement('div');
                            option.setAttribute('class', 'list-group-item');
                            option.setAttribute('id', item.course_id);
                            option.innerHTML = item.name;

                            target_body.appendChild(option);
                        }
                    })
                    if (index == 0) {
                        document.getElementById('source-package-' + package).hidden = false;
                        document.getElementById('target-package-' + package).hidden = false;
                    } else {
                        document.getElementById('source-package-' + package).hidden = true;
                        document.getElementById('target-package-' + package).hidden = true;
                    }
                })
            }
        })
    })
}

function changePackage() {
    selectedPackage = document.getElementById('package-select').value;

    for (let index = 1; index <= 10; index++) {
        var source = document.getElementById('source-package-' + index);
        if (source != undefined) {
            source.hidden = true;
        }
        var target = document.getElementById('target-package-' + index);
        if (target != undefined) {
            target.hidden = true;
        }
    }

    document.getElementById('source-package-' + selectedPackage).hidden = false;
    document.getElementById('target-package-' + selectedPackage).hidden = false;
}

function getOffersNumber() {
    document.getElementById("notification_number").hidden = true;
    $(function() {
        $.ajax({
            type: "GET",
            url: "trades/getTradeOffersNumber",
            success: function(response) {
                var data = JSON.parse(response);
                if (data > 0) {
                    document.getElementById("notification_number").innerHTML = data;
                    document.getElementById("notification_number").hidden = false;
                }
            }
        })
    })
}

function saveChoices() {
    var options = new Array();
    var unselected = 0;
    for (let i = 1; i <= 10; i++) {
        for (let j = 1; j <= 10; j++) {
            var option = document.getElementById('p' + i + 'o' + j);
            if (option != undefined) {
                if (option.firstChild) {
                    options.push({
                        "id": option.firstChild.id,
                        "package": i,
                        "priority": j
                    })
                } else {
                    unselected++;
                }
            }
        }
    }

    if (unselected == 0) {
        $('#infoModal').modal()
        document.getElementById("infoModalStatus").textContent = 'Pending...'
        document.getElementById("infoModalMsg").innerHTML = ''
        $('#loading-image').show();
        $(function() {
            $.ajax({
                type: "post",
                url: "choices/insert",
                data: {
                    "data": options
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    document.getElementById("infoModalStatus").textContent = data.status + '!'
                    document.getElementById("infoModalMsg").textContent = data.msg
                },
                complete: function() {
                    $('#loading-image').hide();
                }
            })
        })
    } else {
        $('#infoModalNoClose').modal()
        document.getElementById("infoModalStatusNoClose").textContent = 'Error!'
        document.getElementById("infoModalMsgNoClose").textContent = 'Please select your options for all packages!'
    }
}