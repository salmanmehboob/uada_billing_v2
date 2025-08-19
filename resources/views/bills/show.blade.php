@php use App\Helpers\GeneralHelper; @endphp
@extends('layouts.admin.app')
@push('style')
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            text-indent: 0;
        }

        table{
            border-color: black !important;
        }

        table td, table th { padding: 4px 6px; line-height: 1.25; }


        .border {
            border: 1px solid black !important;
        }


        .s5 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 13pt;
        }

        .s6 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 11pt;
        }


        .s9 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 10pt;
        }

        .s10 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .s11 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 8pt;
        }

        .s12 {
            color: black;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 11pt;
        }

        .s13 {
            color: #F00;
            font-family: Arial, sans-serif;
            font-style: normal;
            font-weight: bold;
            text-decoration: none;
            font-size: 9pt;
        }

        .card, .card-body, .card-header { border: none !important;}

    </style>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $title }} Record</h5>

            </div>

            <div class="d-flex justify-content-end align-items-center m-3">
                <button id="printBtn" type="button" class="btn btn-primary me-2">
                    Print Invoice
                </button>

            </div>


            <div class="invoice" id="printInvoice">

                <div class="card">
                    <div class="card-body">
                        <table style="margin-left:10pt; border-collapse: collapse;">
                            <tr class="border border-top border-left border-bottom border-right">

                                <td>
                                    <img src="{{ $deptLogoUrl }}" alt="Department Logo" class="p-2" height="120"
                                         width="120">

                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px;"> {{GeneralHelper::getSettingValue('name')}}</p>
                                    <p class="s6 pl-3 text-center" style="margin-bottom: 0px;">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="margin-bottom: 0px;text-indent: 0pt;text-align: center;">
                                        <span class="s9"> SERVICES BILL </span>
                                    </p>
                                    <p style="margin-bottom: 0px;text-indent: 0pt;text-align: center;">
                                        <span class="s9"> Customer Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img src="{{ $govtLogoUrl }}" alt="Government Logo" class="p-2" height="120"
                                         width="120">

                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10 text-center" style="margin-bottom: 0px">Bill Number</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12 pl-3 text-left" style="margin-bottom: 0px">*{{$bill->bill_number}}
                                        *</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13 pl-1 text-left" style="margin-bottom: 0px">Consumer ID</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13 pl-1 text-left"
                                       style="margin-bottom: 0px">{{$bill->allotee->id}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Issue Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s13 pl-2  text-left" style="margin-bottom: 0px">Due Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s10 pl-2 text-left" style="margin-bottom: 0px">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11 pl-2 text-left"
                                       style="margin-bottom: 0px">{{GeneralHelper::showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s13  text-left"
                                       style="margin-bottom: 0px">{{GeneralHelper::showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s11  pl-1 text-left"
                                       style="margin-bottom: 0px">{{$bill->fromMonth->short ?? ''}} {{$bill->year}}
                                        TO {{$bill->toMonth->short ?? ''}} {{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table class="float-left" style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 389pt;"
                                    colspan="4">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Charges Description</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Rate</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Amount</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 145pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Cost</p>
                                </th>

                            </tr>
                            <tr>
                            @foreach($bill->billCharges as $billCharges)
                                <tr>
                                    {{--                                                                    {{dd($billCharges)}}--}}
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s10 pl-1  text-left"
                                           style="margin-bottom: 0px">{{$billCharges->charge->name}}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-1  text-right"
                                           style="margin-bottom: 0px">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-2  text-right"
                                           style="margin-bottom: 0px">{{  $bill->total_months .' X '. $billCharges->amount  }}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-2  text-right"
                                           style="margin-bottom: 0px">{{$billCharges->total}}</p>
                                    </td>
                                </tr>
                                @endforeach

                                </tr>


                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 826pt;"
                                    colspan="12">
                                    <p class="s10 text-center line-height-9" style="margin-bottom: 0px">Payable
                                        Amount</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 158pt;"
                                >
                                    <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px">Current
                                        Bill</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px">
                                        Arrears</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px">Total Before
                                        Due Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;"
                                >
                                    <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px">Surcharge</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;"
                                >
                                    <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px">Total After
                                        Due Date</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 158pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->bill_total}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->arrears}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->sub_total}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->sub_charges}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->total}}</p>
                                </td>
                            </tr>

                        </table>
                    </div>

                    {{--------------------------------Office Copy----------------------------------}}
                    {{--                <div class="card-body">--}}
                    {{--                    <table style="margin-left:21pt; border-collapse: collapse;">--}}
                    {{--                        <tr class="border border-top border-left border-bottom border-right">--}}

                    {{--                            <td>--}}
                    {{--                                <img src="{{ $deptLogoUrl }}" alt="Department Logo"  class="p-2" height="120" width="120">--}}

                    {{--                            </td>--}}
                    {{--                            <td class="" style="width:645pt;" colspan="6">--}}

                    {{--                                <p class="s5 text-center"--}}
                    {{--                                   style="margin-bottom: 0px"> {{\App\Helpers\GeneralHelper::getSettingValue('name')}}</p>--}}
                    {{--                                <p class="s6  pl-3 text-center" style="margin-bottom: 0px">--}}
                    {{--                                    BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}--}}
                    {{--                                    A/C: {{$bill->bank->account_no}} </p>--}}

                    {{--                                <p style="text-indent: 0pt;text-align: center;margin-bottom: 0px;">--}}
                    {{--                                    <span class="s9"> SERVICES BILL </span>--}}
                    {{--                                </p>--}}
                    {{--                                <p style="text-indent: 0pt;text-align: center;margin-bottom: 0px;">--}}
                    {{--                                    <span class="s9"> Office Copy </span>--}}
                    {{--                                </p>--}}
                    {{--                            </td>--}}
                    {{--                            <td>--}}
                    {{--                                <img src="{{ $govtLogoUrl }}" alt="Government Logo"  class="p-2" height="120" width="120">--}}

                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                    </table>--}}
                    {{--                    <table style="margin-left:21pt; border-collapse: collapse;">--}}

                    {{--                        <tr>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width:389pt;"--}}
                    {{--                                colspan=6>--}}
                    {{--                                <p class="s10  pl-4 pr-3 text-center" style="margin-bottom: 0px">Bill Number</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 437pt;"--}}
                    {{--                                colspan="6">--}}
                    {{--                                <p class="s12  pl-3 text-left" style="margin-bottom: 0px">*{{$bill->bill_number}}--}}
                    {{--                                    *</p>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                        <tr>--}}
                    {{--                            <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"--}}
                    {{--                                colspan="6">--}}

                    {{--                                <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">--}}
                    {{--                                    {{$bill->allotee->name}}--}}
                    {{--                                </p>--}}
                    {{--                                <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">--}}
                    {{--                                    Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>--}}
                    {{--                                </p>--}}
                    {{--                                <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">--}}
                    {{--                                    Area : <span class="s11">{{$bill->size->name}}</span>--}}
                    {{--                                </p>--}}
                    {{--                                <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">--}}
                    {{--                                    Sector : <span class="s11">{{$bill->sector->name}}</span>--}}
                    {{--                                </p>--}}

                    {{--                                <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">--}}
                    {{--                                    Address : {{$bill->allotee->address}}--}}
                    {{--                                </p>--}}

                    {{--                            </td>--}}


                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 90pt;"--}}
                    {{--                                colspan="2">--}}
                    {{--                                <p class="s13  pl-1 text-left" style="margin-bottom: 0px">Consumer ID</p>--}}
                    {{--                            </td>--}}

                    {{--                            <td class="border border-top border-left border-bottom" style="width: 95pt;"--}}
                    {{--                                colspan="2">--}}
                    {{--                                <p class="s13  pl-1 text-left"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->allotee->account_no}}</p>--}}
                    {{--                            </td>--}}


                    {{--                        </tr>--}}
                    {{--                        <tr>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 90pt;"--}}
                    {{--                                colspan="2">--}}
                    {{--                                <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Issue Date</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 86pt;">--}}
                    {{--                                <p class="s13 pl-2  text-left" style="margin-bottom: 0px">Due Date</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 107pt;">--}}
                    {{--                                <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Billing Period</p>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                        <tr>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 90pt;"--}}
                    {{--                                colspan="2">--}}
                    {{--                                <p class="s11  pl-2 text-left"--}}
                    {{--                                   style="margin-bottom: 0px">{{\App\Helpers\GeneralHelper::showDate($bill->issue_date)}}</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 86pt;">--}}
                    {{--                                <p class="s13  pl-2 text-left"--}}
                    {{--                                   style="margin-bottom: 0px">{{\App\Helpers\GeneralHelper::showDate($bill->due_date)}}</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 107pt;">--}}
                    {{--                                <p class="s11  pl-1 text-left"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->fromMonth->short ?? ''}} {{$bill->year}}--}}
                    {{--                                    TO {{$bill->toMonth->short ?? ''}} {{$bill->year}} </p>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}

                    {{--                    </table>--}}
                    {{--                    <table class="float-left" style="margin-left:21pt; border-collapse: collapse;">--}}

                    {{--                        <tr>--}}
                    {{--                            <th class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 389pt;"--}}
                    {{--                                colspan="4">--}}
                    {{--                                <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Charges Description</p>--}}
                    {{--                            </th>--}}
                    {{--                            <th class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 146pt;"--}}
                    {{--                                colspan="4">--}}
                    {{--                                <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Rate</p>--}}
                    {{--                            </th>--}}
                    {{--                            <th class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 146pt;"--}}
                    {{--                                colspan="4">--}}
                    {{--                                <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Amount</p>--}}
                    {{--                            </th>--}}
                    {{--                            <th class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 145pt;"--}}
                    {{--                                colspan="4">--}}
                    {{--                                <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Cost</p>--}}
                    {{--                            </th>--}}

                    {{--                        </tr>--}}
                    {{--                        @foreach($bill->billCharges as $billCharges)--}}
                    {{--                            <tr>--}}
                    {{--                                --}}{{--                                                                    {{dd($billCharges)}}--}}
                    {{--                                <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                    colspan="4">--}}
                    {{--                                    <p class="s10 pl-1  text-left"--}}
                    {{--                                       style="margin-bottom: 0px">{{$billCharges->charge->name}}</p>--}}
                    {{--                                </td>--}}
                    {{--                                <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                    colspan="4">--}}
                    {{--                                    <p class="s11 pr-1  text-right"--}}
                    {{--                                       style="margin-bottom: 0px">{{$billCharges->amount}}</p>--}}
                    {{--                                </td>--}}
                    {{--                                <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                    colspan="4">--}}
                    {{--                                    <p class="s11 pr-2  text-right"--}}
                    {{--                                       style="margin-bottom: 0px">{{  $bill->total_months .' X '. $billCharges->amount  }}</p>--}}
                    {{--                                </td>--}}
                    {{--                                <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                    colspan="4">--}}
                    {{--                                    <p class="s11 pr-2  text-right"--}}
                    {{--                                       style="margin-bottom: 0px">{{$billCharges->total}}</p>--}}
                    {{--                                </td>--}}
                    {{--                            </tr>--}}
                    {{--                        @endforeach--}}


                    {{--                    </table>--}}
                    {{--                    <table style="margin-left:21pt; border-collapse: collapse;">--}}

                    {{--                        <tr class="h-14">--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 826pt;"--}}
                    {{--                                colspan="12">--}}
                    {{--                                <p class="s10 text-center line-height-9" style="margin-bottom: 0px">Payable--}}
                    {{--                                    Amount</p>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                        <tr class="h-14">--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 158pt;"--}}
                    {{--                            >--}}
                    {{--                                <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px">Current--}}
                    {{--                                    Bill</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 148pt;"--}}
                    {{--                                colspan="2">--}}
                    {{--                                <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px">--}}
                    {{--                                    Arrears</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 90pt;"--}}
                    {{--                                colspan="3">--}}
                    {{--                                <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px">Total Before--}}
                    {{--                                    Due Date</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 86pt;"--}}
                    {{--                            >--}}
                    {{--                                <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px">Surcharge</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 107pt;"--}}
                    {{--                            >--}}
                    {{--                                <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px">Total After--}}
                    {{--                                    Due Date</p>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    {{--                        <tr class="h-14">--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 158pt;">--}}
                    {{--                                <p class="s11 text-center line-height-9"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->bill_total}}</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 148pt;"--}}
                    {{--                                colspan="2">--}}
                    {{--                                <p class="s11 text-center line-height-9"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->arrears}}</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 90pt;"--}}
                    {{--                                colspan="3">--}}
                    {{--                                <p class="s13 text-center line-height-9"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->sub_total}}</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 86pt;">--}}
                    {{--                                <p class="s11 text-center line-height-9"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->sub_charges}}</p>--}}
                    {{--                            </td>--}}
                    {{--                            <td class="border border-top border-left border-bottom border-right"--}}
                    {{--                                style="width: 107pt;">--}}
                    {{--                                <p class="s13 text-center line-height-9"--}}
                    {{--                                   style="margin-bottom: 0px">{{$bill->total}}</p>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}

                    {{--                    </table>--}}

                    {{--                </div>--}}
                    {{--------------------------------File Copy----------------------------------}}
                    <div class="card-body">
                        <table style="margin-left:21pt; border-collapse: collapse;">
                            <tr class="border border-top border-left border-bottom border-right">

                                <td>
                                    <img src="{{ $deptLogoUrl }}" alt="Department Logo" class="p-2" height="120"
                                         width="120">

                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px"> {{GeneralHelper::getSettingValue('name')}}</p>
                                    <p class="s6 pl-3 text-center" style="margin-bottom: 0px">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> SERVICES BILL </span>
                                    </p>
                                    <p style="text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> File Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img src="{{ $govtLogoUrl }}" alt="Government Logo" class="p-2" height="120"
                                         width="120">

                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10  pl-4 pr-3 text-center" style="margin-bottom: 0px">Bill Number</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12  pl-3 text-left" style="margin-bottom: 0px">*{{$bill->bill_number}}
                                        *</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13  pl-1 text-left" style="margin-bottom: 0px">Consumer ID</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13  pl-1 text-left"
                                       style="margin-bottom: 0px">{{$bill->allotee->account_no}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Issue Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s13 pl-2  text-left" style="margin-bottom: 0px">Due Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11  pl-2 text-left"
                                       style="margin-bottom: 0px">{{GeneralHelper::showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s13  pl-2 text-left"
                                       style="margin-bottom: 0px">{{GeneralHelper::showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s11  pl-1 text-left"
                                       style="margin-bottom: 0px">{{$bill->fromMonth->short ?? ''}} {{$bill->year}}
                                        TO {{$bill->toMonth->short ?? ''}} {{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table class="float-left" style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 389pt;"
                                    colspan="4">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Charges Description</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Rate</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Amount</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 145pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Cost</p>
                                </th>

                            </tr>
                            @foreach($bill->billCharges as $billCharges)
                                <tr>
                                    {{--                                                                    {{dd($billCharges)}}--}}
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s10 pl-1  text-left"
                                           style="margin-bottom: 0px">{{$billCharges->charge->name}}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-1  text-right"
                                           style="margin-bottom: 0px">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-2  text-right"
                                           style="margin-bottom: 0px">{{  $bill->total_months .' X '. $billCharges->amount  }}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-2  text-right"
                                           style="margin-bottom: 0px">{{$billCharges->total}}</p>
                                    </td>
                                </tr>
                            @endforeach


                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 826pt;"
                                    colspan="12">
                                    <p class="s10 text-center line-height-9" style="margin-bottom: 0px">Payable
                                        Amount</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 158pt;"
                                >
                                    <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px">Current
                                        Bill</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px">
                                        Arrears</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px">Total Before
                                        Due Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;"
                                >
                                    <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px">Surcharge</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;"
                                >
                                    <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px">Total After
                                        Due Date</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 158pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->bill_total}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->arrears}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->sub_total}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->sub_charges}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->total}}</p>
                                </td>
                            </tr>

                        </table>

                    </div>
                    {{--------------------------------Bank Copy----------------------------------}}
                    <div class="card-body">
                        <table style="margin-left:21pt; border-collapse: collapse;">
                            <tr class="border border-top border-left border-bottom border-right">

                                <td>
                                    <img src="{{ $deptLogoUrl }}" alt="Department Logo" class="p-2" height="120"
                                         width="120">

                                </td>
                                <td class="" style="width:645pt;" colspan="6">

                                    <p class="s5 text-center"
                                       style="margin-bottom: 0px"> {{GeneralHelper::getSettingValue('name')}}</p>
                                    <p class="s6  pl-3 text-center" style="margin-bottom: 0px">
                                        BANK: {{$bill->bank->name . ' ' . $bill->bank->branch}}
                                        A/C: {{$bill->bank->account_no}} </p>

                                    <p style="text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> SERVICES BILL </span>
                                    </p>
                                    <p style="text-indent: 0pt;text-align: center;margin-bottom: 0px;">
                                        <span class="s9"> Bank Copy </span>
                                    </p>
                                </td>
                                <td>
                                    <img src="{{ $govtLogoUrl }}" alt="Government Logo" class="p-2" height="120"
                                         width="120">

                                </td>
                            </tr>
                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width:389pt;"
                                    colspan=6>
                                    <p class="s10  pl-4 pr-3 text-center" style="margin-bottom: 0px">Bill Number</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 437pt;"
                                    colspan="6">
                                    <p class="s12  pl-3 text-left" style="margin-bottom: 0px">*{{$bill->bill_number}}
                                        *</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom" rowspan="4" style="width: 300pt"
                                    colspan="6">

                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        {{$bill->allotee->name}}
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Plot : <span class="s11">{{$bill->allotee->plot_no}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Area : <span class="s11">{{$bill->size->name}}</span>
                                    </p>
                                    <p class="s10 pl-3 pr-3 text-left" style="margin-bottom: 0px">
                                        Sector : <span class="s11">{{$bill->sector->name}}</span>
                                    </p>

                                    <p class="s11 pl-3 text-left" style="line-height: 9pt;margin-bottom: 0px;">
                                        Address : {{$bill->allotee->address}}
                                    </p>

                                </td>


                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s13  pl-1 text-left" style="margin-bottom: 0px">Consumer ID</p>
                                </td>

                                <td class="border border-top border-left border-bottom" style="width: 95pt;"
                                    colspan="2">
                                    <p class="s13  pl-1 text-left"
                                       style="margin-bottom: 0px">{{$bill->allotee->account_no}}</p>
                                </td>


                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Issue Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s13 pl-2  text-left" style="margin-bottom: 0px">Due Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Billing Period</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="2">
                                    <p class="s11  pl-2 text-left"
                                       style="margin-bottom: 0px">{{GeneralHelper::showDate($bill->issue_date)}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s13  pl-2 text-left"
                                       style="margin-bottom: 0px">{{GeneralHelper::showDate($bill->due_date)}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s11  pl-1 text-left"
                                       style="margin-bottom: 0px">{{$bill->fromMonth->short ?? ''}} {{$bill->year}}
                                        TO {{$bill->toMonth->short ?? ''}} {{$bill->year}} </p>
                                </td>
                            </tr>

                        </table>
                        <table class="float-left" style="margin-left:21pt; border-collapse: collapse;">

                            <tr>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 389pt;"
                                    colspan="4">
                                    <p class="s10 pl-2  text-left" style="margin-bottom: 0px">Charges Description</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Rate</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 146pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Amount</p>
                                </th>
                                <th class="border border-top border-left border-bottom border-right"
                                    style="width: 145pt;"
                                    colspan="4">
                                    <p class="s10 pl-1  text-left" style="margin-bottom: 0px">Cost</p>
                                </th>

                            </tr>
                            @foreach($bill->billCharges as $billCharges)
                                <tr>
                                    {{--                                                                    {{dd($billCharges)}}--}}
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s10 pl-1  text-left"
                                           style="margin-bottom: 0px">{{$billCharges->charge->name}}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-1  text-right"
                                           style="margin-bottom: 0px">{{$billCharges->amount}}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-2  text-right"
                                           style="margin-bottom: 0px">{{  $bill->total_months .' X '. $billCharges->amount  }}</p>
                                    </td>
                                    <td class="border border-top border-left border-bottom border-right"
                                        colspan="4">
                                        <p class="s11 pr-2  text-right"
                                           style="margin-bottom: 0px">{{$billCharges->total}}</p>
                                    </td>
                                </tr>
                            @endforeach


                        </table>
                        <table style="margin-left:21pt; border-collapse: collapse;">

                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 826pt;"
                                    colspan="12">
                                    <p class="s10 text-center line-height-9" style="margin-bottom: 0px">Payable
                                        Amount</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 158pt;"
                                >
                                    <p class="s10 pl-4 text-left line-height-9" style="margin-bottom: 0px">Current
                                        Bill</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s10 pl-4 pr-4 text-center line-height-9" style="margin-bottom: 0px">
                                        Arrears</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 pl-1 text-left line-height-9" style="margin-bottom: 0px">Total Before
                                        Due Date</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;"
                                >
                                    <p class="s10 pl-3 text-left line-height-9" style="margin-bottom: 0px">Surcharge</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;"
                                >
                                    <p class="s13 pl-3 text-left line-height-9" style="margin-bottom: 0px">Total After
                                        Due Date</p>
                                </td>
                            </tr>
                            <tr class="h-14">
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 158pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->bill_total}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 148pt;"
                                    colspan="2">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->arrears}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 90pt;"
                                    colspan="3">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->sub_total}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 86pt;">
                                    <p class="s11 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->sub_charges}}</p>
                                </td>
                                <td class="border border-top border-left border-bottom border-right"
                                    style="width: 107pt;">
                                    <p class="s13 text-center line-height-9"
                                       style="margin-bottom: 0px">{{$bill->total}}</p>
                                </td>
                            </tr>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('page_js')
    <!-- Libraries needed for Print and PDF -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/print-this@1.15.0/printThis.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            window.html2canvas = html2canvas; // add this line of code
            window.jsPDF = window.jspdf.jsPDF; // add this line of code
            $(function () {

                $('#printBtn').on('click', function () {
                    var invoices = $("#printInvoice"); // Select container with invoice
                    var container = $('<div></div>'); // Create a container element to hold all invoices

                    // Loop through each invoice and append its HTML content to the container
                    invoices.each(function (index, invoice) {
                        var invoiceHTML = $(invoice).html(); // Get the HTML content of each invoice
                        container.append('<div class="invoice">' + invoiceHTML + '</div>'); // Append invoice HTML to the container
                    });

                    // Print the container with all invoices
                    container.printThis({
                        importCSS: true, // Import CSS for printing
                        loadCSS: "", // Path to external CSS file (if needed)
                        header: null, // Exclude header from the printed output
                        footer: null, // Exclude footer from the printed output
                        pageTitle: "Invoice", // Set a custom page title
                        afterPrint: function () {
                            // Callback function after printing (optional)
                            console.log("Invoice printed");
                        }
                    });
                });


            });

        });
    </script>
@endpush
