function addOptions() {
    // get position to add more options
    var newOption = document.getElementById("newOption");

    // create new food name input
    var inputFoodName = document.createElement("input");
    inputFoodName.setAttribute("class", "form-control border border-primary");
    inputFoodName.setAttribute("list", "foodList");
    inputFoodName.setAttribute("name", "food_id[]");
    inputFoodName.setAttribute("type", "text");
    inputFoodName.setAttribute("placeholder", "...");

    // create new amount input
    var inputAmount = document.createElement("input");
    inputAmount.setAttribute("class", "form-control border border-primary");
    inputAmount.setAttribute("name", "amount[]");
    inputAmount.setAttribute("type", "number");
    inputAmount.setAttribute("placeholder", "... 123");

    // add new option    
    newOption.appendChild(inputFoodName);
    newOption.appendChild(inputAmount);
}
