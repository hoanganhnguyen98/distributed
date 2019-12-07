// show form to edit bill information
document.getElementById('editBill').onclick = function() {
    // display form to edit
    document.getElementById('name').removeAttribute('readonly');
    document.getElementById('street').removeAttribute('readonly');
    document.getElementById('district').removeAttribute('readonly');
    document.getElementById('city').removeAttribute('readonly');
    document.getElementById('phone').removeAttribute('readonly');
    document.getElementById('email').removeAttribute('readonly');

    // hidden function buttons
    document.getElementById('editBill').style.display = "none";
    document.getElementById('enterTotalPrice').style.display = "none";
    document.getElementById('totalPriceSuggest').style.display = "none";


    document.getElementById('payVNDButton').style.display = "none";
    document.getElementById('payUSDButton').style.display = "none";
    document.getElementById('checkTrue').style.display = "none";
    document.getElementById('checkFalse').style.display = "none";

    // display cancel and submit button
    document.getElementById('cancelEditButton').style.display = "inline";
    document.getElementById('editBillButton').style.display = "inline";
};

// cancel edit bill information
document.getElementById('cancelEditButton').onclick = function() {
    // display form to edit
    document.getElementById('name').setAttribute('readonly', '');
    document.getElementById('street').setAttribute('readonly', '');
    document.getElementById('district').setAttribute('readonly', '');
    document.getElementById('city').setAttribute('readonly', '');
    document.getElementById('phone').setAttribute('readonly', '');
    document.getElementById('email').setAttribute('readonly', '');

    enterTotalPriceToPay();

    // display function buttons
    document.getElementById('editBill').style.display = "inline";
    document.getElementById('enterTotalPrice').style.display = "inline";
    document.getElementById('totalPriceSuggest').style.display = "inline";

    // hidden cancel and submit button
    document.getElementById('cancelEditButton').style.display = "none";
    document.getElementById('editBillButton').style.display = "none";
};

// enter true total price to display pay button
function enterTotalPriceToPay() {
    var input, totalVNDPrice, totalUSDPrice;

    input = document.getElementById("enterTotalPrice");
    totalVNDPrice = document.getElementById("totalVNDPrice");
    totalUSDPrice = document.getElementById("totalUSDPrice");

    if (input.value.length != 0) {
        if (input.value == totalVNDPrice.innerText) {
            document.getElementById('payVNDButton').style.display = "inline";
            document.getElementById('payUSDButton').style.display = "none";
            document.getElementById('checkTrue').style.display = "inline";
            document.getElementById('checkFalse').style.display = "none";
            input.setAttribute("class", "form-control border border-success");
        } else if (input.value == totalUSDPrice.innerText) {
            document.getElementById('payVNDButton').style.display = "none";
            document.getElementById('payUSDButton').style.display = "inline";
            document.getElementById('checkTrue').style.display = "inline";
            document.getElementById('checkFalse').style.display = "none";
            input.setAttribute("class", "form-control border border-success");
        } else {
            document.getElementById('checkTrue').style.display = "none";
            document.getElementById('checkFalse').style.display = "inline";
            document.getElementById('payVNDButton').style.display = "none";
            document.getElementById('payUSDButton').style.display = "none";
            input.setAttribute("class", "form-control border border-danger");
        }
    }
}
