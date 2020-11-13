// add search food name function
$( "#foodInput" ).keyup(function() {
    var foodInput = $(this).val().toUpperCase();
    for (var i = 0; i < $( "#allFoodList .col-3" ).length; i++) {
        var food = $( "#allFoodList .col-3:eq("+i+") p:eq(0)" ).html().toUpperCase();
        var foodCol = $( "#allFoodList .col-3:eq("+i+")" );

        (food.indexOf(foodInput) > -1 ? foodCol.css("display", "") : foodCol.css("display", "none"))
    }
});
