
$(document).ready(function() {
  $("#selectButtonContainer").on('click', ".selectButton", setSelectionStatus);
});

functions = {
  deleteCallingElement: function() {
    $(this).parent().parent().remove();
  },
  
  reloadPage: function() {
    location.reload();
  },
  
  //Gets selection status from the database, and updates the calling element accordingly (toggles between add/remove based on selection)
  getSelectionStatusAndUpdateElement: function() {
    callingElement = this;
    
    const formData = new FormData();
    formData.append('method', 'query');
    formData.append('Rname', callingElement.getAttribute("Rname"));
    
    $.ajax({
      callingElement: callingElement,
      method: 'post',
      processData: false,
      contentType: false,
      cache: false,
      enctype: 'multipart/form-data',
      url: 'selectaction.php',
      data: formData
    }).done(function( returnValue ) {
      if(returnValue.length > 0) {
        this.callingElement.setAttribute("nextSelectMethod", "remove");
        this.callingElement.innerHTML = this.callingElement.getAttribute("removeText");
      }
      else {
        this.callingElement.setAttribute("nextSelectMethod", "add");
        this.callingElement.innerHTML = this.callingElement.getAttribute("addText");
      }
    }).fail(function( XMLHttpRequest, textStatus ) {
      alert("Error: " + textStatus);
    });
  }
};

//Adds/removes data to/from the database selection table
function setSelectionStatus() {
  callingElement = this;
  
  const formData = new FormData();
  formData.append('method', callingElement.getAttribute("nextSelectMethod"));
  if(callingElement.hasAttribute("Rname"))
	formData.append('Rname', callingElement.getAttribute("Rname"));
  if(callingElement.hasAttribute("Iname"))
    formData.append('Iname', callingElement.getAttribute("Iname"));
  if(callingElement.hasAttribute("Unit"))
    formData.append('Unit', callingElement.getAttribute("Unit"));
  
  $.ajax({
    method: 'post',
    processData: false,
    contentType: false,
    cache: false,
    enctype: 'multipart/form-data',
    url: 'selectaction.php',
    data: formData
  }).done(function( returnValue ) {
    functions[callingElement.getAttribute("selectionCallbackFunction")].call(callingElement);
  }).fail(function( XMLHttpRequest, textStatus ) {
    alert("Error: " + textStatus);
  });
}
