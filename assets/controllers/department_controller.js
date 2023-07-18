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
        const htmlContent = (data, type, row) => {
            return `
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop-${row.id}">
                    <i class='bx bxs-show' ></i>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop-${row.id}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">${row.name}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                ${row.description}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
                            </div>
                        </div>
                    </div>
                </div>
                <a href="${row.isActive ? '' : ('/admin/dashboard/department/delete/' + row.id)}" class="btn btn-outline-success ${row.isActive ? 'disabled':''}"><i class='bx bxs-trash'></i></a> 
                <a href="${row.isActive ? '' : ('/admin/dashboard/department/update/' + row.id)}" class="btn btn-outline-primary ${row.isActive ? 'disabled':''}"><i class='bx bxs-edit' ></i></a>`;
              
        };
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
                        return data ? `<a href="/admin/dashboard/department/updateStatus/${row.id}" class="btn btn-danger">InActive</a>`
                            : `<a href="/admin/dashboard/department/updateStatus/${row.id}" class="btn btn-success">Active</a>`;
                    }
                },
                {
                    data: 'isDeleted',
                    render: htmlContent
                }
            ]
        });
    }
}
