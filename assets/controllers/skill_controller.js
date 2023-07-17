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
        console.log('Initializing' + this.element.id + this.urlValue);
        $('#' + this.element.id).DataTable({
            processing: true,
            serverSide: true,
            'ajax': this.urlValue,
            columns: [
                { data: 'name' },
                {
                    data: 'createdAt',
                    render: function (data) {
                        return new Date(data).toLocaleDateString('en-IN', { dateStyle: 'medium' });
                    }
                },
                {
                    data: 'updatedAt',
                    render: function (data) {
                        return new Date(data).toLocaleDateString('en-IN', { dateStyle: 'medium' });
                    }
                },
                {
                    data: 'isActive',
                    render: function (data, type, row) {
                        return data ? `<a href="/admin/dashboard/skills/updateStatus/${row.id}" class="btn btn-success">Active</a>`
                            : `<a href="/admin/dashboard/skills/updateStatus/${row.id}" class="btn btn-danger">InActive</a>`;
                    }
                },
                {
                    data: 'isDeleted',
                    render: function (data, type, row) {
                        return `<a href="/admin/dashboard/skills/delete/${row.id}" class="btn btn-success">Delete</a> 
                        <a href="/admin/dashboard/skills/update/${row.id}" class="btn btn-primary">Update</a>`;
                    }
                }
            ]
        });
    }
}
