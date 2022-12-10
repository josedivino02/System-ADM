//apenas numero
function onlynumber(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    //var regex = /^[0-9.,]+$/;
    var regex = /^[0-9.]+$/;
    if (!regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) theEvent.preventDefault();
    }
  }

//apenas letras
function ApenasLetras(e, t) {
try {
    if (window.event) {
    var charCode = window.event.keyCode;
    } else if (e) {
    var charCode = e.which;
    } else {
    return true;
    }
    if (
    (charCode > 64 && charCode < 91) ||
    (charCode > 96 && charCode < 123) ||
    (charCode > 191 && charCode <= 255) // letras com acentos
    ) {
    return true;
    } else {
    return false;
    }
} catch (err) {
    alert(err.Description);
}
}