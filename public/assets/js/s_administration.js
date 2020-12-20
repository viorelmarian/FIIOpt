function loadPage() {
    switch (sessionStorage.getItem("position")) {
        case "courses":
            displayCourses()
            break;
        case "professors":
            displayProfessors()
            break;
        case "trades":
            displayTrades()
            break;
        case "transfers":
            displayTransfers()
            break;
        case "statistics":
            displayStatistics()
            break;
        default:
            break;
    }
}

function hideAll() {
    courses = document.getElementById("courses")
    courses.className = "hide"
    courses = document.getElementById("professors")
    courses.className = "hide"
    courses = document.getElementById("trades")
    courses.className = "hide"
    courses = document.getElementById("transfers")
    courses.className = "hide"
    courses = document.getElementById("statistics")
    courses.className = "hide"
}

function displayCourses() {
    hideAll()
    courses = document.getElementById("courses")
    courses.className = "show"
    sessionStorage.setItem("position", "courses")
}

function displayProfessors() {
    hideAll()
    courses = document.getElementById("professors")
    courses.className = "show"
    sessionStorage.setItem("position", "professors")
}

function displayTrades() {
    hideAll()
    courses = document.getElementById("trades")
    courses.className = "show"
    sessionStorage.setItem("position", "trades")
}

function displayTransfers() {
    hideAll()
    courses = document.getElementById("transfers")
    courses.className = "show"
    sessionStorage.setItem("position", "transfers")
}

function displayStatistics() {
    hideAll()
    courses = document.getElementById("statistics")
    courses.className = "show"
    sessionStorage.setItem("position", "statistics")
}

function getCycles() {

    $(function() {
        $.ajax({
            type: "GET",
            url: "courses/getStudyCycles",
            success: function(response) {
                var data = JSON.parse(response);

                const s = document.getElementById('course_cycle')

                i = 0
                data.forEach(item => {
                    i = i + 1
                    const o = document.createElement("option")
                    o.setAttribute("value", item.study_cycle_id)
                    o.innerHTML = i + '.   ' + item.name

                    s.appendChild(o)
                })
            }
        })
    })
}

function getProfessors() {

    $(function() {
        $.ajax({
            type: "GET",
            url: "courses/getProfessors",
            success: function(response) {
                var data = JSON.parse(response);

                const s1 = document.getElementById('course_professor1')
                const s2 = document.getElementById('course_professor2')
                const s3 = document.getElementById('inputProfessor')

                i = 0
                data.forEach(item => {
                    i++
                    const p1 = document.createElement("option")
                    p1.setAttribute("value", item.professor_id)
                    p1.innerHTML = i + '.   ' + item.title + ' ' + item.f_name + ' ' + item.l_name

                    s1.appendChild(p1)

                    const p2 = document.createElement("option")
                    p2.setAttribute("value", item.professor_id)
                    p2.innerHTML = i + '.   ' + item.title + ' ' + item.f_name + ' ' + item.l_name

                    s2.appendChild(p2)

                    const p3 = document.createElement("option")
                    p3.setAttribute("value", item.professor_id)
                    p3.innerHTML = i + '.   ' + item.title + ' ' + item.f_name + ' ' + item.l_name

                    s3.appendChild(p3)
                })
            }
        })
    })
}

function saveCourse() {

    var course_year = $("input[name='course_year']:checked").val();
    var course_package = $("input[name='course_package']:checked").val();

    data = 'id=' + document.getElementById("inputCourse").value + '&'
    data += 'name=' + document.getElementById("course_name").value + '&'
    data += 'year=' + course_year + '&'
    data += 'package=' + course_package + '&'
    data += 'cycle=' + document.getElementById("course_cycle").value + '&'
    data += 'link=' + document.getElementById("course_link").value + '&'
    data += 'no_studs=' + document.getElementById("course_no_studs").value + '&'
    data += 'professor1=' + document.getElementById("course_professor1").value + '&'
    data += 'professor2=' + document.getElementById("course_professor2").value

    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/saveCourse/' + data,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function saveProfessor() {
    data = 'id=' + document.getElementById("inputProfessor").value + '&'
    data += 'l_name=' + document.getElementById("l_name").value + '&'
    data += 'f_name=' + document.getElementById("f_name").value + '&'
    data += 'title=' + document.getElementById("title").value

    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/saveProfessor/' + data,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function deleteCourse() {
    courseId = document.getElementById("inputCourse").value

    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/deleteCourse/' + courseId,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function deleteProfessor() {
    professorId = document.getElementById("inputProfessor").value

    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/deleteProfessor/' + professorId,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function getCourses() {

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/getAllCourses',
            success: function(response) {
                var data = JSON.parse(response);

                const s = document.getElementById('inputCourse')

                i = 0
                data.forEach(item => {
                    i++
                    const o = document.createElement("option")
                    o.setAttribute("value", item.course_id)
                    o.innerHTML = i + '.   ' + item.name

                    s.appendChild(o)
                })
            }
        })
    })
}

function fillFormCourses() {
    var e = document.getElementById("inputCourse")

    var courseId = e.options[e.selectedIndex].value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/getById/' + courseId,
            success: function(response) {
                var data = JSON.parse(response);

                document.getElementById("course_name").value = data[0].name;
                // document.getElementById("course_year").value = data[0].year
                $("#course_year" + data[0].year).prop("checked", true);
                // document.getElementById("course_package").value = data[0].package
                $("#course_package" + data[0].package).prop("checked", true);
                document.getElementById("course_cycle").value = data[0].study_cycle_id
                document.getElementById("course_link").value = data[0].link
                document.getElementById("course_no_studs").value = data[0].no_of_students
                document.getElementById("course_professor1").value = data[0].professor_1
                document.getElementById("course_professor2").value = data[0].professor_2
            }
        })
    })

    var past_courses;
    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/getPastCourses',
            success: function(response) {
                past_courses = JSON.parse(response);
            }
        })
    })

    const dependencies = document.getElementById('dependencies');
    dependencies.innerHTML = '';

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/getPastCoursesforCourse/' + courseId,
            success: function(response) {
                var data = JSON.parse(response);


                var i = 0;
                data.forEach(item => {
                    i++;
                    var select = document.createElement('select');
                    select.setAttribute('id', 'dependency' + i);
                    select.setAttribute('class', 'form-control mb-3');

                    dependencies.appendChild(select);

                    past_courses.forEach(element => {
                        var option = document.createElement('option');
                        option.setAttribute('value', element.past_course_id);
                        if (element.past_course_id == item.past_course_id) {
                            select.value = element.past_course_id
                        }
                        option.innerHTML = element.past_course_name;
                        select.appendChild(option);
                    });
                })
            }
        })
    })
}

function fillFormProfessors() {
    var e = document.getElementById("inputProfessor")

    var professorId = e.options[e.selectedIndex].value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/getProfessorById/' + professorId,
            success: function(response) {
                var data = JSON.parse(response);

                document.getElementById("title").value = data[0].title
                document.getElementById("l_name").value = data[0].l_name
                document.getElementById("f_name").value = data[0].f_name
            }
        })
    })
}

function getTransferRequests() {

    $(function() {
        $.ajax({
            type: "GET",
            url: 'trades/getTransferRequests',
            success: function(response) {
                var data = JSON.parse(response);

                const root = document.getElementById('transfers-root')
                root.innerHTML = "";

                data.forEach(item => {
                    const d = document.createElement("div")
                    d.setAttribute("class", "trades-card")

                    root.appendChild(d)

                    const p = document.createElement("p")
                    stud_name = item.username.split('.')
                    p.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' + ' requested to be transferred to <b>' + item.name + '</b>.'

                    d.appendChild(p)

                    const btnAccept = document.createElement('a')
                    btnAccept.setAttribute('class', 'btn btn-notif btn-success')
                    btnAccept.setAttribute('id', item.transfer_id)
                    btnAccept.setAttribute('onclick', 'openConfirmationAcceptTransfer(event)')
                    btnAccept.textContent = '{ Accept }'

                    d.appendChild(btnAccept)

                    const btnDecline = document.createElement('a')
                    btnDecline.setAttribute('class', 'btn btn-notif btn-danger')
                    btnDecline.setAttribute('id', item.transfer_id)
                    btnDecline.setAttribute('onclick', 'openConfirmationDeclineTransfer(event)')
                    btnDecline.textContent = '{ Decline }'

                    d.appendChild(btnDecline)
                })
            }
        })
    })
}

function getTradesRequests() {

    $(function() {
        $.ajax({
            type: "GET",
            url: 'trades/getTradesRequests',
            success: function(response) {
                var data = JSON.parse(response);

                const root = document.getElementById('trades-root')
                root.innerHTML = "";

                data.forEach(item => {
                    const d = document.createElement("div")
                    d.setAttribute("class", "trades-card")

                    root.appendChild(d)

                    const p = document.createElement("p")
                    student_names = item.donor_student_username.split('.')
                    donor_name = capitalize(student_names[0]) + ' ' + capitalize(student_names[1])

                    student_names = item.receiver_student_username.split('.')
                    receiver_name = capitalize(student_names[0]) + ' ' + capitalize(student_names[1])


                    p.innerHTML = "Students <b>" + donor_name + "</b> and <b>" + receiver_name + " </b> want to trade the following courses: <br><br>" +
                        "<b>" + donor_name + "</b>: " + item.donor_course_name + "<br>" +
                        "<b>" + receiver_name + "</b>: " + item.receiver_course_name + "<br><br>" +
                        "Do you accept the trade?"

                    d.appendChild(p)

                    const btnAccept = document.createElement('a')
                    btnAccept.setAttribute('class', 'btn btn-notif btn-success')
                    btnAccept.setAttribute('id', item.trade_id)
                    btnAccept.setAttribute('onclick', 'openConfirmationAcceptTrade(event)')
                    btnAccept.textContent = '{ Accept }'

                    d.appendChild(btnAccept)

                    const btnDecline = document.createElement('a')
                    btnDecline.setAttribute('class', 'btn btn-notif btn-danger')
                    btnDecline.setAttribute('id', item.trade_id)
                    btnDecline.setAttribute('onclick', 'openConfirmationDeclineTrade(event)')
                    btnDecline.textContent = '{ Decline }'

                    d.appendChild(btnDecline)
                })
            }
        })
    })
}

const capitalize = (s) => {
    if (typeof s !== 'string')
        return ''
    return s.charAt(0).toUpperCase() + s.slice(1)
}

function openConfirmationAcceptTransfer(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/acceptTransferRequest/' + choice,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function openConfirmationAcceptTrade(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/acceptTradeRequest/' + choice,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function openConfirmationDeclineTransfer(e) {
    this.choice = e.target.id

    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/declineTransferRequest/' + this.choice,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            }
        })
    })
}

function openConfirmationDeclineTrade(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/declineTradeRequest/' + choice,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function assignCourses() {
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    document.getElementById("infoModalMsg").innerHTML = ''
    $(function() {
        $.ajax({
            type: "POST",
            url: 'courses/assignCourses',
            success: function() {

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = 'Success!'
                document.getElementById("infoModalMsg").innerHTML = 'Courses have been successfully assigned!'
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function addDependency() {
    var dependencies = document.getElementById('dependencies');
    var depno = dependencies.childElementCount + 1;

    var select = document.createElement('select');
    select.setAttribute('id', 'dependency' + depno);
    select.setAttribute('class', 'form-control mb-3');

    dependencies.appendChild(select);

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/getPastCourses',
            success: function(response) {
                var data = JSON.parse(response);

                const s = document.getElementById('inputCourse')

                i = 0
                data.forEach(item => {
                    var option = document.createElement('option');
                    option.setAttribute('value', item.past_course_id);
                    option.innerHTML = item.past_course_name;
                    select.appendChild(option);
                })
            }
        })
    })
}