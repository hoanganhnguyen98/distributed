// display sidebar after clicking
document.getElementById('billSidebar').classList.add('show');

// filter by id
function searchID() {
    var input, filter, tbody, td, tr, i, txtValue;

    input = document.getElementById("idInput");
    filter = input.value.toUpperCase();
    tbody = document.getElementById("currentBill");
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0]; // 0 - first column
        txtValue = td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// filter by table
function searchTable() {
    var input, filter, tbody, td, tr, i, txtValue;

    input = document.getElementById("tableInput");
    filter = input.value.toUpperCase();
    tbody = document.getElementById("currentBill");
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1]; // 1 - second column
        txtValue = td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// filter by name
function searchName() {
    var input, filter, tbody, td, tr, i, txtValue;

    input = document.getElementById("nameInput");
    filter = input.value.toUpperCase();
    tbody = document.getElementById("currentBill");
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2]; // 2 - third column
        txtValue = td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// filter by phone
function searchPhone() {
    var input, filter, tbody, td, tr, i, txtValue;

    input = document.getElementById("phoneInput");
    filter = input.value;
    tbody = document.getElementById("currentBill");
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[3]; // 3 - fourth column
        txtValue = td.innerText;
        if (txtValue.indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// filter by book time
function searchBook() {
    var input, filter, tbody, td, tr, i, txtValue;

    input = document.getElementById("bookInput");
    filter = input.value;
    tbody = document.getElementById("currentBill");
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[4]; // 4 - fifth column
        txtValue = td.innerText;
        if (txtValue.indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// filter by pay time
function searchPay() {
    var input, filter, tbody, td, tr, i, txtValue;

    input = document.getElementById("payInput");
    filter = input.value;
    tbody = document.getElementById("currentBill");
    tr = tbody.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[5]; // 5 - sixth column
        txtValue = td.innerText;
        if (txtValue.indexOf(filter) > -1) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}
