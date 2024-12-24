<h5>Special Service Requirement</h5>
<p>Please refer the below special service requirements against custom orders</p>
<br>
<div class="row">
    <div class="col-12">
        <div class="client_error_msg"></div>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
{!! Form::open(array('id' => 'add_service_requirement', 'name' => 'add_service_requirement')) !!}
<input type="hidden" name="order_id" id="order_id_step3" value="{{$order->id}}" />

<div class="row mb-2">
    <div class="col-4">
        <h6>Installations & Training</h6>
    </div>
    <div class="col-6">
        <input type="text" id="installation_training" name="installation_training" class="form-control" value="{{ $deliveryPoints->installation_training ?? '' }}" />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Service Experts</h6>
    </div>
    <div class="col-6">
        <input type="text" id="service_expert" name="service_expert" class="form-control" value="{{ $deliveryPoints->service_expert ?? '' }}" />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Estimated Installation (DAYS)</h6>
    </div>
    <div class="col-6">
        <input type="text" id="estimated_installation" name="estimated_installation" class="form-control" value="{{ $deliveryPoints->estimated_installation ?? '' }}" />
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>Site Readiness Requirements </h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="site_readiness" row="3">{{$serviceRequest->site_readiness}}</textarea>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Training Required For YM Engineers </h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="training_requirement" row="3">{{$serviceRequest->training_requirement}}</textarea>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>List Of Consumables</h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="consumables" row="3">{{$serviceRequest->consumables}}</textarea>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>Offered Warranty Period</h6>
    </div>
    <div class="col-6">
        <input type="text" id="warranty_period" name="warranty_period" class="form-control" value="{{$serviceRequest->warranty_period}}" />
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>Any Special Offers To Clients</h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="special_offers" row="3">{{$serviceRequest->special_offers}}</textarea>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>Any Special Documentations Required</h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="documents_required" row="3">{{$serviceRequest->documents_required}}</textarea>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>Objective Of The Machine</h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="machine_objective" row="3">{{$serviceRequest->machine_objective}}</textarea>
    </div>
    <div class="col-2"></div>
</div>


<div class="row mb-2">
    <div class="col-4">
        <h6>FAT Test</h6>
    </div>
    <div class="col-6">
        <label><input type="radio" name="fat_test" value="1" {{($serviceRequest->fat_test == 1)? "checked": ""}}> YES</label>&nbsp; &nbsp;
        <label><input type="radio" name="fat_test" value="0" {{($serviceRequest->fat_test == 0)? "checked": "checked"}}> NO</label>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>FAT Expectations</h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="fat_expectation" row="3">{{$serviceRequest->fat_expectation}}</textarea>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-4">
        <h6>SAT Objectives</h6>
    </div>
    <div class="col-6">
        <textarea class="form-control" name="sat_objective" row="3">{{$serviceRequest->sat_objective}}</textarea>
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-3"></div>
    <div class="col-6">
        <button type="button" class="btn btn-default prev-step m-2"><i class="fa fa-chevron-left"></i> Back</button>
        <!-- <button type="submit" id="order_client_draft" class="btn btn-secondary m-2" value="save-step2-draft">Draft & Continue</button> -->
        <button type="submit" id="order_service_details_button" class="btn btn-success m-2" value="save-step3">Save & Continue</button>

        <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="col-3"></div>
</div>

{!! Form::close() !!}