$(function () {

  var $typeSelect = $(".js-user-form-type");

  var $customType = $(".js-custom-type-target");
  var $dept = $("#subscription_criteria_dept");
  var $user = $("#subscription_criteria_user");
  var $storage = $("#subscription_criteria_storage");
  var $id = $('#subscription_subscription_id');

  console.log('outsier');
  $typeSelect.on("change", function (e) {

    $.ajax({
      url: $typeSelect.data("specific-type-url"),
      data: {
        type: $typeSelect.val(),
      },
      success: function (html) {

        var json = JSON.parse(html);
        if (json.Type == "Other") {

          var inputTextField = document.createElement("INPUT");
          inputTextField.setAttribute("type", "text");
          inputTextField.setAttribute("id", "subscription_criteria_customType");
          inputTextField.setAttribute("name", "subscription[customType]");
          inputTextField.setAttribute("class", "form-control");

          inputTextField.setAttribute("required", "true");
          inputTextField.setAttribute("placeholder", "Enter Custom Type");

          $dept.removeAttr('disabled').val(0);
          $user.removeAttr('disabled').val(0);
          $storage.removeAttr('disabled').val(0);
          $id.val(-1);
          $customType.html(inputTextField).removeClass("d-none");
          
          return;

        }

        $dept.val(json.dept).attr("disabled", "true");
        $user.val(json.user).attr("disabled", "true");
        $storage.val(json.storage).attr("disabled", "true");
        $id.val(json.id);
        $customType.addClass("d-none");
        
      },
    });
  });
});
