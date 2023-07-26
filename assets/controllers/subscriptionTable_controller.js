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
        <table id="subscription_dt" class="ui celled table text-center align-middle w-100" {{ stimulus_controller('subscriptionTable', { url: path('app_sa_subscription_dt') } ) }}>
            <thead>
              <tr>
                <th>ID</th>
                <th>Subscription Type</th>
                <th>Duration</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
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
        {
          data: "type",
          render: function (data, type, row) {
            return (
              data +
              ` : [ Departments - ${row.criteria_dept}, Users - ${row.criteria_user}, Storage - ${row.criteria_storage} ]`
            );
          },
        },
        {
          data: "duration",
          render: function (data) {
            return data + ` Months`;
          },
        },
        {
          data: "price",
          render: function (data) {
            return data + ` Rs.`;
          },
        },
        {
          data: "isActive",
          render: function (data, type, row) {
            return data
              ? `<a href="/superadmin/subscription/delete/${row.id}" class="btn btn-success">Active</a>`
              : `<a href="/superadmin/subscription/delete/${row.id}" class="btn btn-danger">In Active</a>`;
          },
        },
        {
          mData: "id",
          mRender: function (data) {
            return (
              "<a class='btn btn-primary' href='/superadmin/subscription/edit/" +
              data +
              "'>Edit</a>"
            );
          },
        },
      ],
    });
  }
}
