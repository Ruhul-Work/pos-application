@extends('backend.layouts.master')

@section('content')

<h6 class="mb-3">Purchase Return</h6>

<div class="row mb-3">
    <div class="col-md-4">
        <input type="text" class="form-control" id="purchase_order" placeholder="Enter Purchase Order No">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary btn-sm" id="search_order">Search</button>
    </div>
</div>

<div id="return_section" style="display:none">

    <div class="card mb-3">
        <div class="card-body lh-1">
            <h6>Invoice Details</h6>
            <p class="order_no"></p>
            <p class="supplier"></p>
            <p class="payment_status"></p>
            <p class="purchase_date"></p>

            <table class="table table-bordered mt-3">
                <thead class="table-light">
                <tr>
                    <th>SL</th>
                    <th>Product</th>
                    <th>Purchased Qty</th>
                    <th>Unit Cost</th>
                    <th>Line Total</th>
                    <th>Return Qty</th>
                </tr>
                </thead>
                <tbody id="purchase_item_table"></tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-5">
                <p class="bg-danger text-light p-3 rounded text-lg">Total Refund: <span id="total_refund">0.00</span></p>
                <button class="btn btn-danger btn-sm" id="confirm_return">Confirm Return</button>
            </div>
        </div>
    </div>

</div>

@endsection


@section('script')
<script>

let CURRENT_PURCHASE_ID = null;

// ---------------- SEARCH ORDER ----------------
$('#search_order').on('click', function () {

    let orderNo = $('#purchase_order').val().trim();

    if (!orderNo) {
        alert('Please enter purchase order number');
        return;
    }

    $.ajax({
        url: "{{ route('purchase.order.details', ':order') }}".replace(':order', orderNo),
        method: 'GET',

        success: function (response) {

            CURRENT_PURCHASE_ID = response.order.id;

            $('#return_section').show();
            $('#purchase_item_table').empty();
            $('#total_refund').text('0.00');

            $('.order_no').text('Order No: ' + response.order.po_number);
            $('.supplier').text('Supplier: ' + response.order.supplier.name);
            $('.payment_status').text('Payment Status: ' + response.order.payment_status);
            $('.purchase_date').text('Date: ' + response.order.order_date);

            response.order.items.forEach((item, index) => {

                let availableQty = item.quantity - (item.returned_qty || 0);

                $('#purchase_item_table').append(`
                    <tr data-purchase-item-id="${item.id}"
                        data-product-id="${item.product_id}"
                        data-unit-cost="${item.unit_cost}"
                        data-available-qty="${availableQty}">

                        <td>${index + 1}</td>
                        <td>${item.product.name}</td>
                        <td>${availableQty}</td>
                        <td>${item.unit_cost}</td>
                        <td>${item.line_total}</td>
                        <td>
                            <input type="number"
                                   class="form-control form-control-sm return-qty"
                                   value="0"
                                   min="0"
                                   max="${availableQty}">
                        </td>
                    </tr>
                `);
            });

        },

        error: function () {
            alert('Purchase order not found');
            $('#return_section').hide();
        }
    });
});


// ---------------- LIVE REFUND CALC ----------------
$(document).on('input', '.return-qty', function () {

    let totalRefund = 0;

    $('#purchase_item_table tr').each(function () {

        let unitCost = parseFloat($(this).data('unit-cost'));
        let returnQty = parseFloat($(this).find('.return-qty').val()) || 0;
        let maxQty = parseFloat($(this).data('available-qty'));

        if (returnQty > maxQty) {
            returnQty = maxQty;
            $(this).find('.return-qty').val(maxQty);
        }

        totalRefund += unitCost * returnQty;
    });

    $('#total_refund').text(totalRefund.toFixed(2));
});


// ---------------- CONFIRM RETURN ----------------
$('#confirm_return').on('click', function () {

    let items = [];

    $('#purchase_item_table tr').each(function () {

        let returnQty = parseFloat($(this).find('.return-qty').val()) || 0;

        if (returnQty > 0) {
            items.push({
                purchase_item_id: $(this).data('purchase-item-id'),
                product_id: $(this).data('product-id'),
                return_qty: returnQty
            });
        }
    });

    if (items.length === 0) {
        alert('Please select at least one product to return.');
        return;
    }

    let payload = {
        purchase_id: CURRENT_PURCHASE_ID,
        purchase_order_no: $('#purchase_order').val(),
        items: items
    };

    console.log(payload); // DEBUG

    // $.ajax({
    //     url: "",
    //     method: "POST",
    //     data: payload,
    //     headers: {
    //         'X-CSRF-TOKEN': "{{ csrf_token() }}"
    //     },

    //     success: function () {
    //         alert('Purchase return completed successfully');
    //         location.reload();
    //     },

    //     error: function (xhr) {
    //         alert(xhr.responseJSON?.message || 'Return failed');
    //     }
    // });

});

</script>
@endsection
