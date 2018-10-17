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
            @if ($isAdmin || ($companyName->name == 'AST' && isset($viewing)))
            <h5>Viewing <span id="respond-to" style="text-transform: capitalize;">{{ $viewing }}</span></h5>
            @endif
          </div>
        </div>
      </div>
      
	  <!-- current statuses, removed for now -->

    <!-- action logs -->
    <div id="actionLog">
      <action-log-component></action-log-component>
    </div>
      
 	  <div class="row">
        &nbsp;
      </div>

      <!-- Product Documents -->

      <div class="container">

        @if (session('success') !== null)
          @include('partials.success')
        @endif
        
	  <h1 class="h2"><a name="product-documents">Product Documents</a></h1>
        <div class="row">
          <div class="col-md-5">
            <ul id="products" class="list-group">
              @foreach($product_documents as $product_document)
                <li class="list-group-item"><a href="{{ asset('storage') }}/files/{{ strtolower(str_replace(' ', '_', $viewing)) }}/{{ $product_document->document_name }}" target="_blank">{{ $product_document->document_name }}</a> <span id="product-document-{{ $product_document->id }}" class="removeProductDocument" alt="remove" title="remove" data-feather="x"></span></li>
              @endforeach
            </ul>
          </div>
        </div>
		
		@if(count($errors) > 0)
          @include('partials.errors')
        @endif

        <div class="row">
          &nbsp;
        </div>

        @include('partials.upload-files')

      </div>

      <div class="row">
        &nbsp;
      </div>

      <div class="row">
        &nbsp;
      </div>

      @include('partials.test-data')

      <div class="row">
        &nbsp;
      </div>

      <div class="row">
        &nbsp;
      </div>

      @endsection
