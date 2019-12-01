function searchTable() {
    var input, divList, divTable, tableId, i;

    input = document.getElementById("tableInput").value;
    divList = document.getElementById("waiterTableList");
    divTable = divList.getElementsByClassName("row");

    for (i = 0; i < divTable.length; i++) {
        tableId = divTable[i].getElementsByTagName("button")[0].innerText;
        if (tableId.indexOf(input) > -1) {
            divTable[i].style.display = "";
        } else {
            divTable[i].style.display = "none";
        }
    }
}
