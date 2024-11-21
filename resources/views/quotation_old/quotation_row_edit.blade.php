<div class="row gx-3">
    <input class="form-control" type="hidden" name="total_amount" value="{{$quotation->total_amount}}" />
    <input class="form-control" type="hidden" name="gross_margin" value="{{$quotation->gross_margin}}" />

    <div class="col-md-3">
        <label class="form-label">Preffered Currency</label>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <select class="form-control" name="quote_currency" id="currencyDropdown" onchange="updateCurrencyLabel()" required>
                <option value="" disabled>Select Currency</option>
                @foreach($currencies as $currency)
                <option value="{{ $currency->code }}" {{ $quotation->preferred_currency == $currency->code ? 'selected' : '' }}>
                    {{ $currency->name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <label class="form-label">Delivery Terms</label>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <select class="form-control" name="payment_term" id="paymentTerm" required onchange="updateQuotationTerms()">
                @foreach($paymentTermList as $paymentTerm)
                <option value="{{ $paymentTerm->id }}" data-title="{{ $paymentTerm->title }}">{{ $paymentTerm->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row gx-3">
    <div class="row gx-3">&nbsp;</div>
</div>
<div class="row gx-3">
    <div class="col-sm-12">
        <table class="table">
            <thead class=" thead-light">
                <tr>
                    <th>Product</th>
                    <th>
                        <p class="currency-label">Unit Price({{$quotation->preferred_currency}})</p>
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
                @forelse($quotationItems as $k => $items)
                {{$items}}
                <tr data-item-id="{{ $items->id }}">
                    <td width="25%">
                        {{$items->product->title}} {{$items->product->modelno? '/'.$items->product->modelno: ''}}
                        {{$items->product->part_number? '/'.$items->product->part_number : ''}}
                        <br />
                        {{$items->description}}
                    </td>
                    <td>
                        {{$items->unit_price}}<br />
                        <span class="text-danger">MOSP : {{$items->product->margin_percent}} %</span>
                        <!-- <span class="text-danger">Margin Price : {{$items->product->margin_price}}</span> -->
                    </td>
                    <td>{{$items->quantity}}

                    </td>
                    <td>{{$items->subtotal}}</td>
                    <td>{{$items->discount}} %</td>
                    <td>{{$items->total_after_discount}}<br />
                        <span class="text-success">Margin : {{$items->product->margin_price}}</span>
                    </td>
                    <th>Actions</th>
                </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </div>
</div>


<script>
    function updateCurrencyLabel() {
        var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
        var labels = document.getElementsByClassName("currency-label");
        var labelsTerm = document.getElementsByClassName("currency-label-terms");

        for (var i = 0; i < labels.length; i++) {
            var labelText = labels[i].textContent;
            labelText = labelText.replace(/\([A-Z]+\)/g, '');
            labels[i].textContent = labelText + ' (' + selectedCurrency + ')';

            if (labelsTerm[i]) {
                var paymentTermDropdown = document.getElementById("paymentTerm");
                var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
                var paymentTitle = selectedOption.getAttribute("data-title");

                labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '.' + paymentTitle;

                var categoryDropdown = $('select[name="product[]"]');

            }
        }
    }

    function updateQuotationTerms() {
        var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
        var labels = document.getElementsByClassName("currency-label");
        var labelsTerm = document.getElementsByClassName("currency-label-terms");

        for (var i = 0; i < labels.length; i++) {
            if (labelsTerm[i]) {
                var paymentTermDropdown = document.getElementById("paymentTerm");
                var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
                var paymentTitle = selectedOption.getAttribute("data-title");

                labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '.' + paymentTitle;

                var categoryDropdown = $('select[name="product[]"]');
                // initializeModelDropdown(categoryDropdown);
            }
        }
    }
</script>