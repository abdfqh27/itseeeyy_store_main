@extends('layouts.app')

@section('title', $title)
@section('header', $title)

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800">Laporan</h2>
    <div class="flex space-x-2">
        <form method="GET" action="{{ route('reports.index') }}" class="inline">
            <input type="hidden" name="export_pdf" value="1">
            <input type="hidden" name="from_date" value="{{ $fromDate }}">
            <input type="hidden" name="to_date" value="{{ $toDate }}">
            <input type="hidden" name="report_type" value="{{ $reportType }}">
            <button type="submit" class="bg-secondary hover:bg-secondary/90 text-white py-2 px-4 rounded-md transition-all inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Ekspor ke PDF
            </button>
        </form>
    </div>
</div>

<!-- Filter -->
<div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
    <div class="p-4 px-6 border-b border-pink-100 bg-pink-50">
        <h3 class="text-sm font-medium text-pink-500">Pengaturan Laporan</h3>
    </div>
    <form method="GET" action="{{ route('reports.index') }}" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <x-form-select name="report_type" id="report_type" label="Jenis Laporan">
                    <option value="daily" {{ $reportType == 'daily' ? 'selected' : '' }}>Harian</option>
                    <option value="weekly" {{ $reportType == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                    <option value="monthly" {{ $reportType == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                    <option value="yearly" {{ $reportType == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                </x-form-select>
            </div>
            <div>
                <x-form-input
                    type="date"
                    name="from_date"
                    id="from_date"
                    :value="$fromDate"
                    label="Dari Tanggal" />
            </div>
            <div>
                <x-form-input
                    type="date"
                    name="to_date"
                    id="to_date"
                    :value="$toDate"
                    label="Sampai Tanggal" />
            </div>
            <div class="flex items-end">
                <x-button type="submit" variant="primary" class="w-full">Buat Laporan</x-button>
            </div>
        </div>
    </form>
</div>

<!-- Hasil Laporan -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-4 px-6 border-b border-pink-100 bg-pink-50">
        <h3 class="font-medium text-pink-500">Hasil Laporan: {{ $fromDate }} hingga {{ $toDate }}</h3>
    </div>

    @if($groupedData->isEmpty())
    <div class="p-6 text-center text-gray-500">
        Tidak ada data tersedia untuk periode yang dipilih.
    </div>
    @else
    @php
    $totalIn = $groupedData->flatten()->where('type', 'in')->sum('quantity');
    $totalOut = $groupedData->flatten()->where('type', 'out')->sum('quantity');
    $totalNet = $totalIn - $totalOut;
    @endphp
        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-pink-100 text-gray-700 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Periode</th>
                    <th class="px-6 py-3 text-left font-semibold">Stok Masuk</th>
                    <th class="px-6 py-3 text-left font-semibold">Stok Keluar</th>
                    <th class="px-6 py-3 text-left font-semibold">Perubahan Bersih</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($groupedData as $period => $movements)
                @php
                $stockIn = $movements->where('type', 'in')->sum('quantity');
                $stockOut = $movements->where('type', 'out')->sum('quantity');
                $netChange = $stockIn - $stockOut;
                @endphp
                <tr class="hover:bg-pink-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $period }}</td>
                    <td class="px-6 py-4 text-green-600 font-semibold">+{{ $stockIn }}</td>
                    <td class="px-6 py-4 text-red-600 font-semibold">-{{ $stockOut }}</td>
                    <td class="px-6 py-4 font-semibold {{ $netChange >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $netChange >= 0 ? '+' : '' }}{{ $netChange }}
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="px-6 py-2 bg-pink-50">
                        <h4 class="text-xs font-semibold text-pink-700 mb-2">📋 Detail Pergerakan</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-xs divide-y divide-gray-200">
                                <thead class="bg-gray-100 text-gray-600">
                                    <tr>
                                        <th class="px-3 py-2 text-left">Produk</th>
                                        <th class="px-3 py-2 text-left">Tipe</th>
                                        <th class="px-3 py-2 text-left">Jumlah</th>
                                        <th class="px-3 py-2 text-left">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($movements as $movement)
                                    <tr class="odd:bg-white even:bg-gray-50">
                                        <td class="px-3 py-2">{{ $movement->product->name }}</td>
                                        <td class="px-3 py-2">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-2 font-semibold">{{ $movement->quantity }}</td>
                                        <td class="px-3 py-2 text-gray-500">{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-pink-100 font-semibold text-sm text-gray-700">
                <tr>
                    <td class="px-6 py-3">Total</td>
                    <td class="px-6 py-3 text-green-600">+{{ $totalIn }}</td>
                    <td class="px-6 py-3 text-red-600">-{{ $totalOut }}</td>
                    <td class="px-6 py-3 {{ $totalNet >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $totalNet >= 0 ? '+' : '' }}{{ $totalNet }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>
@endsection
