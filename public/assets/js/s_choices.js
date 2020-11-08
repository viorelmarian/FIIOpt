function getChoices() {
    $(function() {
        $.ajax({
            type: "GET",
            url: "choices/get",
            success: function(response) {
                var data = JSON.parse(response);

                const row = document.getElementById('root-choices')
                row.innerHTML = "";

                data.forEach(item => {

                    const tr = document.createElement('tr')
                    row.appendChild(tr)

                    const td1 = document.createElement('td')
                    td1.innerText = item.name

                    tr.appendChild(td1)

                    const td2 = document.createElement('td')
                    td2.innerText = item.professor_1

                    tr.appendChild(td2)

                    const td4 = document.createElement('td')
                    td4.setAttribute("class", "center-td")
                    td4.innerText = item.year

                    tr.appendChild(td4)

                    const td5 = document.createElement('td')
                    td5.setAttribute("class", "center-td")
                    td5.innerText = item.package

                    tr.appendChild(td5)

                    const td6 = document.createElement('td')
                    td6.innerText = item.status

                    tr.appendChild(td6)
                })
            }
        })
    })
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

function getAssignations() {
    $(function() {
        $.ajax({
            type: "GET",
            url: "assignations/get/display",
            success: function(response) {
                var data = JSON.parse(response);

                const row = document.getElementById('root-assignations')
                row.innerHTML = "";

                data.forEach(item => {

                    const tr = document.createElement('tr')
                    row.appendChild(tr)

                    const td1 = document.createElement('td')
                    td1.innerText = item.name

                    tr.appendChild(td1)

                    const td2 = document.createElement('td')
                    td2.innerText = item.professor_1

                    tr.appendChild(td2)

                    const td4 = document.createElement('td')
                    td4.setAttribute("class", "center-td")
                    td4.innerText = item.year

                    tr.appendChild(td4)

                    const td5 = document.createElement('td')
                    td5.setAttribute("class", "center-td")
                    td5.innerText = item.package

                    tr.appendChild(td5)

                    const td6 = document.createElement('td')
                    td6.innerText = item.status

                    tr.appendChild(td6)
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
            url: "users/getLoggedUser",
            success: function(response) {
                var data = JSON.parse(response);

                username = document.getElementById('username')

                stud_name = data.split('.')
                username.innerHTML = '<b>' + capitalize(stud_name[0]) + ' ' + capitalize(stud_name[1]) + '</b>'
            }
        })
    })
}