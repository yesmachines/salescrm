<div class="row gx-3">
    <div class="col-md-9">
        <label class="form-label">Enter Product / Model No / Part No <span class="text-danger">*</span></label>
        <div class="form-group">
            <select class="form-control select2" name="product_item_search" id="product_item_search" disabled>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">&nbsp;</label>
        <div class="form-group text-center">
            <a href="javascript:void(0);" id="add-new-product" class="mt-3 d-none" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"></i> Create New Product</a>
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
                        <p class="currency-label">UnitPrice(AED)</p>
                    </th>
                    <th>Quantity</th>
                    <th>
                        <p class="currency-label">Line Total(AED)</p>
                    </th>
                    <th>Discount(%)</th>
                    <th>
                        <p class="currency-label">Total After Discount(AED)</p>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>