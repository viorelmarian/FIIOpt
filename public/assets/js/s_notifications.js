function getTradeOffers() {

    $(function() {
        $.ajax({
            type: "GET",
            url: 'trades/getTradeOffers',
            success: function(response) {
                var data = JSON.parse(response);

                const root = document.getElementById('trade_offers-root')

                data.forEach(item => {
                    const d = document.createElement("div")
                    d.setAttribute("class", "trades-card")

                    root.appendChild(d)

                    const p = document.createElement("p")
                    stud_name = item.offer_student_name.split('.')
                    p.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>' + ' ofera ' +
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
            }
        })
    })
}

function acceptTrade(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/acceptTrade/' + choice,
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

function declineTrade(e) {
    this.choice = e.target.id
    var choice = this.choice
    $('#infoModal').modal()
    document.getElementById("infoModalStatus").textContent = 'Pending...'
    $('#loading-image').show();
    $(function() {
        $.ajax({
            type: "POST",
            url: 'trades/declineTrade/' + choice,
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
                console.log(data)
                stud_name = data.split('.')
                username.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>'
            }
        })
    })
}