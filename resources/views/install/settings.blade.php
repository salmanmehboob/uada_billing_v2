@extends('layouts.install')

@section('content')

    <!-- POS Settings -->
            <h4 class="mb-1">POS Settings ⚙️</h4>
            <p class="mb-4">Configure your shop settings before completing the installation.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('install.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label>Shop Name</label>
                    <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name', $settings->shop_name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Shop Logo</label>
                    <input type="file" name="shop_logo" class="form-control">
                    @if ($settings->shop_logo ?? false)
                        <img src="{{ asset($settings->shop_logo) }}" width="100" alt="Shop Logo">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Shop Email</label>
                    <input type="email" name="shop_email" class="form-control" value="{{ old('shop_email', $settings->shop_email ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Shop Phone</label>
                    <input type="text" name="shop_phone" class="form-control" value="{{ old('shop_phone', $settings->shop_phone ?? '') }}">
                </div>

                <div class="mb-3">
                    <label>Shop Address</label>
                    <textarea name="shop_address" class="form-control">{{ old('shop_address', $settings->shop_address ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Currency</label>
                    <input type="text" name="currency" class="form-control" value="{{ old('currency', $settings->currency ?? 'USD') }}" required>
                </div>

                <div class="mb-3">
                    <label>Tax Rate (%)</label>
                    <input type="number" step="0.01" name="tax_rate" class="form-control" value="{{ old('tax_rate', $settings->tax_rate ?? 0) }}">
                </div>

                <div class="mb-3">
                    <label>Tax Label</label>
                    <input type="text" name="tax_label" class="form-control" value="{{ old('tax_label', $settings->tax_label ?? 'VAT') }}">
                </div>

                <div class="mb-3">
                    <label>Invoice Format</label>
                    <select name="invoice_format" class="form-control">
                        <option value="standard" {{ old('invoice_format', $settings->invoice_format ?? 'standard') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="thermal" {{ old('invoice_format', $settings->invoice_format ?? 'standard') == 'thermal' ? 'selected' : '' }}>Thermal</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Invoice Prefix</label>
                    <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $settings->invoice_prefix ?? 'INV-') }}">
                </div>

                <div class="mb-3">
                    <label>Receipt Footer</label>
                    <textarea name="receipt_footer" class="form-control">{{ old('receipt_footer', $settings->receipt_footer ?? '') }}</textarea>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="enable_discount" value="1" class="form-check-input" {{ old('enable_discount', $settings->enable_discount ?? true) ? 'checked' : '' }}>
                    <label>Enable Discounts</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="enable_tax" value="1" class="form-check-input" {{ old('enable_tax', $settings->enable_tax ?? true) ? 'checked' : '' }}>
                    <label>Enable Tax</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="enable_stock_management" value="1" class="form-check-input" {{ old('enable_stock_management', $settings->enable_stock_management ?? true) ? 'checked' : '' }}>
                    <label>Enable Stock Management</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="enable_customer_management" value="1" class="form-check-input" {{ old('enable_customer_management', $settings->enable_customer_management ?? true) ? 'checked' : '' }}>
                    <label>Enable Customer Management</label>
                </div>

                <button type="submit" class="btn btn-primary d-grid w-100">Save Settings</button>
            </form>

    <!-- /POS Settings -->

@endsection
