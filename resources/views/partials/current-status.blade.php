      <div id="quickLook" class="container">
        <h4><a name="dashboard">Current Status Quicklook</a></h4>
        <div class="row">
          <div class="col-md-3">
            Current Status:
            <form id="status" name="current_status">
              <ul>
                @foreach ($currentStatuses as $cs)

                <li>{{ $cs->current_status }}</li>

                @endforeach
              </ul>
            </form>
            <p style="text-align: center;">
              <span class="edit" data-feather="plus"></span>
            </p>
          </div>
        </div>
      </div>