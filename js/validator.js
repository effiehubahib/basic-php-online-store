$(document).ready(function() {
    $( ".i-today-order" ).click(function() {
      $( ".todayOrderSearch" ).toggle( "slow", function() {
        // Animation complete.
      });
    });
    $( ".i-week-order" ).click(function() {
      $( ".weekOrderSearch" ).toggle( "slow", function() {
        // Animation complete.
      });
    });
     $( ".i-month-order" ).click(function() {
      $( ".monthOrderSearch" ).toggle( "slow", function() {
        // Animation complete.
      });
    });
});

function validateAmountPaid(obj, next) {
    // fetch value and remove any non-digits
    // you could write more code to prevent typing of non-digits
    var orig = obj.value;
    var mod = orig.replace(/[^0-9.]/g, "");

        // only set this if neccessary to prevent losing cursor position
        if (orig != mod) {
            obj.value = mod;
        }
    // convert to number and check value of the number
    var num = Number(obj.value);
    var retVal = true;
    // don't know what you want to do here if the two digit value is out of range
    if (num < 0) {
        alert("Value can't be negative");
        retVal = false;
    }
    

    return(retVal);
}

function validateNumOnly(obj, next){
    var orig = obj.value;
    var mod = orig.replace(/[^0-9]/g, "");

        // only set this if neccessary to prevent losing cursor position
        if (orig != mod) {
            obj.value = mod;
        }
    // convert to number and check value of the number
    var num = Number(obj.value);
    var retVal = true;
    // don't know what you want to do here if the two digit value is out of range
    if (num <= 0) {
        alert("Value can't be zero or negative");
        retVal = false;
    }
    

    return(retVal);
}