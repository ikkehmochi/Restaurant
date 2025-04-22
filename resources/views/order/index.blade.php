@extends('app.master')
@section('content')

<!-- /.card-header -->
<div class="card-body p-0">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 10px">#</th>
                <th>User</th>
                <th>Table</th>
                <th>Status</th>
                <th>Total Price</th>
                <th>Payment</th>
                <th>Action</th>
                <th>Label</th>
            </tr>
        </thead>
        <tbody>
            <tr class="align-middle">
                <td>1.</td>
                <td>Update software</td>
                <td>
                    <div class="progress progress-xs">
                        <div
                            class="progress-bar progress-bar-danger"
                            style="width: 55%"></div>
                    </div>
                </td>
                <td><span class="badge text-bg-danger">55%</span></td>
            </tr>
            <tr class="align-middle">
                <td>2.</td>
                <td>Clean database</td>
                <td>
                    <div class="progress progress-xs">
                        <div class="progress-bar text-bg-warning" style="width: 70%"></div>
                    </div>
                </td>
                <td><span class="badge text-bg-warning">70%</span></td>
            </tr>
            <tr class="align-middle">
                <td>3.</td>
                <td>Cron job running</td>
                <td>
                    <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar text-bg-primary" style="width: 30%"></div>
                    </div>
                </td>
                <td><span class="badge text-bg-primary">30%</span></td>
            </tr>
            <tr class="align-middle">
                <td>4.</td>
                <td>Fix and squish bugs</td>
                <td>
                    <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar text-bg-success" style="width: 90%"></div>
                    </div>
                </td>
                <td><span class="badge text-bg-success">90%</span></td>
            </tr>
        </tbody>
    </table>
</div>
<!-- /.card-body -->

@endsection