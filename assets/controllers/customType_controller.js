import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

var $url = $(".select_type").data("specific-type-url");

export default class extends Controller {

  connect() {
    var type = $(".select_type").val();
    console.log(type);
    if(type.length != 0){
      this.ajaxCall();
    }
  }

  changeValue(e) {
    e.preventDefault();
    this.ajaxCall();
  }

  ajaxCall() {
    const dept = document.getElementById('subscription_criteria_dept');
    const user = document.getElementById('subscription_criteria_user');
    const storage = document.getElementById('subscription_criteria_storage');
    const id = document.getElementById('subscription_subscription_id');

    const selectedType = document.getElementsByClassName('select_type')[0].value;

    const custom_type = document.getElementById('custom_type');

    $.ajax({
      url: $url,
      data: {
        type: selectedType,
      },
      dataType: 'json',
      success: function (html) {
        console.log(html);

        var json = html;

        if (json.Type == "Other") {

          var inputTextField = document.createElement("INPUT");
          inputTextField.setAttribute("type", "text");
          inputTextField.setAttribute("id", "subscription_criteria_customType");
          inputTextField.setAttribute("name", "subscription[customType]");
          inputTextField.setAttribute("class", "form-control");

          inputTextField.setAttribute("required", "true");
          inputTextField.setAttribute("placeholder", "Enter Custom Type");

          dept.setAttribute('value', 0);
          dept.removeAttribute('disabled');

          user.setAttribute('value', 0);
          user.removeAttribute('disabled');

          storage.setAttribute('value', 0);
          storage.removeAttribute('disabled');

          id.setAttribute('value', 0);

          custom_type.innerHTML = "";
          custom_type.appendChild(inputTextField);
          custom_type.setAttribute('class', '');

          return;
        } else if (json.Type == "Empty") {
          return;
        } else {

          dept.setAttribute('value', json.dept);
          dept.setAttribute('disabled', 'true');

          user.setAttribute('value', json.user);
          user.setAttribute('disabled', 'true');

          storage.setAttribute('value', json.storage);
          storage.setAttribute('disabled', 'true');

          id.setAttribute('value', json.id);

          custom_type.setAttribute('class', 'd-none');

          return;
        }
      },
    });
  }
}
