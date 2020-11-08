function loadPage() {
    switch (sessionStorage.getItem("position")) {
        case "courses":
            displayCourses()
            break;
        case "professors":
            displayProfessors()
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
    data = 'id=' + document.getElementById("inputCourse").value + '&'
    data += 'name=' + document.getElementById("course_name").value + '&'
    data += 'year=' + document.getElementById("course_year").value + '&'
    data += 'package=' + document.getElementById("course_package").value + '&'
    data += 'cycle=' + document.getElementById("course_cycle").value + '&'
    data += 'link=' + document.getElementById("course_link").value + '&'
    data += 'no_studs=' + document.getElementById("course_no_studs").value + '&'
    data += 'professor1=' + document.getElementById("course_professor1").value + '&'
    data += 'professor2=' + document.getElementById("course_professor2").value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/saveCourse/' + data,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }
        })
    })
}

function saveProfessor() {
    data = 'id=' + document.getElementById("inputProfessor").value + '&'
    data += 'l_name=' + document.getElementById("l_name").value + '&'
    data += 'f_name=' + document.getElementById("f_name").value + '&'
    data += 'title=' + document.getElementById("title").value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/saveProfessor/' + data,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }
        })
    })
}

function deleteCourse() {
    courseId = document.getElementById("inputCourse").value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/deleteCourse/' + courseId,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
            }
        })
    })
}

function deleteProfessor() {
    professorId = document.getElementById("inputProfessor").value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'courses/deleteProfessor/' + professorId,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").textContent = data.msg
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

                document.getElementById("course_name").value = data[0].name
                document.getElementById("course_year").value = data[0].year
                document.getElementById("course_package").value = data[0].package
                document.getElementById("course_cycle").value = data[0].study_cycle_id
                document.getElementById("course_link").value = data[0].link
                document.getElementById("course_no_studs").value = data[0].no_of_students
                document.getElementById("course_professor1").value = data[0].professor_1
                document.getElementById("course_professor2").value = data[0].professor_2
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

                const root = document.getElementById('transfers')
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
                    btnAccept.setAttribute('onclick', 'openConfirmationAccept(event)')
                    btnAccept.textContent = '{ Accept }'

                    d.appendChild(btnAccept)

                    const btnDecline = document.createElement('a')
                    btnDecline.setAttribute('class', 'btn btn-notif btn-danger')
                    btnDecline.setAttribute('id', item.transfer_id)
                    btnDecline.setAttribute('onclick', 'openConfirmationDecline(event)')
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

function openConfirmationAccept(e) {
    this.choice = e.target.id

    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/acceptTransferRequest/' + this.choice,
            success: function(response) {
                var data = JSON.parse(response);

                $('#infoModal').modal()
                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            }
        })
    })
}

function openConfirmationDecline(e) {
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