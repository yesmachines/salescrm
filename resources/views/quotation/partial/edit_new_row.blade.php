<div class="row gx-3">
    <div class="col-md-9">
        <label class="form-label">Enter Product / Model No / Part No <span class="text-danger">*</span></label>
        <div class="form-group">
            @if(!empty($brands))
            <select class="form-control select2" name="product_item_search" id="product_item_search">
                <option value="">-Select Products-</option>
                @foreach($brands as $brand => $products)
                <optgroup label="{{$brand}}">
                    @foreach ($products as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->title }} {{$item->modelno? '/'.$item->modelno: ''}} {{$item->part_number? '/'.$item->part_number: ''}}
                    </option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
            @else
            <select class="form-control select2" name="product_item_search" id="product_item_search" disabled>
            </select>
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">&nbsp;</label>
        <div class="form-group text-center">

            <a href="javascript:void(0);" id="add-new-product" class="mt-3 {{empty($brands)? 'd-none': ''}}" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"></i> Create New Product</a>
        </div>
    </div>
</div>
<div class="row gx-3">
    <div class="col-md-12">
        <table class="table" id="product_item_tbl">
            <thead class="thead-light">
                <tr>
                    <th width="25%">Product</th>
                    <th>
                        <p class="currency-label">UnitPrice({{$quotation->preferred_currency}})</p>
                    </th>
                    <th>Quantity</th>
                    <th>
                        <p class="currency-label">Line Total({{$quotation->preferred_currency}})</p>
                    </th>
                    <th>Discount(%)</th>
                    <th>
                        <p class="currency-label">Total After Discount({{$quotation->preferred_currency}})</p>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(!$quotationItems->isEmpty())
                @foreach($quotationItems as $item)
                <tr id="irow-{{$item->item_id}}">
                    <td><textarea class="form-control" name="item_description[]" rows="2">{{$item->description}}</textarea></td>
                    <td><input type="text" class="form-control unit-price" name="unit_price[]" value="{{$item->unit_price}}" readonly />
                        <p class="text-danger">MOSP: <span class="mosp-label">{{$item->product->margin_percent}}</span>%</p>
                    </td>
                    <td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="{{$item->quantity}}" /></td>
                    <td><input type="text" class="form-control subtotal" name="subtotal[]" value="{{$item->subtotal}}" readonly /></td>
                    <td><input type="number" class="form-control discount" name="discount[]" min="0" value="{{$item->discount}}" /></td>
                    <td><input type="text" class="form-control total-price" name="total_after_discount[]" value="{{$item->total_after_discount}}" readonly />
                        <p class="text-danger">New Margin: <span class="new-margin-label">{{$item->margin_price}}</span></p>
                    </td>
                    <td><input type="hidden" name="item_id[]" value="{{$item->item_id}}" />
                        <input type="hidden" name="brand_id[]" value="{{$item->brand_id}}" />
                        <input type="hidden" name="product_selling_price[]" value="{{$item->product->selling_price}}" />
                        <input type="hidden" class="margin-percent" name="margin_percent[]" value="{{$item->product->margin_percent}}" />
                        <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="{{$item->product->allowed_discount}}" />
                        <input type="hidden" name="product_margin[]" value="{{$item->product->margin_price}}" />
                        <input type="hidden" name="product_currency[]" value="{{$item->product->currency}}" />
                        <input type="hidden" name="margin_amount_row[]" value="{{$item->margin_price}}" class="margin-amount-row">
                        <a href="javascript:void(0);" data-id="drow-{{$item->item_id}}" class="del-item" onclick="removeQuotationRow(this)"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>