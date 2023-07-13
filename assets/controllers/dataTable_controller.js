import { Controller } from '@hotwired/stimulus';
import 'datatables.net-se';
import 'datatables.net-buttons-se';
import 'datatables.net-responsive-se';
import $ from 'jquery';

export default class extends Controller {
    static values = {
        url: String
    }

    initialize() {
        console.log('Initializing');
        $('#' + this.element.id).DataTable({
            processing: true,
            serverSide: true,
            'ajax': this.urlValue,
            columns: [
                { data: 'username' },
                { data: 'company.name' },
                { data: 'gender' },
                {
                    data: 'dob',
                    render: function (data) {
                        return new Date(data).toLocaleDateString('en-IN', { dateStyle: 'medium' });
                    }
                },
                {
                    data: 'isVerified',
                    render: function (data) {
                        return data ? "<span class='text-success'> Yes </span>" : "<span class='text-danger'> No </span>";
                    }
                },
                {
                    data: 'isActive',
                    render: function (data, type, row) {
                        return data ? `<a href="/superadmin/admin/delete/${row.id}" class="btn btn-success">Active</a>`
                            : `<a href="/superadmin/admin/delete/${row.id}" class="btn btn-danger">In Active</a>`;
                    }
                },
                {
                    "mData": "id",
                    "mRender": function (data) {
                        return "<a class='btn btn-primary' href='/superadmin/admin/edit/" + data + "'>EDIT</a>";
                    }
                }
            ]
        });
    }
}
