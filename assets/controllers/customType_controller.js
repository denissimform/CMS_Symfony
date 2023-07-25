import { Controller } from "@hotwired/stimulus";
import $ from "jquery";

export default class extends Controller {
  static values = {
    url: String,
  }

  connect() {
    if ($("#subscription_type").val()) {
      this.ajaxCall();
    }
  }

  changeValue() {
    this.ajaxCall();
  }

  ajaxCall() {
    $.ajax({
      url: this.urlValue,
      data: {
        type: $("#subscription_type").val(),
      },
      dataType: 'json',
      success: function (json) {
        if (json.Type == "Other") {
          const inputTextField = $("<input>")
            .attr({
              "type": "text",
              "id": "subscription_criteria_customType",
              "name": "subscription[customType]",
              "class": "form-control",
              "placeholder": "Enter Custom Type",
              "required": true,
            });

          $("#subscription_criteria_dept").attr({ 'value': 0 });
          $("#subscription_criteria_user").attr({ 'value': 0 });
          $("#subscription_criteria_storage").attr({ 'value': 0 });
          $("#subscription_subscription_id").attr({ 'value': 0 });

          $("#custom_type").html("").append(inputTextField).attr({ "class": "" });
        } else {
          $("#subscription_criteria_dept").attr({ 'value': json.dept });
          $("#subscription_criteria_user").attr({ 'value': json.user });
          $("#subscription_criteria_storage").attr({ 'value': json.storage });
          $("#subscription_subscription_id").attr({ 'value': json.id });

          $("#custom_type").attr({ 'class': 'd-none' });
        }
      },
      error: function (err) {
        console.error(err.responseText);
      }
    });
  }
}
