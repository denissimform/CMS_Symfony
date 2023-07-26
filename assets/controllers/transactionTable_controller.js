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
    $("#table_div").html(`
        <table id="transaction_dt" class="ui celled table text-center align-middle w-100" {{ stimulus_controller('transactionTable', { url: path('app_sa_transaction_dt') } ) }}>
            <thead>
                <tr>
                  <th>ID</th>
                  <th>Order ID</th>
                  <th>Company</th>
                  <th>Subscription</th>
                  <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    `);

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
