<nav class="col-md-2 d-none d-md-block bg-light sidebar">
  <div class="sidebar-sticky">
    <ul id="left-nav" class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="/home"><img src="{{ asset('images/astlogo_reduced.png') }}" width="150" height="32"></a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="/home">
          <span data-feather="home"></span>
          Home <span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/home#product-documents">
          <span data-feather="file"></span>
          Product Documents
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/home#test-data">
          <span data-feather="file"></span>
          Test Data
        </a>
      </li>
      <li class="nav-item">

        <a class="nav-link" href="/archived{{ ($isAdmin && isset($viewing)) ? '/'. $viewing : '' }}">
          <span data-feather="archive"></span>
          Archived
        </a>
      </li>
      @if( $companyName->name == 'AST' && !$isAdmin)
      <li>
        <a class="nav-link" href="/user-directory"><span data-feather="list"></span>User Directory</a>
      </li>
      @endif
      @if( $isAdmin )
      <li>
        <a class="nav-link" href="/deleted"><span data-feather="trash-2"></span>Deleted Action Items</a>
      </li>
      <li>
        <a class="nav-link" href="/add-user"><span data-feather="user-plus"></span>Add User</a>
      </li>
      <li>
        <a class="nav-link" href="/manage-users"><span data-feather="user-plus"></span>Manage Users</a>
      </li>
      <li>
        <a class="nav-link" href="/add-company"><span data-feather="plus-circle"></span>Add Company</a>
      </li>
      <li>
        <a class="nav-link" href="/manage-companies"><span data-feather="edit"></span>Manage Companies</a>
      </li>
      @endif
    </ul>

  </div>
</nav>