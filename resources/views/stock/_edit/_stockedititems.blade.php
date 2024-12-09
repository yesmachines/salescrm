      <div class="row m-2">
        <div class="col-9"> <label>Enter Product / Model No / Part No *</label>

          <select class="form-control select2" id="product_item_search">
            <option value="">-- Select Products --</option>
            @foreach($products as $prod)
            <option value="{{$prod->id}}">{{$prod->title}}/{{$prod->modelno}}/{{$prod->part_number}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">&nbsp;</label>
          <div class="form-group text-center">
            <a href="javascript:void(0);" id="add-new-product" class="mt-3" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"></i> Create New Product</a>
          </div>
        </div>
      </div>
      <div class="row ">
        <div class="col-12">
          <div class="separator"></div>
        </div>
      </div>
      <div class="row">
        <div class="col-xxl-12">
          <h6><b> Stock Items</b>
            <!-- <a href="javascript:void(0);" class="addIT btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a> -->
          </h6>
          </h6>
          <table class="table form-table" id="itemcustomFields">
            <thead>
              <tr>
                <th>Item Details *</th>
                <th>Part No</th>
                <th>Unit (AED)*</th>
                <th>Qty *</th>
                <th>YesNo *</th>
                <th>Dis (%)</th>
                <th>Amt (AED) *</th>
                <th>Expected<br />Delivery</th>
                <th>Status</th>
                <th>Item<br />Remarks</th>
                <th></th>
              </tr>
            </thead>
            <tbody>

              @foreach($stockItems as $index => $item)
              <tr valign="top" id="irow-{{ $item->item_id }}">
                <td width="15%">
                  <textarea class="form-control" name="item_name[]" placeholder="Item">{{ $item->item_name }}</textarea>
                </td>
                <td>
                  <input type="text" class="form-control" name="partno[]" placeholder="Part No" value="{{ $item->partno }}" />
                </td>
                <td>
                  @php
                  $unit_price = 0;
                  if($item->unit_price >0){
                  $unit_price = $item->unit_price;
                  }else{
                  if($item->discount <= 0){
                    $unit_price=($item->total_amount / $item->quantity);
                    }
                    }
                    @endphp
                    <input type="text" class="form-control" placeholder="Unit Price" name="unit_price[]" value="{{ $unit_price }}" readonly />

                </td>
                <td>
                  <input type="number" class="form-control quantity" name="quantity[]" value="{{ $item->quantity }}" placeholder="Quantity" />
                </td>
                <td>
                  <input type="text" class="form-control" name="yes_number[]" placeholder="YesNo." value="{{ $item->yes_number }}" />
                </td>
                <td><input type="text" class="form-control linediscount" name="discount[]" value="{{ $item->discount }}" placeholder="Discount %" /></td>
                <td>
                  <input type="number" class="form-control purchase_amount" name="total_amount[]" value="{{ $item->total_amount }}" step="any" placeholder="Total Amount" />
                  <a href="javascript:void(0);" class="b-price-add btn btn-primary btn-sm mt-1" data-pid="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#addpurchase"> <i class="fas fa-plus"></i> ADD</a>
                </td>
                <td width="8%">
                  <input type="text" class="form-control datepick" name="expected_delivery[]" placeholder="Expected Delivery" value="{{ $item->expected_delivery }}" />
                </td>
                <td width="10%">
                  <select class="form-control" name="status[]" id="status">
                    <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Not Delivered</option>
                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Delivered</option>
                  </select>
                </td>
                <td width="15%">
                  <textarea rows="2" id="remarks" name="item_remark[]" placeholder="Remarks" class="form-control">{{ $item->remarks }}</textarea>
                </td>
                <td>

                  <input type="hidden" name="item_id[]" value="{{ $item->item_id }}" />
                  <a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id="drow-{{ $item->item_id }}"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              @endforeach

            </tbody>
          </table>
        </div>
        <div class="row mb-2">
          <div class="col-4">
            <h6>Buying Price (AED) From Supplier *</h6>
          </div>
          <div class="col-6">
            <input type="text" class="form-control" id="total_buying_price" name="total_buying_price" value="{{ $stock->buying_price }}" readonly>
          </div>
          <div class="col-2"></div>
        </div>


      </div>