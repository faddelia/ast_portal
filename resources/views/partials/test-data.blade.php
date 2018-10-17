      <!-- Test Data -->

      <div class="container">
        <h1 class="h2"><a name="test-data">Test Data</a></h1>

        <div class="row">
          <div class="col-md-5">
            <ul id="test-data" class="list-group">
              @foreach($test_data as $data)
              <li class="list-group-item"><a href="{{ asset('/storage') }}/files/{{ strtolower(str_replace(' ', '_', $viewing)) }}/{{ $data->document_name }}" target="_blank">{{ $data->document_name }}</a> <span id="test-document-{{ $data->id }}" class="removeTestDocument" alt="remove" title="remove" data-feather="x"></span></li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="row">
          &nbsp;
        </div>

        <!-- Upload Files -->

        <div class="row">
          <div class="col">
            <h1 class="h4"><a name="upload-files">Upload Test Data File</a></h1>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <form id="data_upload_form" name="upload-data" action="/upload-data-file" method="POST" enctype="multipart/form-data">
              <div class="custom-file">
                <input type="file" name="data" class="custom-file-input" id="customDataFile" data-toggle="custom-data-file" data-target="#data-file">
                <label id="data-file" class="custom-file-label" for="customDataFile" data-content="Upload product file...">Choose file</label>
              </div>
              <input type="hidden" id="viewing-data-file" name="viewing" value="self">
              <div class="form-group" style="margin-top: 15px;">
                <button type="submit" name="data" id="data-file-upload" class="btn btn-primary">Upload</button>
              </div>
              {{ csrf_field() }}
            </form>
          </div>
        </div>

      </div>
