@extends('layouts.app')

@section('title', 'Tambah Transaksi Manual')

@section('content')
    <style>
        form { background: #fff; padding: 25px; border-radius: 8px; max-width: 800px; margin: auto; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; font-size: 0.9em; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        
        .item-row { display: flex; gap: 10px; align-items: center; margin-bottom: 10px; }
        .item-row .produk-select { flex: 3; }
        .item-row .jumlah-input { flex: 1; }
        .item-row .remove-btn { flex-shrink: 0; background-color: #dc3545; padding: 8px 12px; }
        #items-container { border: 1px solid #eee; padding: 15px; border-radius: 8px; }
        #tambah-item { background-color: #28a745; margin-top: 10px; }

        /* CSS untuk menyembunyikan/menampilkan form */
        #new-customer-fields {
            border: 1px dashed #0d6efd;
            padding: 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
    </style>
    
    <h1>Form Transaksi Manual</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        
        <h3>Detail Utama Transaksi</h3>
        
        <div>
            <input type="checkbox" id="new-customer-toggle" name="is_new_customer">
            <label for="new-customer-toggle" style="display: inline; font-weight: normal;">Pelanggan Baru?</label>
        </div>
        
        <div id="existing-customer-select">
            <label for="id_pelanggan">Pilih Pelanggan Lama</label>
            <select id="id_pelanggan" name="id_pelanggan" required>
                <option value="">-- Pilih Pelanggan --</option>
                @foreach($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->ID_Pelanggan }}">{{ $pelanggan->Nama_Pelanggan }}</option>
                @endforeach
            </select>
        </div>

        <div id="new-customer-fields" style="display: none;">
            <label for="new_customer_name">Nama Pelanggan Baru</label>
            <input type="text" id="new_customer_name" name="new_customer_name" disabled>
            
            <label for="new_customer_email" style="margin-top: 10px;">Email Pelanggan Baru (Opsional)</label>
            <input type="email" id="new_customer_email" name="new_customer_email" disabled>
        </div>
        <div style="display: flex; gap: 20px; margin-top: 20px;">
            <div style="flex: 1;">
                <label for="tanggal">Tanggal & Waktu Transaksi</label>
                <input type="datetime-local" id="tanggal" name="tanggal" value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div style="flex: 1;">
                <label for="metode_pembayaran">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" required>
                    <option value="QRIS">QRIS</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
        </div>

        <hr style="margin: 20px 0;">
        
        <h3>Detail Produk (Item)</h3>
        
        <div id="items-container">
            </div>
        
        <button type="button" id="tambah-item">
            <i class="fa fa-plus"></i> Tambah Produk
        </button>

        <hr style="margin: 20px 0;">

        <button type="submit">Simpan Transaksi</button>
        <a href="{{ route('transaksi.index') }}" style="margin-left: 10px;">Batal</a>
    </form>
    
    <template id="item-template">
        <div class="item-row">
            <div class="produk-select">
                <select name="items[0][id_produk]" class="form-control" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($produks as $produk)
                        <option value="{{ $produk->ID_Produk }}">{{ $produk->Nama_Produk }} (Rp {{ number_format($produk->Harga) }})</option>
                    @endforeach
                </select>
            </div>
            <div class="jumlah-input">
                <input type="number" name="items[0][jumlah_produk]" class="form-control" value="1" min="1" required>
            </div>
            <button type="button" class="remove-btn">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Logika untuk Item Dinamis (SAMA SEPERTI SEBELUMNYA) ---
            const container = document.getElementById('items-container');
            const addButton = document.getElementById('tambah-item');
            const template = document.getElementById('item-template');
            let itemIndex = 0;

            function addItem() {
                const newItem = template.content.cloneNode(true);
                const select = newItem.querySelector('select');
                const input = newItem.querySelector('input');
                const removeButton = newItem.querySelector('.remove-btn');
                
                select.name = `items[${itemIndex}][id_produk]`;
                input.name = `items[${itemIndex}][jumlah_produk]`;
                
                removeButton.addEventListener('click', function () {
                    this.closest('.item-row').remove();
                });
                
                container.appendChild(newItem);
                itemIndex++; 
            }
            addItem(); // Tambah 1 baris saat muat
            addButton.addEventListener('click', addItem);

            // --- JAVASCRIPT BARU UNTUK PELANGGAN BARU ---
            const toggle = document.getElementById('new-customer-toggle');
            const existingSelectDiv = document.getElementById('existing-customer-select');
            const newFieldsDiv = document.getElementById('new-customer-fields');
            
            const existingSelect = existingSelectDiv.querySelector('select');
            const newNameInput = newFieldsDiv.querySelector('#new_customer_name');
            const newEmailInput = newFieldsDiv.querySelector('#new_customer_email');

            toggle.addEventListener('change', function() {
                if (this.checked) {
                    // Tampilkan form pelanggan baru
                    existingSelectDiv.style.display = 'none';
                    newFieldsDiv.style.display = 'block';
                    
                    // 'disabled=true' agar tidak terkirim ke backend
                    existingSelect.disabled = true; 
                    
                    // Aktifkan input pelanggan baru
                    newNameInput.disabled = false;
                    newEmailInput.disabled = false;
                    newNameInput.required = true;

                } else {
                    // Tampilkan dropdown pelanggan lama
                    existingSelectDiv.style.display = 'block';
                    newFieldsDiv.style.display = 'none';
                    
                    // Aktifkan dropdown
                    existingSelect.disabled = false;
                    
                    // 'disabled=true' agar tidak terkirim ke backend
                    newNameInput.disabled = true;
                    newEmailInput.disabled = true;
                    newNameInput.required = false;
                }
            });
        });
    </script>
@endsection