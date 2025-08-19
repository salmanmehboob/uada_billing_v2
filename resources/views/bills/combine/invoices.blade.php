@extends('layouts.admin.app')
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
@section('content')
    <div class="container-xxl py-3">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <h5 class="mb-0">{{ $title }}</h5>
            <div class="d-flex gap-2">
                <a href="{{ route($route . '.list') }}" class="btn btn-outline-primary">Back to Listing</a>
                <button onclick="window.print()" class="btn btn-outline-primary"> <i class="icon-base ti tabler-printer icon-xs me-1"></i>Print</button>
            </div>
        </div>

        @if($bills->isEmpty())
            <div class="alert alert-info">No combined bills match the selected filters.</div>
        @endif

        @foreach($bills as $bill)
            <div class="invoice-wrapper mt-5" id="printInvoice">
                @include('bills.combine.show', ['bill' => $bill])
            </div>
            <div class="page-break"></div>
        @endforeach

    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .card, .card-body, .card-header { border: none !important; }
            table td, table th { padding: 4px 6px !important; line-height: 1.25 !important; }

        }
        @media screen {
            .page-break { height: 40px; }
            .invoice-wrapper { background: #fff; }
        }
    </style>

    @if(request()->boolean('print'))
        <script>
            // Give the browser a tick to render, then open the print dialog
            window.addEventListener('load', () => {
                setTimeout(() => window.print(), 150);
            });
        </script>
    @endif
@endsection