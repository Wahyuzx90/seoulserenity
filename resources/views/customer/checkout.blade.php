@extends('layouts.app')
@section('title', 'Checkout — Seoul Serenity')

@push('styles')
<style>
.checkout-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
}

.checkout-form {
    background: var(--parchment);
    border-radius: 20px;
    padding: 30px;
}

.form-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.form-section h3 {
    font-family: var(--font-display);
    margin-bottom: 20px;
    color: var(--ink);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--gochujang);
    box-shadow: 0 0 0 3px rgba(194,59,34,0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.payment-methods {
    display: grid;
    gap: 12px;
}

.payment-method {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-method:hover {
    border-color: var(--gochujang);
    background: rgba(194,59,34,0.05);
}

.payment-method.selected {
    border-color: var(--gochujang);
    background: rgba(194,59,34,0.05);
}

.payment-method input {
    margin-right: 15px;
    width: auto;
}

.payment-method .method-icon {
    font-size: 28px;
    margin-right: 15px;
}

.payment-method .method-info {
    flex: 1;
}

.payment-method .method-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.payment-method .method-desc {
    font-size: 12px;
    color: var(--ash);
}

.order-summary {
    background: var(--parchment);
    border-radius: 20px;
    padding: 30px;
    position: sticky;
    top: 20px;
    height: fit-content;
}

.summary-items {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 20px;
}

.summary-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    align-items: center;
}

.summary-item-img {
    width: 60px;
    height: 60px;
    background: var(--parchment);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}

.summary-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 12px;
}

.summary-item-img .emoji-summary {
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.summary-item-info {
    flex: 1;
}

.summary-item-name {
    font-weight: 600;
    margin-bottom: 4px;
}

.summary-item-qty {
    font-size: 12px;
    color: var(--ash);
}

.summary-item-price {
    font-weight: 600;
    color: var(--gochujang);
    white-space: nowrap;
}

.summary-total {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid rgba(0,0,0,0.1);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
}

.summary-row.total {
    font-size: 20px;
    font-weight: 700;
    color: var(--gochujang);
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0,0,0,0.1);
}

.btn-checkout {
    width: 100%;
    padding: 16px;
    background: var(--gochujang);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 20px;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(194,59,34,0.3);
}

/* Payment Modal */
.payment-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    overflow-y: auto;
}

.payment-modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 24px;
    max-width: 550px;
    width: 90%;
    padding: 30px;
    text-align: center;
    max-height: 90vh;
    overflow-y: auto;
}

/* QR Code Styles */
.qr-container {
    background: white;
    padding: 20px;
    border-radius: 20px;
    margin: 20px auto;
    display: inline-block;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

#qrcode {
    text-align: center;
}

#qrcode canvas,
#qrcode img {
    width: 250px;
    height: 250px;
}

.bank-info {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: white;
    padding: 20px;
    border-radius: 16px;
    margin: 20px 0;
}

.bank-info h4 {
    margin-bottom: 10px;
}

.account-number {
    font-size: 32px;
    font-weight: 700;
    letter-spacing: 3px;
    background: rgba(255,255,255,0.2);
    padding: 10px;
    border-radius: 10px;
    margin: 10px 0;
    font-family: monospace;
}

.upload-area {
    border: 2px dashed #ddd;
    border-radius: 12px;
    padding: 20px;
    margin: 15px 0;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
}

.upload-area:hover {
    border-color: var(--gochujang);
    background: rgba(194,59,34,0.05);
}

.upload-area.dragover {
    border-color: var(--gochujang);
    background: rgba(194,59,34,0.1);
}

.file-info {
    background: #f5f5f5;
    padding: 10px;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.remove-file {
    background: #f44336;
    color: white;
    border: none;
    padding: 4px 10px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 11px;
}

.preview-image {
    max-width: 80px;
    max-height: 80px;
    margin-top: 10px;
    border-radius: 8px;
}

.copy-btn,
.confirm-btn {
    background: var(--gochujang);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    margin: 5px;
    font-weight: 600;
    font-size: 14px;
}

.confirm-btn {
    background: #4caf50;
}

.confirm-btn:hover {
    background: #45a049;
}

.copy-btn:hover {
    background: #a32e18;
}

.payment-method-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    background: #f0f0f0;
    margin-top: 5px;
    color: #080b08;
}

.toast-message {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #333;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    z-index: 10001;
    font-size: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 1024px) {
    .checkout-container {
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .checkout-container {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .checkout-form,
    .order-summary {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .summary-item-img {
        width: 50px;
        height: 50px;
    }

    .account-number {
        font-size: 24px;
    }
}

@media (max-width: 480px) {
    .checkout-container {
        padding: 20px 16px;
    }

    .checkout-form,
    .order-summary {
        padding: 16px;
    }

    .payment-method {
        padding: 10px;
    }

    .method-icon {
        font-size: 24px;
        margin-right: 10px;
    }
}
</style>
@endpush

@section('content')
<div class="checkout-container">
    <form class="checkout-form" id="checkoutForm">
        <div class="form-section">
            <h3>📋 Detail Pemesanan</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" id="fullname" required value="{{ auth()->user()->name ?? '' }}">
                </div>
                <div class="form-group">
                    <label>No. Telepon *</label>
                    <input type="tel" id="phone" required placeholder="081234567890">
                </div>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap *</label>
                <textarea id="address" rows="3" required placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota"></textarea>
            </div>

            <div class="form-group">
                <label>Catatan untuk Dapur (Opsional)</label>
                <textarea id="notes" rows="2" placeholder="Misal: tidak pedas, tambah saus, dll"></textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>💳 Metode Pembayaran</h3>
            <div class="payment-methods">
                <label class="payment-method" data-method="qris">
                    <input type="radio" name="payment_method" value="qris" checked>
                    <div class="method-icon">📱</div>
                    <div class="method-info">
                        <div class="method-name">QRIS</div>
                        <div class="method-desc">Scan QR Code via Gopay, OVO, DANA, LinkAja</div>
                    </div>
                </label>

                <label class="payment-method" data-method="transfer_bca">
                    <input type="radio" name="payment_method" value="transfer_bca">
                    <div class="method-icon">🏦</div>
                    <div class="method-info">
                        <div class="method-name">Transfer Bank BCA</div>
                        <div class="method-desc">a.n Seoul Serenity - 1234567890</div>
                    </div>
                </label>

                <label class="payment-method" data-method="transfer_mandiri">
                    <input type="radio" name="payment_method" value="transfer_mandiri">
                    <div class="method-icon">🏦</div>
                    <div class="method-info">
                        <div class="method-name">Transfer Bank Mandiri</div>
                        <div class="method-desc">a.n Seoul Serenity - 9876543210</div>
                    </div>
                </label>

                <label class="payment-method" data-method="cod">
                    <input type="radio" name="payment_method" value="cod">
                    <div class="method-icon">💵</div>
                    <div class="method-info">
                        <div class="method-name">COD (Cash on Delivery)</div>
                        <div class="method-desc">Bayar saat pesanan sampai</div>
                    </div>
                </label>
            </div>
        </div>
    </form>

    <div class="order-summary">
        <h3 style="margin-bottom: 20px;">🛒 Ringkasan Pesanan</h3>
        <div class="summary-items" id="summaryItems"></div>

        <div class="summary-total">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="summarySubtotal">Rp 0</span>
            </div>
            <div class="summary-row">
                <span>Biaya Pengiriman</span>
                <span>Rp 10.000</span>
            </div>
            <div class="summary-row">
                <span>Pajak (10%)</span>
                <span id="summaryTax">Rp 0</span>
            </div>
            <div class="summary-row total">
                <span>Total Pembayaran</span>
                <span id="summaryTotal">Rp 0</span>
            </div>
        </div>

        <button type="button" class="btn-checkout" onclick="processPayment()">
            🎉 Konfirmasi & Bayar
        </button>
    </div>
</div>

<div id="paymentModal" class="payment-modal">
    <div class="modal-content">
        <div id="modalContent"></div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
// ========== CART STATE ==========
let cart = {};
let uploadedFile = null;
let currentOrder = null;
let savedOrderNumber = null;

// ========== LOAD CART ==========
function loadCart() {
    const saved = localStorage.getItem('seoul_cart');
    if (saved) {
        cart = JSON.parse(saved);
        updateSummary();
    } else {
        alert('Keranjang kosong!');
        window.location.href = '{{ route("customer.menu") }}';
    }
}

// ========== UPDATE SUMMARY DENGAN GAMBAR ==========
function updateSummary() {
    const items = Object.values(cart).filter(i => i.qty > 0);
    const subtotal = items.reduce((sum, i) => sum + (i.price * i.qty), 0);
    const tax = subtotal * 0.1;
    const delivery = 10000;
    const total = subtotal + delivery + tax;

    let itemsHtml = '';
    items.forEach(item => {
        const hasImage = item.image && item.image !== '';
        const imageUrl = hasImage ? `/images/${item.image}` : null;
        
        itemsHtml += `
            <div class="summary-item">
                <div class="summary-item-img">
                    ${hasImage ? `<img src="${imageUrl}" 
                        onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
                        style="width:100%; height:100%; object-fit:cover; border-radius:12px;">` : ''}
                    <div class="emoji-summary" style="display: ${hasImage ? 'none' : 'flex'}; font-size:32px;">
                        ${item.emoji}
                    </div>
                </div>
                <div class="summary-item-info">
                    <div class="summary-item-name">${item.name}</div>
                    <div class="summary-item-qty">${item.qty} x Rp ${item.price.toLocaleString('id-ID')}</div>
                </div>
                <div class="summary-item-price">Rp ${(item.price * item.qty).toLocaleString('id-ID')}</div>
            </div>
        `;
    });

    document.getElementById('summaryItems').innerHTML = itemsHtml;
    document.getElementById('summarySubtotal').innerHTML = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('summaryTax').innerHTML = `Rp ${tax.toLocaleString('id-ID')}`;
    document.getElementById('summaryTotal').innerHTML = `Rp ${total.toLocaleString('id-ID')}`;
}

// ========== PAYMENT METHOD SELECTION ==========
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        const radio = this.querySelector('input');
        radio.checked = true;
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));
        this.classList.add('selected');
    });
});

// ========== GENERATE ORDER NUMBER ==========
function generateOrderNumber() {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    return `SSR-${year}${month}${day}-${random}`;
}

// ========== PROCESS PAYMENT ==========
function processPayment() {
    const fullname = document.getElementById('fullname').value;
    const phone = document.getElementById('phone').value;
    const address = document.getElementById('address').value;

    if (!fullname || !phone || !address) {
        alert('Mohon lengkapi data pemesanan!');
        return;
    }

    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const items = Object.values(cart).filter(i => i.qty > 0);
    const subtotal = items.reduce((sum, i) => sum + (i.price * i.qty), 0);
    const tax = subtotal * 0.1;
    const delivery = 10000;
    const total = subtotal + delivery + tax;
    const orderNumber = generateOrderNumber();

    currentOrder = {
        order_number: orderNumber,
        customer: { name: fullname, phone: phone, address: address },
        items: items,
        subtotal: subtotal,
        tax: tax,
        delivery: delivery,
        total: total,
        payment_method: paymentMethod,
        notes: document.getElementById('notes').value,
        status: 'pending'
    };

    // Tampilkan loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Memproses...';
    btn.disabled = true;

    // Kirim ke database
    fetch('{{ route("customer.checkout.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_number: currentOrder.order_number,
            fullname: currentOrder.customer.name,
            phone: currentOrder.customer.phone,
            address: currentOrder.customer.address,
            payment_method: currentOrder.payment_method,
            notes: currentOrder.notes,
            items: currentOrder.items,
            subtotal: currentOrder.subtotal,
            tax: currentOrder.tax,
            delivery: currentOrder.delivery,
            total: currentOrder.total
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (data.success) {
            savedOrderNumber = data.order?.order_number || currentOrder.order_number;
            showPaymentModal(currentOrder);
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        btn.innerHTML = originalText;
        btn.disabled = false;
        console.error('Error:', error);
        showToast('❌ Terjadi kesalahan: ' + error);
    });
}

// ========== GENERATE QRIS DATA ==========
function generateQRISData(amount, orderNumber) {
    return JSON.stringify({
        merchant: "Seoul Serenity",
        amount: amount,
        order: orderNumber,
        type: "qris"
    });
}

// ========== SHOW PAYMENT MODAL ==========
function showPaymentModal(order) {
    const modal = document.getElementById('paymentModal');
    const modalContent = document.getElementById('modalContent');

    if (order.payment_method === 'qris') {
        const qrisData = generateQRISData(order.total, order.order_number);

        modalContent.innerHTML = `
            <div style="text-align:center;">
                <div style="font-size: 48px;">📱</div>
                <h3>Scan QRIS untuk Membayar</h3>
                <p style="font-size: 12px; color: #666;">Gunakan GoPay, OVO, DANA, atau LinkAja</p>
                <div class="qr-container"><div id="qrcode"></div></div>
                <div class="bank-info">
                    <p><strong>Detail Pembayaran</strong></p>
                    <h2>Rp ${order.total.toLocaleString('id-ID')}</h2>
                    <p>No. Order: ${order.order_number}</p>
                </div>
                <button onclick="confirmQRISPayment('${order.order_number}')" class="confirm-btn">✅ Saya sudah bayar</button>
                <button onclick="closeModal()" class="copy-btn">❌ Tutup</button>
            </div>
        `;
        setTimeout(() => new QRCode(document.getElementById("qrcode"), { text: qrisData, width: 250, height: 250 }), 100);
    } 
    else if (order.payment_method.includes('transfer')) {
        const bank = order.payment_method === 'transfer_bca' ? 'BCA' : 'Mandiri';
        const accountNumber = order.payment_method === 'transfer_bca' ? '1234567890' : '9876543210';

        modalContent.innerHTML = `
            <div style="text-align:center;">
                <div style="font-size: 48px;">🏦</div>
                <h3>Transfer Bank ${bank}</h3>
                <div class="bank-info">
                    <div class="account-number">${accountNumber}</div>
                    <p>a.n PT Seoul Serenity Indonesia</p>
                </div>
                <h2>Rp ${order.total.toLocaleString('id-ID')}</h2>
                <p>No. Order: ${order.order_number}</p>
                <button onclick="copyToClipboard('${accountNumber}')" class="copy-btn">📋 Salin No. Rekening</button>
                <div id="uploadArea" class="upload-area" onclick="document.getElementById('fileInput').click()">
                    📎 Upload Bukti Transfer
                </div>
                <input type="file" id="fileInput" accept="image/*" style="display:none" onchange="handleFileUpload(this, '${order.order_number}')">
                <div id="fileInfo"></div>
                <button id="confirmTransferBtn" class="confirm-btn" onclick="confirmTransferPayment('${order.order_number}')" disabled>✅ Konfirmasi</button>
                <button onclick="closeModal()" class="copy-btn">❌ Tutup</button>
            </div>
        `;
        setupDragAndDrop();
    } 
    else if (order.payment_method === 'cod') {
        modalContent.innerHTML = `
            <div style="text-align:center;">
                <div style="font-size: 48px;">💵</div>
                <h3>Pesanan Berhasil!</h3>
                <p>Total: Rp ${order.total.toLocaleString('id-ID')}</p>
                <p>No. Order: ${order.order_number}</p>
                <p>Bayar saat pesanan tiba</p>
                <button onclick="confirmCODPayment('${order.order_number}')" class="confirm-btn">🎉 Konfirmasi</button>
            </div>
        `;
    }
    modal.classList.add('active');
}

// ========== CONFIRM QRIS PAYMENT ==========
function confirmQRISPayment(orderNumber) {
    showToast('Mengkonfirmasi pembayaran...');
    fetch('/order/confirm/' + orderNumber, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('seoul_cart');
            showToast('✅ Pembayaran berhasil!');
            setTimeout(() => window.location.href = '{{ route("customer.orders") }}', 1500);
        } else {
            showToast('❌ Gagal: ' + data.message);
        }
    });
}

// ========== CONFIRM COD PAYMENT ==========
function confirmCODPayment(orderNumber) {
    showToast('Mengkonfirmasi pesanan...');
    fetch('/order/confirm/' + orderNumber, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('seoul_cart');
            showToast('✅ Pesanan dikonfirmasi!');
            setTimeout(() => window.location.href = '{{ route("customer.orders") }}', 1500);
        } else {
            showToast('❌ Gagal: ' + data.message);
        }
    });
}

// ========== CONFIRM TRANSFER PAYMENT ==========
function confirmTransferPayment(orderNumber) {
    if (!uploadedFile) {
        showToast('Upload bukti transfer dulu!');
        return;
    }
    const formData = new FormData();
    formData.append('proof_image', uploadedFile);
    formData.append('order_number', orderNumber);
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('/order/upload-proof', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            localStorage.removeItem('seoul_cart');
            showToast('✅ Bukti transfer berhasil!');
            setTimeout(() => window.location.href = '{{ route("customer.orders") }}', 1500);
        } else {
            showToast('❌ ' + data.message);
        }
    });
}

// ========== HELPER FUNCTIONS ==========
function setupDragAndDrop() {
    const area = document.getElementById('uploadArea');
    if (!area) return;
    area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('dragover'); });
    area.addEventListener('dragleave', () => area.classList.remove('dragover'));
    area.addEventListener('drop', e => {
        e.preventDefault();
        area.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });
}

function handleFileUpload(input, orderNumber) {
    const file = input.files[0];
    if (file) handleFile(file, orderNumber);
}

function handleFile(file, orderNumber) {
    if (!file.type.startsWith('image/')) {
        showToast('Format file harus gambar!');
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        showToast('Ukuran maksimal 5MB!');
        return;
    }
    uploadedFile = file;
    const info = document.getElementById('fileInfo');
    const btn = document.getElementById('confirmTransferBtn');
    info.innerHTML = `<div class="file-info">📄 ${file.name} <button class="remove-file" onclick="removeFile()">Hapus</button></div>`;
    if (btn) btn.disabled = false;
}

function removeFile() {
    uploadedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').innerHTML = '';
    const btn = document.getElementById('confirmTransferBtn');
    if (btn) btn.disabled = true;
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
    showToast('Nomor rekening disalin!');
}

function showToast(msg) {
    let toast = document.querySelector('.toast-message');
    if (toast) toast.remove();
    toast = document.createElement('div');
    toast.className = 'toast-message';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

function closeModal() {
    document.getElementById('paymentModal').classList.remove('active');
    uploadedFile = null;
}

document.getElementById('paymentModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

loadCart();
</script>
@endpush
@endsection