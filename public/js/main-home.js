// add search food name function
function searchFood() {
    var input, foodAll, foodName, i;

    input = document.getElementById("foodInput").value.toUpperCase();
    foodAll = document.getElementById("allFoodList");
    
    food = foodAll.getElementsByClassName("col-3");

    for (i = 0; i < food.length; i++) {
        foodName = food[i].getElementsByTagName("p")[0].innerText;
        if (foodName.toUpperCase().indexOf(input) > -1) {
            food[i].style.display = "";
        } else {
            food[i].style.display = "none";
        }
    }
}
