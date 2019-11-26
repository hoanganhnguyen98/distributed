// show sidebar
document.getElementById('accountSidebar').classList.add('show');

// edit information button
document.getElementById('editInformation').onclick = function() {
    // display form to edit
    document.getElementById('name').removeAttribute('readonly');
    document.getElementById('address').removeAttribute('readonly');
    document.getElementById('phone').removeAttribute('readonly');
    document.getElementById('area').removeAttribute('disabled');
    document.getElementById('role').removeAttribute('disabled');

    //display suggest and button
    document.getElementById('editButton').style.display = "inline";
    document.getElementById('suggestArea').style.display = "inline";
    document.getElementById('suggestRole').style.display = "inline";

    // hidden other functions
    document.getElementById('changeImage').setAttribute('disabled', '');
    document.getElementById('deleteAccount').setAttribute('disabled', '');
    document.getElementById('editInformation').style.display = "none";

    //display cancel button
    document.getElementById('cancelEditButton').style.display = "inline";
};

// cancel edit information button
document.getElementById('cancelEditButton').onclick = function() {
    // display form to edit
    document.getElementById('name').setAttribute('readonly', '');
    document.getElementById('address').setAttribute('readonly', '');
    document.getElementById('phone').setAttribute('readonly', '');
    document.getElementById('area').setAttribute('disabled', '');
    document.getElementById('role').setAttribute('disabled', '');

    //display suggest and button
    document.getElementById('editButton').style.display = "none";
    document.getElementById('suggestArea').style.display = "none";
    document.getElementById('suggestRole').style.display = "none";

    // hidden other functions
    document.getElementById('changeImage').removeAttribute('disabled');
    document.getElementById('deleteAccount').removeAttribute('disabled');
    document.getElementById('editInformation').style.display = "inline";

    //display cancel button
    document.getElementById('cancelEditButton').style.display = "none";
};

// change image button
document.getElementById('changeImage').onclick = function() {
    // display form to edit
    document.getElementById('image').style.display = "inline";

    // hidden other functions
    document.getElementById('editInformation').setAttribute('disabled', '');
    document.getElementById('deleteAccount').setAttribute('disabled', '');
    document.getElementById('changeImage').style.display = "none";

    //display cancel button
    document.getElementById('cancelChangeButton').style.display = "inline";
};

// cancel change image button
document.getElementById('cancelChangeButton').onclick = function() {
    // display form to edit
    document.getElementById('image').style.display = "none";

    // hidden other functions
    document.getElementById('editInformation').removeAttribute('disabled');
    document.getElementById('deleteAccount').removeAttribute('disabled');
    document.getElementById('changeImage').style.display = "inline";

    //display cancel button
    document.getElementById('cancelChangeButton').style.display = "none";
};
