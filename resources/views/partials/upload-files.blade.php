        <!-- Upload Files -->

        <div class="row">
          <div class="col">
            <h1 class="h4"><a name="upload-files">Upload Product Files</a></h1>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <form id="product_upload_form" action="/upload-product-file" method="POST" enctype="multipart/form-data">
              <div class="custom-file">
                <input type="file" name="file" data-target="#chooseFile" class="custom-file-input" id="customFile" data-toggle="custom-file">
                <label id="chooseFile" class="custom-file-label" for="customFile" data-content="Upload product file...">Choose file</label>
              </div>
              <input type="hidden" id="viewing-product-file" name="viewing" value="self">
              <div class="form-group" style="margin-top: 15px;">
                <button type="submit" id="product-upload" name="product" class="btn btn-primary">Upload</button>
              </div>
              {{ csrf_field() }}
            </form>
          </div>
        </div>