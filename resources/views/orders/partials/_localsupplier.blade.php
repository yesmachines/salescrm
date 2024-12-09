<div class="row ">
    <div class="col-12">
        <h5>Add Local Suppliers</h5>
    </div>
</div>
<div class="row m-2">
    <div class="col-3">
        <label>Select Local Supplier *</label>
        <select class="form-control supplier-dropdown select2" id="localsupplier">
            <option value="">--Select--</option>
            @foreach($localSuppliers as $sup)
            <option value="{{$sup->id}}">{{$sup->brand}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-6"> <label>Enter Product / Model No / Part No *</label>

        <select class="form-control select2" id="product_item_search" disabled>
            <option value="">-- Select Products --</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">&nbsp;</label>
        <div class="form-group text-center">
            <a href="javascript:void(0);" id="add-new-product" class="mt-3 d-none"> <i class="fas fa-plus"></i> Create New Product</a>
        </div>
    </div>
</div>
<div class="row ">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>


<script>
    var productData = [];

    function searchProducts() {
        let selSuppliers = $("#localsupplier").val();

        if (!selSuppliers) return false;

        let productDropdown = $("#product_item_search");
        productDropdown.html("");

        $.ajax({
            url: '/fetch-product-models',
            method: 'GET',
            data: {
                supplier_id: selSuppliers
            },
            success: function(resp) {
                let data = resp;
                productData = [];

                if (data) {
                    // for dropdown listing
                    productDropdown.append('<option value="">-Select Products-</option>');
                    for (let brand in data) {
                        let products = data[brand];
                        let optgroup = $('<optgroup label="' + brand + '">');

                        for (let pi in products) {
                            let item = products[pi];

                            let selopt = item.title;
                            if (item.modelno) selopt += ' / ' + item.modelno + '';
                            if (item.part_number) selopt += ' / ' + item.part_number + '';
                            if (item.product_type) selopt += ' ( ' + (item.product_type).toUpperCase() + ' )';

                            optgroup.append('<option value="' + item.id + '">' + selopt + '</option>');

                            productData[item.id] = item;
                        }

                        optgroup.append('</optgroup>');
                        productDropdown.append(optgroup);
                    }
                    productDropdown.val('').trigger('change');

                } else {
                    productDropdown.append('<option value="">No models available</option>');
                }
            },
            error: function(error) {
                console.error('Error fetching product models:', error);
            }
        });
    }

    function addLocalSupplier(supplier) {
        let cntSRow = $("#supplierFields").find('tr').length;
        let newSRow = '';

        let localSups = $("#supplierFields").find(".supplier-local").length;
        let notCreated = false;
        if (localSups > 0) {
            $("#supplierFields").find(".supplier-local").each(function(index, value) {
                if (this.value == supplier.id) {
                    notCreated = true;
                }
            });
        }
        if (!notCreated) {
            newSRow += `<tr valign="top" id="row-sp{{$sup->id}}" data-index="${cntSRow}">
                <td width="30%">
                    <input type="hidden" class="form-control" value="${supplier.country_id}" name="supplier[${cntSRow}][country_id]" />
                    <input type="text" readonly class="form-control" value="${supplier.brand}" name="supplier[${cntSRow}][supplier_name]" />
                    <input type="hidden" class="form-control supplier-local" value="${supplier.id}" name="supplier[${cntSRow}][supplier_id]" />
                </td>
                <td width="20%">
                    <input type="text" class="form-control" name="supplier[${cntSRow}][price_basis]" value="aed" readonly/>                    
                </td>
                <td width="20%">
                    <input type="text" class="form-control"  name="supplier[${cntSRow}][delivery_term]" value="Exworks" readonly/>   
                </td>
                <td width="30%">
                    <textarea rows="2" name="supplier[${cntSRow}][remarks]" placeholder="Supplier Remarks" class="form-control"></textarea>
                </td>
                <td>
                <a href="javascript:void(0);" class="remSup" title="DELETE ROW" data-id="srow-${supplier.id}"><i class="fa fa-trash"></i></a>
                </td>
            </tr>`;
        }
        $("#supplierFields").find('tbody').append(newSRow);

    }


    // function calculateMarginAmount(localExpense) {

    //     let margin = $("#actual_margin").val();

    //     let newmargin = margin - localExpense;
    //     newmargin = newmargin.toFixed(2);
    //     if (newmargin < 0) newmargin = 0;
    //     $("#projected_margin").val(newmargin);
    // }

    // function recalculateMarginAmount(localExpense) {

    //     let margin = $("#actual_margin").val();
    //     let newmargin = margin + localExpense;
    //     newmargin = newmargin.toFixed(2);
    //     if (newmargin < 0) newmargin = 0;
    //     $("#projected_margin").val(newmargin);
    // }

    $(document).ready(function() {



        jQuery('#localsupplier').on('change', function() {
            let selSuppliers = $(this).val();

            if (selSuppliers != null && selSuppliers != '') {
                $("#product_item_search").removeAttr('disabled');
                $("#add-new-product").removeClass("d-none");

                searchProducts();

            } else {
                $("#product_item_search").attr('disabled', 'disabled');
                $("#add-new-product").addClass("d-none");
                // disable
            }
        });

        $("#itemcustomFields").on('click', '.remIT', function() {

            let row = $(this).closest('tr');
            let irow = row.data('index');
            let linetotal = row.find("input[name='item[" + irow + "][total_amount]']").val() || 0;

            Swal.fire({
                title: "Are you sure?",
                text: "You are sure to delete the order item!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // recalculateMarginAmount(linetotal);

                    $(this).parents('tr').remove();

                }
            });
        });


        $("#supplierFields").on('click', '.remSup', function() {

            Swal.fire({
                title: "Are you sure?",
                text: "You are sure to delete the supplier!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    $(this).parents('tr').remove();

                }
            });
        });

        $(document).on('input change', '.quantity', function(e) {
            let row = $(this).closest('tr');
            let irow = row.data('index');

            let qty = $(this).val();
            let unitprice = row.find("input[name='item[" + irow + "][unit_price]']").val() || 0;
            let linetotal = parseFloat(unitprice * qty).toFixed(2);
            $("input[name='item[" + irow + "][total_amount]']").val(linetotal);

            // Purchase price
            let purchase_unitprice = row.find("input[name='item[" + irow + "][buying_unit_price]']").val() || 0;
            let purchase_linetotal = parseFloat(purchase_unitprice * qty).toFixed(2);
            $("input[name='item[" + irow + "][buying_price]']").val(purchase_linetotal);
        });

        // on change/ selection of product
        jQuery('#product_item_search').on('change', function(e) {
            e.preventDefault();
            let asset_url = `{{ env('APP_URL') }}`;
            let selProductId = $(this).val();

            let rowCnt = $("#itemcustomFields").find("tbody > tr").length;
            let newRow = '',
                title = '',
                brandId = 0,
                unitprice = 0,
                mosp = 0,
                subtotal = 0,
                discount = 0,
                qty = 1,
                total = 0,
                buyingPrice = 0,
                newmargin = 0,
                supplierData = [];

            if (productData.hasOwnProperty(selProductId)) {

                /****** Selling Price *****/
                brandId = productData[selProductId].brand_id;

                title = productData[selProductId].title;
                if (productData[selProductId].modelno) title += ' / ' + productData[selProductId].modelno + '';
                if (productData[selProductId].part_number) title += ' / ' + productData[selProductId].part_number + '';
                if (productData[selProductId].product_type) title += ' ( ' + (productData[selProductId].product_type).toUpperCase() + ') ';

                // this is exclusively for Local supplier products - AED
                if (productData[selProductId].currency == 'aed') {
                    unitprice = productData[selProductId].selling_price;
                }
                unitprice = parseFloat(unitprice).toFixed(2);
                // mosp = productData[selProductId].margin_percent;

                subtotal = unitprice * qty; // unitprice * quantity (default)
                subtotal = parseFloat(subtotal).toFixed(2);

                total = subtotal; //- (subtotal * discount / 100);
                total = parseFloat(total).toFixed(2);

                // newmargin = subtotal * (mosp / 100); //((mosp - discount) / 100);
                // newmargin = parseFloat(newmargin).toFixed(2);

                supplierData = productData[selProductId].supplier;

                let rowExists = $("#row-p" + selProductId);
                if (rowExists) {
                    rowExists.remove();
                }

                /****** Buying Price *****/
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
                            <b>${productData[selProductId].supplier.brand}</b><br />
                            <input type="hidden" name="item[${rowCnt}][product_id]" value="${selProductId}" />
                            <textarea class="form-control" name="item[${rowCnt}][item_name]" placeholder="Item">${title}</textarea>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[${rowCnt}][partno]" placeholder="Part No" value="${productData[selProductId].part_number}" />
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
                        /*  */
                        $("#itemcustomFields").find('tbody').append(newRow);

                        // add local supplier 
                        addLocalSupplier(supplierData);

                        // calculateMarginAmount(total); // Projected Margin price calculate
                    },
                    error: function(error) {
                        console.error('Error fetching product models:', error);
                    }
                });
                //

            }
        });


    });
</script>