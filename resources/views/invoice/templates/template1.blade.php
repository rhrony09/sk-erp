@php
    $settings_data = \App\Models\Utility::settingsById($invoice->created_by);
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $settings_data['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <style type="text/css">
        :root {
            --theme-color: {
                    {
                    $color
                }
            }

            ;
            --white: #ffffff;
            --black: #000000;
        }

        body {
            font-family: 'Lato', sans-serif;
            font-size: 12px;
            /* Base font size */
            margin: 0 20px;
            /* Added margin to left and right */
        }

        p,
        li,
        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
            line-height: 1.2;
            /* Reduced line height */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th,
        table tr td {
            padding: 0.3rem;
            /* Reduced padding */
            text-align: left;
        }

        .invoice-preview-main {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            background: #ffff;
            box-shadow: 0 0 10px #ddd;
            overflow: hidden;
            /* Prevent overflow */
        }

        @media print {
            .invoice-preview-main {
                box-shadow: none;
            }
        }

        .invoice-header,
        .invoice-body,
        .invoice-footer {
            padding: 10px;
            padding-bottom: 0 !important;
            /* Reduced padding */
        }

        .invoice-logo {
            max-width: 150px;
            width: 100%;
        }

        .text-right {
            text-align: right;
        }

        .invoice-summary th,
        .invoice-summary td {
            font-size: 11px;
            /* Smaller font size for summary */
        }

        .invoice-extra-info {
            padding-top: 5px;
            /* Reduced padding */
            width: 50%;
            word-break: break-all;
        }

        .invoice-signature {
            margin-top: 50px;
            /* Adjusted margin */
        }

        .customer-signature {
            border-top: 1px solid #000;
            width: 150px;
            /* Adjusted width */
            text-align: center;
            font-size: 10px;
            /* Smaller font size */
            padding-top: 2px;
            margin-top: 5px;
            /* Reduced padding */
        }

        .salesperson-name {
            margin-top: 15px;
            /* Spacing above salesperson name */
            font-size: 12px;
            /* Font size for salesperson */
        }

        .invoice-title {
            font-size: 32px;
            /* Increased font size for 'INVOICE' */
            font-weight: bold;
        }

        .add-border tr td,
        .add-border tr th {
            border: 1px solid #000000;
            padding: 3px !important;
        }

        @media print {

            /* Optional: Adjust for print media */
            body {
                -webkit-print-color-adjust: exact;
                /* Ensure colors are printed */
            }
        }

        html[dir="rtl"] table tr td,
        html[dir="rtl"] table tr th {
            text-align: right;
        }
    </style>

    @if ($settings_data['SITE_RTL'] == 'on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
</head>

<body>
    <div class="invoice-preview-main" id="boxes">
        <div class="invoice-header" style="background: {{ $color }}; color:{{ $font_color }};">
            <table>
                <tr>
                    <td><img class="invoice-logo" src="{{ $img }}" alt=""></td>
                    <td class="invoice-title text-right">{{ __('INVOICE') }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <p>
                            <h3>{{ $settings['company_name'] }}</h3>
                            {{-- {{ $settings['mail_from_address'] }}<br> --}}
                            {{ $settings['company_address'] }}<br>
                            {{ $settings['company_city'] }}, {{ $settings['company_state'] }}
                            {{ $settings['company_zipcode'] }}<br>
                            {{-- {{ $settings['company_country'] }}<br> --}}
                            {{ $settings['company_telephone'] }}<br>
                            {{-- {{ __('Reg. No') }}: {{ $settings['registration_number'] }}<br> --}}
                            {{-- @if ($settings['vat_gst_number_switch'] == 'on')
                                {{ $settings['tax_type'] }} {{ __('Number') }}: {{ $settings['vat_number'] }}
                            @endif --}}
                        </p>
                    </td>

                    <td style="width: 25%; text-align: right;">
                        <p>
                            {{ __('No') }}:
                            {{ Utility::invoiceNumberFormat($settings, $invoice->invoice_id) }}<br>
                            {{ __('Issue Date') }}: {{ Utility::dateFormat($settings, $invoice->issue_date) }}<br>
                            {{ __('Delivery Date') }}:
                            {{ Utility::dateFormat($settings, $invoice->due_date) }}<br>
                        </p>
                    </td>
                    <td style="width: 15%;">
                        <p class="text-right">
                            {!! DNS2D::getBarcodeHTML(route('invoice.link.copy', \Crypt::encrypt($invoice->invoice_id)), 'QRCODE', 2, 2) !!}
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="invoice-body">
            <table>
                <tr>
                    <td><strong>{{ __('Bill To') }}:</strong><br>{{ $customer->billing_name }}<br>{{ @$invoice->address->billing_address_line_1 }},
                        {{ @$invoice->address->billing_address_line_2 ? @$invoice->address->billing_address_line_2 . ', ' : '' }}
                        {{ @$invoice->address->billing_city }},
                        {{ @$invoice->address->billing_state }},
                        {{ @$invoice->address->billing_zipcode }}
                    </td>
                    @if ($settings['shipping_display'] == 'on')
                        <td class="text-right">
                            <strong>{{ __('Ship To') }}:</strong><br>{{ $customer->shipping_name ?? $customer->billing_name }}<br>{{ @$invoice->address->shipping_address_line_1 }},
                            {{ @$invoice->address->shipping_address_line_2 ? @$invoice->address->shipping_address_line_2 . ', ' : '' }}
                            {{ @$invoice->address->shipping_city }},
                            {{ @$invoice->address->shipping_state }},
                            {{ @$invoice->address->shipping_zipcode }}
                        </td>
                    @endif
                </tr>
            </table>
            <div class="invoice-summary">
                <table class="add-border invoice-summary" style="margin-top: 30px;">
                    
                    @if (isset($invoice->itemData) && count($invoice->itemData) > 0)
                    <thead style="background: {{ $color }};color:{{ $font_color }}">
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Item') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Rate') }}</th>
                            <th>{{ __('Discount') }}</th>
                            {{-- <th class="text-right">{{ __('Tax') }} (%)</th> --}}
                            <th class="text-right">{{ __('Price') }} <small>after discount</small></th>
                        </tr>
                    </thead>
                    @endif
                    <tbody>
                        @if (isset($invoice->itemData) && count($invoice->itemData) > 0)
                            @foreach ($invoice->itemData as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    @php
                                        $unit = $item->unit;
                                        $unitName = App\Models\ProductServiceUnit::find($unit);
                                    @endphp
                                    <td>{{ $item->quantity }}
                                        {{ $unitName != null ? '(' . $unitName->name . ')' : '' }}</td>
                                    <td>{{ Utility::priceFormat($settings, $item->price) }}</td>
                                    <td>{{ $item->discount != 0 ? Utility::priceFormat($settings, $item->discount) : '-' }}
                                    </td>
                                    @php
                                        $itemtax = 0;
                                    @endphp
                                    {{-- <td class="text-right">
                                        @if (!empty($item->itemTax))
                                            @foreach ($item->itemTax as $taxes)
                                                @php
                                                    $itemtax += $taxes['tax_price'];
                                                @endphp
                                                <p>{{ $taxes['name'] }} ({{ $taxes['rate'] }}) {{ $taxes['price'] }}
                                                </p>
                                            @endforeach
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td> --}}
                                    <td class="text-right">
                                        {{ Utility::priceFormat($settings, $item->price * $item->quantity - $item->discount + $itemtax) }}
                                    </td>
                                    @if (!empty($item->description))
                                        {{-- <tr class="border-0 itm-description">
                                    <td colspan="6">{{ $item->description }}</td>
                                </tr> --}}
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                        @if ($invoice->customerService)
                            <tr>
                                <td colspan="5">Service Charge: </td>
                                <td colspan="1" style="text-align: right;">{{ $invoice->customerService->service_charge }}৳</td>
                            </tr>
                        @endif

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">{{ __('Total') }}</td>
                            <td>{{ $invoice->totalQuantity }}</td>
                            <td>{{ Utility::priceFormat($settings, $invoice->totalRate) }}</td>
                            <td>{{ Utility::priceFormat($settings, $invoice->totalDiscount) }}</td>
                            {{-- <td class="text-right">{{ Utility::priceFormat($settings, $invoice->totalTaxPrice) }}</td> --}}
                            <td class="text-right">{{ Utility::priceFormat($settings, $invoice->getSubTotal()) }}</td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <td colspan="2" class="sub-total text-right">
                                <table class="total-table">
                                    <tr>
                                        <td class="text-right">{{ __('Subtotal') }}:</td>
                                        <td class="text-right">
                                            {{ Utility::priceFormat($settings, $invoice->getSubTotal()) }}</td>
                                    </tr>
                                    @if ($invoice->getTotalDiscount())
                                        <tr>
                                            <td class="text-right">{{ __('Discount') }}:</td>
                                            <td class="text-right">
                                                {{ Utility::priceFormat($settings, $invoice->getTotalDiscount()) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (!empty($invoice->taxesData))
                                        @foreach ($invoice->taxesData as $taxName => $taxPrice)
                                            <tr>
                                                <td class="text-right">{{ $taxName }} :</td>
                                                <td class="text-right">{{ Utility::priceFormat($settings, $taxPrice) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td class="text-right">{{ __('Total') }}:</td>
                                        <td class="text-right">
                                            {{ Utility::priceFormat($settings, $invoice->getSubTotal() - $invoice->getTotalDiscount() + $invoice->getTotalTax()) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right">{{ __('Paid') }}:</td>
                                        <td class="text-right">
                                            {{ Utility::priceFormat($settings, $invoice->getTotal() - $invoice->getDue() - $invoice->invoiceTotalCreditNote()) }}
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td class="text-right">{{ __('Credit Note') }}:</td>
                                        <td class="text-right">{{ Utility::priceFormat($settings, $invoice->invoiceTotalCreditNote()) }}
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <td class="text-right">{{ __('Due Amount') }}:</td>
                                        <td class="text-right">
                                            {{ Utility::priceFormat($settings, $invoice->getDue()) }}</td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="invoice-footer">

            <div class="invoice-extra-info" style="margin-bottom: 50px;">
                <h5>{{ __('Note') }}: <span
                        class="text-muted ml-2">{{ !empty($invoice->note) ? $invoice->note : '--' }}</span>
                </h5>

            </div>

            <div style="line-height: 30px !important;">
                {!! $invoice->footer_text !!}
            </div>

            <div class="invoice-signature">
                <div style="display: flex; justify-content: space-between;">
                    <div>
                        <div class="customer-signature" style="margin-top: 20px;">{{ __('Customer Signature') }}</div>
                        <!-- Customer signature field -->
                    </div>
                    <div>
                        <p style="text-align: center !important; font-size: 11px">
                            {{ !empty(@$invoice->customerService->employee->name) ? @$invoice->customerService->employee->name : 'N/A' }}</p>
                        <div class="customer-signature">{{ __('Sales Person') }}</div> <!-- Sales Person field -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!isset($preview) && $requestType != 'print')
        @include('invoice.script');
    @else
        <script>
            window.print();
        </script>
    @endif
</body>

</html>
