<!-- Add New Product -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="padding: 16px;">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Product</h5>
            </div>
            <div class="modal-body">
                <form id="localProductForm">
                    <input type="hidden" name="allowed_discount" id="allowed_discount" value="0" />
                    <input type="hidden" name="product_type" value="custom" />
                    <input type="hidden" name="status" value="active" />
                    <div class="row gx-3">
                        <div class="col-12">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Product Details</span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" id="titleInput" name="title" />
                                <div class="invalid-feedback">Please enter a title.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Division<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="division_id" id="divisionInput">
                                    <option value="">--</option>
                                    @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please enter a Division.</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Local Supplier<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="brand_id" id="brandInput">
                                    <option value="">--</option>
                                    @foreach ($localSuppliers as $k => $sup)
                                    <option value=" {{ $sup->id }}">
                                        {{ $sup->brand }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please enter a brand.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Model No</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="model_no">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Part Number</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="part_number" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Description </label>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="description" rows="2" cols="1"></textarea>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Product Category<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="product_category" id="productcategoryInput">
                                    <option value="">-Select-</option>
                                    <option value="products">Products</option>
                                    <option value="accessories">Accessories</option>
                                    <option value="consumables">Consumables</option>
                                    <option value="spares">Spares</option>
                                    <option value="services">Services</option>
                                </select>
                                <div class="invalid-feedback">Please select a product category.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Manager<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-select" name="manager_id" id="managerInput">
                                    <option value="" selected="">--</option>
                                    @foreach ($managers as $key => $row)
                                    <option value="{{ $row->id }}">
                                        {{ $row->user->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select product manager.</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label class="form-label">Image</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="file" name="image" />
                            </div>
                        </div>
                    </div>



                    <div class="row gx-3 mt-2 mb-2">
                        <div class="col-12">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Selling Price Details</span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Payment Term<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="product_payment_term">
                                    @foreach($terms as $paymentTerm)
                                    <option value="{{ $paymentTerm->short_code }}">{{ $paymentTerm->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please enter a product payment term.</div>

                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Currency<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="currency" id="currencyInput" readonly>
                                    <option value="">-Select Currency-</option>
                                    @foreach($currencies as $currency)
                                    <option value="{{ $currency->code }}" {{ $currency->code == 'aed'? "selected": "" }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please enter a product currency.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Selling Price<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="selling_price_new" id="sellingPrice" />
                                <div class="invalid-feedback">Please enter a price.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">MOSP<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" />
                                <div class="invalid-feedback">Please enter a MOSP.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Margin Price<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="margin_price" id="marginPrice">
                                <div class="invalid-feedback">Please enter a margin price.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="date" id="durationSelect">
                                    <option value="1" selected>1 Month</option>
                                    <option value="3">3 Month</option>
                                    <option value="6">6 Month</option>
                                    <option value="9">9 Month</option>
                                    <option value="12">12 Month</option>
                                </select>
                                <div class="invalid-feedback">Please select an date.</div>
                            </div>
                            <div class="form-group small" id="dateRangeGroup" style="display: none;">
                                <label class="form-label">Dates:</label>
                                <span id="dateRange"></span>
                                <input type="hidden" name="start_date" id="startDateInput" />
                                <input type="hidden" name="end_date" id="endDateInput" />
                            </div>
                        </div>
                    </div>


                    <div class="row gx-3 mt-2 mb-2">
                        <div class="col-12">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Buying Price Details</span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Buying Currency<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="buying_currency" id="supplier_buying_currency" readonly required>
                                    <option value="">-Select Currency-</option>
                                    @foreach($currencies as $currency)
                                    <option value="{{ $currency->code  }}" {{ $currency->code == 'aed'? "selected": ""  }}>{{ $currency->name }}</option>
                                    @endforeach
                                </select>

                                <div class="invalid-feedback">Please select a currency.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="gross_price" id="supplier_gross_price" required />

                                <div class="invalid-feedback">Please enter a gross price.</div>
                            </div>
                        </div>
                        <div class="col-md-4" id="purchase_discount_percent">
                            <div class="form-group">
                                <label class="form-label">Purchase Discount(%)</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="number" name="discount" id="supplier_purchase_discount">
                                <div class="invalid-feedback">Please enter a purchase discount.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" id="purchase_discount_price">
                            <div class="form-group">
                                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="discount_amount" id="supplier_purchase_discount_amount" required />
                                <div class="invalid-feedback">Please enter a purchase discount price.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="buying_price" id="supplier_buying_price" required />
                                <div class="invalid-feedback">Please enter a buying price.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="purchase_price_duration" id="supplier_purchasedurationSelect">
                                    <option value="">Select</option>
                                    <option value="1" selected>1 Month</option>
                                    <option value="3">3 Month</option>
                                    <option value="6">6 Month</option>
                                    <option value="9">9 Month</option>
                                    <option value="12">12 Month</option>
                                </select>
                                <div class="invalid-feedback">Please choose a date.</div>
                            </div>

                            <div class="form-group small" id="supplier_purchasedateRangeGroup" style="display: none;">
                                <label class="form-label">Dates:</label>
                                <span id="supplier_purchasedateRange"></span>
                                <input type="hidden" name="validity_from" id="supplier_validity_from" />
                                <input type="hidden" name="validity_to" id="supplier_validity_to" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveLocalProduct">Save</button>
                    </div>
                </form>
            </div>


        </div>

    </div>

</div>
<!-- Add New Product -->

<script>
    function createNewLocalProduct(isValid) {
        let asset_url = `{{ env('APP_URL') }}`;
        if (isValid) {
            let formData = new FormData($('#localProductForm')[0]);


            formData.append('title', $('input[name=title]').val());
            formData.append('division_id', $('select[name=division_id]').val());
            formData.append('brand_id', $('select[name=brand_id]').val());
            formData.append('model_no', $('input[name=model_no]').val());
            formData.append('description', $('textarea[name=description]').val());
            formData.append('product_type', $('input[name=product_type]').val());
            formData.append('payment_term', $('select[name=product_payment_term]').val());
            formData.append('currency', $('select[name=currency]').val());
            //  formData.append('currency_rate', $('input[name=currency_rate]').val());
            formData.append('manager_id', $('select[name=manager_id]').val());
            formData.append('selling_price', $('input[name=selling_price_new]').val());
            formData.append('margin_percentage', $('input[name=margin_percentage]').val());
            formData.append('margin_price', $('input[name=margin_price]').val());
            formData.append('freeze_discount', $('input[name=freeze_discount]').is(':checked') ? 1 : 0);
            formData.append('image', $('input[name=image]')[0].files[0]);
            formData.append('start_date', $('input[name=start_date]').val());
            formData.append('end_date', $('input[name=end_date]').val());
            formData.append('product_category', $('select[name=product_category]').val());
            formData.append('status', $('input[name=status]').val());
            formData.append('allowed_discount', $('input[name=allowed_discount]').val());
            formData.append('part_number', $('input[name=part_number]').val());

            formData.append('buying_currency', $('#supplier_buying_currency').val());
            formData.append('gross_price', $('#supplier_gross_price').val());
            formData.append('discount', $('#supplier_purchase_discount').val());
            formData.append('discount_amount', $('#supplier_purchase_discount_amount').val());
            formData.append('buying_price', $('#supplier_buying_price').val());
            formData.append('validity_from', $('#supplier_validity_from').val());
            formData.append('validity_to', $('#supplier_validity_to').val());

            $.ajax({
                url: "{{ route('products.ajaxsave') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.data) {
                        let newProductData = response.data;

                        let newRow = '',
                            title = '',
                            brandId = 0,
                            unitprice = 0,
                            mosp = 0,
                            subtotal = 0,
                            discount = 0,
                            qty = 1,
                            total = 0,
                            newmargin = 0;

                        let selProductId = newProductData.id;

                        /****** Selling Price *****/
                        brandId = newProductData.brand_id;

                        title = newProductData.title;
                        if (newProductData.modelno) title += ' / ' + newProductData.modelno + '';
                        if (newProductData.part_number) title += ' / ' + newProductData.part_number + '';
                        if (newProductData.product_type) title += ' ( ' + (newProductData.product_type).toUpperCase() + ') ';

                        // this is exclusively for Local supplier products - AED
                        if (newProductData.currency == 'aed') {
                            unitprice = newProductData.selling_price;
                        }
                        unitprice = parseFloat(unitprice).toFixed(2);
                        // mosp = newProductData.margin_percent;

                        subtotal = unitprice * qty; // unitprice * quantity (default)
                        subtotal = parseFloat(subtotal).toFixed(2);

                        total = subtotal; //- (subtotal * discount / 100);
                        total = parseFloat(total).toFixed(2);

                        // newmargin = subtotal * (mosp / 100); //((mosp - discount) / 100);
                        // newmargin = parseFloat(newmargin).toFixed(2);

                        supplierData = newProductData.supplier;

                        let rowExists = $("#row-p" + selProductId);
                        if (rowExists) {
                            rowExists.remove();
                        }

                        /****** Buying Price *****/
                        let rowCnt = $("#itemcustomFields").find("tbody > tr").length;
                        $.ajax({
                            url: '/buyingprice',
                            method: 'GET',
                            data: {
                                product_id: selProductId
                            },
                            success: function(resp) {
                                let buying_unit_price = resp.data;

                                if (buying_unit_price) {
                                    buyingPrice = buying_unit_price * qty;
                                }
                                let bpHtml = "";
                                if (buyingPrice > 0) {
                                    bpHtml += `<input type="hidden" class="form-control" name="item[${rowCnt}][buying_currency]" value="aed" />
                                    <input type="hidden" class="form-control" name="item[${rowCnt}][buying_unit_price]" value="${buying_unit_price}" />
                                    <input type="text" class="form-control" name="item[${rowCnt}][buying_price]" value="${buyingPrice}" readonly />`;
                                }

                                newRow += `<tr valign="top" id="row-p${selProductId}" data-index="${rowCnt}">
                                    <td width="15%">
                                        <b>${newProductData.supplier.brand}</b><br />
                                        <input type="hidden" name="item[${rowCnt}][product_id]" value="${selProductId}" />
                                        <textarea class="form-control" name="item[${rowCnt}][item_name]" placeholder="Item">${title}</textarea>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="item[${rowCnt}][partno]" placeholder="Part No" value="${newProductData.part_number}" />
                                    </td>
                                    <td>
                                        <input type="hidden" class="form-control" name="item[${rowCnt}][unit_price]" value="${unitprice}" />
                                        <input type="number" class="form-control quantity" name="item[${rowCnt}][quantity]" value="${qty}" placeholder="Quantity" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="item[${rowCnt}][yes_number]" placeholder="YesNo." />
                                    </td>
                                    <td width="12%">
                                        <label class="text-primary small">Selling Price (AED)</label>
                                        <input type="text" class="form-control" name="item[${rowCnt}][total_amount]" value="${total}" placeholder="Total Amount" readonly/>
                                        <div class="purchase mt-3">                                
                                            <label class="text-primary small">Buying Price (AED)</label>
                                            ${bpHtml}
                                            <a href="javascript:void(0);" class="b-price-add btn btn-primary btn-sm mt-1" data-pid="${selProductId}" data-bs-toggle="modal" data-bs-target="#addpurchase"> <i class="fas fa-plus"></i> ADD</a>
                                        </div>
                                    </td>
                                    <td width="8%">
                                        <input type="text" class="form-control datepick" name="item[${rowCnt}][expected_delivery]" placeholder="Expected Delivery" />
                                    </td>
                                    <td width="10%">
                                        <select class="form-control" name="item[${rowCnt}][status]" id="status">
                                            <option value="0">Not Delivered</option>
                                            <option value="1">Delivered</option>
                                        </select>
                                    </td>
                                    <td width="15%">
                                        <textarea rows="2" id="remarks" name="item[${rowCnt}][remarks]" placeholder="Remarks" class="form-control"></textarea>
                                    </td>
                                    <td>
                                    <a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id="drow-${selProductId}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>`;

                                $("#itemcustomFields").find('tbody').append(newRow);

                                // add local supplier 
                                addLocalSupplier(supplierData);

                                // calculateMarginAmount(total); // Projected Margin price calculate
                            },
                            error: function(error) {
                                console.error('Error fetching buying price:', error);
                            }
                        });


                        // reset modal
                        let modal = $("#myModal");
                        modal.find('input[type=text], input[type=number], textarea').val('');
                        modal.find('select').val('').trigger('change');

                        modal.modal('hide');

                    }

                },
                error: function(error) {
                    console.error('Error fetching product models:', error);
                }
            });
        }
    }

    function setPurchaseDateRange1(selectedValue) {

        let currentDate = new Date();
        let validity_from = document.getElementById('supplier_validity_from');
        let validity_to = document.getElementById('supplier_validity_to');
        let dateRangeGroup = document.getElementById('supplier_purchasedateRangeGroup');

        if (selectedValue !== "") {
            let startDate = new Date(currentDate);
            let endDate = new Date(currentDate);
            endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));

            let startDateFormatted = startDate.toISOString().split('T')[0];
            let endDateFormatted = endDate.toISOString().split('T')[0];

            validity_from.value = startDateFormatted;
            validity_to.value = endDateFormatted;

            let dateRange = `${startDateFormatted} to ${endDateFormatted}`;

            document.getElementById('supplier_purchasedateRange').innerText = dateRange;
            dateRangeGroup.style.display = 'block';

        } else {
            validity_from.value = '';
            validity_to.value = '';
            document.getElementById('supplier_purchasedateRange').innerText = "";
            dateRangeGroup.style.display = 'none';
        }
    }

    function updateMarginPrice() {
        var sellingPriceStr = $('#sellingPrice').val(); // Get the value using jQuery
        var marginPercentageStr = $('#marginPercentage').val(); // Get the value using jQuery

        var basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
        var marginPercentage = parseFloat(marginPercentageStr.replace(/,/g, ''));

        var marginPriceInput = $('#marginPrice');

        if (!isNaN(basePrice) && !isNaN(marginPercentage)) {

            var calculatedMarginPrice = basePrice * (marginPercentage / 100);
            var formattedMarginPrice = numberWithCommas(calculatedMarginPrice.toFixed(2));

            marginPriceInput.val(formattedMarginPrice);
        }
    }

    function updateMarginPercentage() {
        var sellingPriceStr = $('#sellingPrice').val();
        var marginPriceStr = $('#marginPrice').val();

        if (sellingPriceStr.trim() === '') {
            return;
        }

        var basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
        var marginPrice = parseFloat(marginPriceStr.replace(/,/g, ''));

        console.log('Base Price:', basePrice);
        console.log('Margin Price:', marginPrice);

        var marginPercentageInput = $('#marginPercentage');

        if (!isNaN(basePrice) && !isNaN(marginPrice) && basePrice !== 0) {
            if (basePrice <= marginPrice) {
                alert('Selling price should be greater than margin price.');
                marginPercentageInput.val('');
            } else {
                var calculatedMarginPercentage = (marginPrice / basePrice) * 100;
                var formattedMarginPercentage = calculatedMarginPercentage.toFixed(2);
                marginPercentageInput.val(formattedMarginPercentage);
            }
        } else {
            marginPercentageInput.val('');
        }
    }

    function updateBuyingPrice1() {
        let gross_price = $('#supplier_gross_price').val(); // Get the value using jQuery
        let purchase_discount = $('#supplier_purchase_discount').val(); // Get the value using jQuery

        let basePrice = parseFloat(gross_price.replace(/,/g, ''));
        let dPercentage = parseFloat(purchase_discount.replace(/,/g, ''));

        let buyingPriceInput = $('#supplier_buying_price');

        if (!isNaN(basePrice) && !isNaN(dPercentage)) {

            let calculatedDPrice = basePrice * (dPercentage / 100);
            $('#supplier_purchase_discount_amount').val(calculatedDPrice);
            calculatedDPrice = basePrice - calculatedDPrice;
            let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

            buyingPriceInput.val(formattedMarginPrice);
        } else if (!isNaN(basePrice)) {
            let formattedMarginPrice = numberWithCommas(basePrice.toFixed(2));

            buyingPriceInput.val(formattedMarginPrice);
        }
    }

    function updateBuyingPriceWithAmount1() {
        let gross_price = $('#supplier_gross_price').val(); // Get the value using jQuery
        let purchase_discount = $('#supplier_purchase_discount_amount').val(); // Get the value using jQuery

        let basePrice = parseFloat(gross_price.replace(/,/g, ''));
        let dAmount = parseFloat(purchase_discount.replace(/,/g, ''));

        let buyingPriceInput = $('#supplier_buying_price');

        if (!isNaN(basePrice) && !isNaN(dAmount)) {

            let calculatedDPrice = basePrice - dAmount;
            let dpercent = (dAmount / basePrice) * 100;
            dpercent = parseFloat(dpercent).toFixed(2);
            $('#supplier_purchase_discount').val(dpercent);

            let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

            buyingPriceInput.val(formattedMarginPrice);
        }
    }

    function setPriceValidity(selectedValue) {
        var currentDate = new Date();
        var startDateInput = document.getElementById('startDateInput');
        var endDateInput = document.getElementById('endDateInput');
        if (selectedValue !== "") {
            var startDate = new Date(currentDate);
            var endDate = new Date(currentDate);
            endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));
            var startDateFormatted = startDate.toISOString().split('T')[0];
            var endDateFormatted = endDate.toISOString().split('T')[0];
            startDateInput.value = startDateFormatted;
            endDateInput.value = endDateFormatted;

            var dateRange = startDateFormatted + ' to ' + endDateFormatted;
            document.getElementById('dateRange').innerText = dateRange;
            dateRangeGroup.style.display = 'block';
        } else {
            startDateInput.value = '';
            endDateInput.value = '';
            document.getElementById('dateRange').innerText = "";
            dateRangeGroup.style.display = 'none';
        }
    }


    $(document).ready(function() {
        $("#add-new-product").on('click', function() {
            $('#myModal').appendTo("body").modal('show');
        });
        document.getElementById('durationSelect').addEventListener('change', function() {
            var selectedValue = this.value;
            setPriceValidity(selectedValue);
        });
        setPriceValidity(1);
        // FOR ADD NEW CUSTOM PRODUCT
        document.getElementById('sellingPrice').addEventListener('input', updateMarginPrice);
        document.getElementById('marginPercentage').addEventListener('input', updateMarginPrice);
        $('#sellingPrice, #marginPrice').on('input', function() {
            updateMarginPercentage();
        });

        $('#saveLocalProduct').on('click', function(e) {
            e.preventDefault();
            var isValid = true;

            // Remove the is-invalid class and hide the invalid feedback for all elements
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').hide();

            // Define the required fields by their IDs
            var requiredFields = [
                '#titleInput',
                '#divisionInput',
                '#brandInput',
                '#sellingPrice',
                '#marginPercentage',
                '#marginPrice',
                '#productcategoryInput',
                '#durationSelect',
                '#currencyInput',
                '#managerInput',
                '#supplier_buying_currency',
                '#supplier_gross_price',
                '#supplier_purchase_discount',
                '#supplier_purchase_discount_amount',
                '#supplier_buying_price'
            ];


            // Validate each field based on its value
            requiredFields.forEach(function(field) {
                var value = $(field).val().trim();

                // Check if the field value is empty
                if (value === '') {
                    $(field).addClass('is-invalid').next('.invalid-feedback').show();
                    isValid = false;
                }
            });

            // If all fields pass validation, submit the form
            if (isValid) {
                createNewLocalProduct(isValid);

            }
        });

        $("#supplier_purchasedurationSelect").on("change", function(e) {
            let selectedValue = $(this).val();
            setPurchaseDateRange1(selectedValue);
        });
        // default purchase date range
        setPurchaseDateRange1(1);

        $('#supplier_purchase_discount').on('input', function() {
            var gross_price = $('#supplier_gross_price').val();
            if (gross_price.trim() === '') {
                alert('Please enter the gross price first.');
                $(this).val(''); // Clear the margin price input
            }
        });

        $('#supplier_purchase_discount_amount').on('input', function() {
            var gross_price = $('#supplier_gross_price').val();
            if (gross_price.trim() === '') {
                alert('Please enter the gross price first.');
                $(this).val(''); // Clear the margin price input
            }
        });

        document.getElementById('supplier_gross_price').addEventListener('input', updateBuyingPrice1);
        document.getElementById('supplier_purchase_discount').addEventListener('input', updateBuyingPrice1);
        document.getElementById('supplier_purchase_discount_amount').addEventListener('input', updateBuyingPriceWithAmount1);

        $('#supplier_gross_price, #supplier_purchase_discount').on('input', function() {
            updateBuyingPrice1();
        });


    });
</script>