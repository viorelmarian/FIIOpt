function saveCourseOffer() {
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/insertTradeOffer/' + choice,
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
}

function openConfirmationOfferCourse(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#loading-image').hide();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/determineTradeOffer/' + choice,
            success: function(response) {
                var data = JSON.parse(response);

                $('#confirmModal').modal()
                document.getElementById("confirmModalMsg").innerHTML = data.msg
            }
        })
    })
}

function postCourseTrade() {
    var e = document.getElementById("inputCourse")
    var courseForTrade = e.options[e.selectedIndex].value

    var data = courseForTrade

    var courseOptions = document.getElementsByClassName('form-check-input')
    for (courseId in courseOptions) {
        if (courseOptions[courseId].checked) {
            data = data + '.' + courseOptions[courseId].value
        }
    }

    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/insert/' + data,
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
}

function getTradableOptions() {
    var e = document.getElementById("inputCourse")

    var courseId = e.options[e.selectedIndex].value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'trades/getTradableCourses/' + courseId,
            success: function(response) {
                var data = JSON.parse(response);

                const c = document.getElementById('inputChk')
                c.innerHTML = ""

                data.forEach(item => {
                    const r = document.createElement("div")
                    r.setAttribute("class", "row")

                    c.appendChild(r)

                    const l = document.createElement("label")
                    l.setAttribute("for", item.course_id)
                    l.innerHTML = item.name

                    r.appendChild(l)

                    const i = document.createElement("input")
                    i.setAttribute("type", "checkbox")
                    i.setAttribute("class", "form-check-input")
                    i.setAttribute("value", item.course_id)

                    r.appendChild(i)
                })
            }
        })
    })
}

function getTransferableOptions() {
    var e = document.getElementById("inputFromCourse")
    var s = document.getElementById("inputToCourse")
    var courseId = e.options[e.selectedIndex].value

    $(function() {
        $.ajax({
            type: "GET",
            url: 'trades/getTradableCourses/' + courseId,
            success: function(response) {
                var data = JSON.parse(response);
                $("#inputToCourse").empty();
                data.forEach(item => {
                    const o = document.createElement("option")
                    o.setAttribute("value", item.course_id)
                    o.innerHTML = item.name

                    s.appendChild(o)
                })
            }
        })
    })
}

function getTrades() {

    $(function() {
        $.ajax({
            type: "GET",
            url: 'trades/get/' + search.value,
            success: function(response) {
                var data = JSON.parse(response);

                const root = document.getElementById('trades-root')
                root.innerHTML = "";

                data.forEach(item => {
                    const d = document.createElement("div")
                    d.setAttribute("class", "trades-card")
                    d.setAttribute("trade_id", item.trade_id)

                    root.appendChild(d)

                    const p = document.createElement("p")
                    stud_name = item.username.split('.')
                    p.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' + ' ofera ' +
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
            }
        })
    })
}

function getAssignedCourses() {

    $(function() {
        $.ajax({
            type: "GET",
            url: 'assignations/get/trade',
            success: function(response) {
                var data = JSON.parse(response);

                const s1 = document.getElementById('inputCourse')
                const s2 = document.getElementById('inputFromCourse')

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
            }
        })
    })
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
            url: 'users/getLoggedUser',
            success: function(response) {
                var data = JSON.parse(response);

                username = document.getElementById('username')

                stud_name = data.split('.')
                username.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>'
            }
        })
    })
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
            }
        })
    })
}

function openConfirmationCancel(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/cancelTransferRequest/' + choice,
            success: function(response) {
                var data = JSON.parse(response);

                document.getElementById("infoModalStatus").textContent = data.status + '!'
                document.getElementById("infoModalMsg").innerHTML = data.msg
            },
            complete: function() {
                $('#loading-image').hide();
            }
        })
    })
}

function insertTransferRequest() {
    var e = document.getElementById("inputFromCourse")
    var transferFromCourse = e.options[e.selectedIndex].value

    var e = document.getElementById("inputToCourse")
    var transferToCourse = e.options[e.selectedIndex].value

    var data = transferFromCourse + '.' + transferToCourse
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/insertTransferRequest/' + data,
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
}