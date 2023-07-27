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
        console.log('here')

        const htmlContent = (data, type, row) => {
            return `
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop-${row.id}">
                    <i class='bx bxs-show' ></i>
                </button>

                <!-- Modal -->
                <div class="modal fade" id="staticBackdrop-${row.id}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" 
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">${row.username}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th scope="row">Gender</th>
                                        <td>${row.gender}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td>${row.email}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Username</th>
                                        <td>${row.username}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Full name</th>
                                        <td>${row.firstName} ${row.lastName}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">DOB</th>
                                        <td>${new Date(row.dob).toLocaleDateString('en-IN', { dateStyle: 'medium' }) }</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
                            </div>
                        </div>
                    </div>
                </div>`;
              
        };
        $('#' + this.element.id).DataTable({
            processing: true,
            serverSide: true,
            'ajax': this.urlValue,
            columns: [
                { data: 'username' },
                {
                    data: 'isApproved',
                    render: function (data) {
                        return data ? "<span class='text-success'> Yes </span>" : "<span class='text-danger'> No </span>";
                    }
                },
                {
                    data: 'isApproved',
                    render: function (data, type, row) {
                        return data ? `<a href="/admin/approve/${row.id}" class="btn btn-outline-danger">Approved</a>`
                            : `<a href="/admin/approve/${row.id}" class="btn btn-outline-success">Approve</a>`;
                    }
                },
                {
                    "mData": "id",
                    "mRender": htmlContent
                }
            ]
        });
    }
}