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
            <table id="company_dt" class="ui celled table text-center align-middle w-100" {{ stimulus_controller('companyTable', { url: path('app_sa_company_dt') } ) }}>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>About</th>
                        <th>Established At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
    `);

    console.log("Initializing");
    $("#" + this.element.id).DataTable({
      processing: true,
      serverSide: true,
      retrieve: false,
      ajax: this.urlValue,
      columns: [
        { data: "id" },
        { data: "name" },
        { data: "about" },
        {
          data: "establishedAt",
          render: function (data) {
            return new Date(data).toLocaleDateString("en-IN", {
              dateStyle: "medium",
            });
          },
        },
        {
          data: "isActive",
          render: function (data, type, row) {
            return data
              ? `<a href="/superadmin/company/delete/${row.id}" class="btn btn-success">Active</a>`
              : `<a href="/superadmin/company/delete/${row.id}" class="btn btn-danger">In Active</a>`;
          },
        },
        {
          mData: "id",
          mRender: function (data) {
            return (
              "<a class='btn btn-primary' href='/superadmin/company/" +
              data +
              "'>View</a>"
            );
          },
        },
      ],
    });
  }
}
