      @extends('layouts.master')

      @section('content')
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <h1 id="company-name" class="h2"><a name="dashboard">Client Portal - <span>{{ $companyName->name }}</span></a></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
              <!--
              <button class="btn btn-sm btn-outline-secondary">Share</button>
              <button class="btn btn-sm btn-outline-secondary">Export</button>
            -->
            @if ($isAdmin)
            <h5>Viewing <span id="respond-to" style="text-transform: capitalize;">{{ $viewing }}</span></h5>
            @endif
          </div>
        </div>
      </div>

      <!-- current status, removed for now -->

      <div class="table-responsive">
        <table id="action-table" class="table table-striped table-sm">
          <thead>
            <tr>
              <th><a id="sortby-date" class="action-nav" href="{{ url()->current() }}?sortby=date&sortdirection=desc">Date</a></th>
              <th><a id="sortby-company" class="action-nav" href="{{ url()->current() }}?sortby=company&sortdirection=desc">Company</a></th>
              <th><a id="sortby-name" class="action-nav" href="{{ url()->current() }}?sortby=name&sortdirection=desc">Name</a></th>
              <th><a id="sortby-communication-type" class="action-nav" href="{{ url()->current() }}?sortby=communication-type&sortdirection=desc">Communication Type</a></th>
              <th><a id="sortby-contact" class="action-nav" href="{{ url()->current() }}?sortby=contact&sortdirection=desc">Contact</a></th>
              <th><a id="sortby-subject" class="action-nav" href="{{ url()->current() }}?sortby=subject&sortdirection=desc">Subject</a></th>
              <th><a id="sortby-action" class="action-nav" href="{{ url()->current() }}?sortby=action&sortdirection=desc">Comment/Action Item</a></th>
              <th>Restore</th>
              @if($isAdmin)
              <th><a id="sortby-assigned-to" class="action-nav" href="{{ url()->current() }}?sortby=date&sortdirection=desc">Assigned To</a></th>
              @endif
            </tr>
          </thead>
          <tbody>
            @foreach ($actionLog as $al)
            <tr id="action-log-{{ $al->id }}">
              <td>{{ $al->string_date }}</td>
              <td>{{ $al->company_name }}</td>
              <td>{{ $al->name }}</td>
              <td>{{ $al->communication_type }}</td>
              <td>{{ $al->contact }}</td>
              <td>{!! nl2br(e($al->status)) !!}</td>  
              <td>{!! nl2br(e($al->action_item)) !!}</td>
              <td><input type="checkbox" class="delete" name="delete-{{ $al->id }}" id="delete-{{ $al->id }}"></td>
              @if(Auth::user()->role == 'admin')
              <td id="record-{{ $al->id }}" class="assigned-to">{{ $al->assigned_to }}</td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @endsection