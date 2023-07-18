import { Controller } from "@hotwired/stimulus";
import "datatables.net-se";
import "datatables.net-buttons-se";
import "datatables.net-responsive-se";
import $ from "jquery";

export default class extends Controller {
  static values = {
    url: String,
  };

  initialize() {
    $("#" + this.element.id).DataTable({
      processing: true,
      serverSide: true,
      retrieve: false,
      ajax: this.urlValue,
      columns: [
        { data: "id" },
        { data: "orderId" },
        { data: "name" },
        { data: "type" },
        { data: "status" },
      ],
    });
  }
}
