<div class="logo"><a href="{{ route('admin.dashboard') }}" class="simple-text logo-normal"><img src="{{ asset('images') }}/logo.png" width="160"></a></div>
<div class="sidebar-wrapper">
    <ul class="nav">
        <li class="nav-item @routeis('admin.dashboard') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="material-icons">dashboard</i>
                <p>Dashboard</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.user*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.user.list') }}">
                <i class="material-icons">supervisor_account</i>
                <p>Users</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.job*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.job.list') }}">
                <i class="material-icons">work</i>
                <p>Jobs</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.work_category*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.work_category.list') }}">
                <i class="material-icons">add_task</i>
                <p>Work Categories</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.category*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.category.list') }}">
                <i class="material-icons">category</i>
                <p>Categories</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.sub_category*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.sub_category.list') }}">
                <i class="material-icons">subject</i>
                <p>Sub Categories</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.client*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.client.list') }}">
                <i class="material-icons">groups</i>
                <p>Clients</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.source_job*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.source_job.list') }}">
                <i class="material-icons">source</i>
                <p>Job Sources</p>
            </a>
        </li>

        <li class="nav-item @routeis('admin.status_job*') active @endrouteis">
            <a class="nav-link" href="{{ route('admin.status_job.list') }}">
                <i class="material-icons">military_tech</i>
                <p>Job Status</p>
            </a>
        </li>

    </ul>
</div>
