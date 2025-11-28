document.addEventListener("DOMContentLoaded", function () {
    const btnOpen   = document.getElementById("btn-open-sku-scanner");
    const btnClose  = document.getElementById("btn-close-sku-scanner");
    const card      = document.getElementById("sku-scanner-card");
    const skuInput  = document.getElementById("sku-input");
    const qrDivId   = "qr-reader-sku";

    if (!btnOpen || !card || !skuInput) {
        // elemen tidak ada, tidak usah jalanin apa-apa
        return;
    }

    let scanner = null; // instance Html5QrcodeScanner

    function startScanner() {
        if (scanner || !window.Html5QrcodeScanner) {
            if (!window.Html5QrcodeScanner) {
                alert("Library scanner belum dimuat.");
            }
            return;
        }

        scanner = new Html5QrcodeScanner(qrDivId, {
            fps: 10,
            qrbox: { width: 250, height: 250 },
        });

        scanner.render(
            function onScanSuccess(decodedText) {
                // isi SKU dengan hasil scan
                const kode = (decodedText || "").trim();
                if (kode) {
                    skuInput.value = kode;
                }

                // stop scanner & sembunyikan card
                scanner.clear()
                    .then(() => {
                        scanner = null;
                        card.classList.add("d-none");
                    })
                    .catch(err => {
                        console.error("Gagal clear scanner:", err);
                        card.classList.add("d-none");
                    });
            },
            function onScanError(errorMessage) {
                // boleh diabaikan supaya tidak spam log
                // console.warn(errorMessage);
            }
        );
    }

    btnOpen.addEventListener("click", function () {
        card.classList.remove("d-none");
        startScanner();
    });

    btnClose.addEventListener("click", function () {
        card.classList.add("d-none");
        if (scanner) {
            scanner.clear()
                .then(() => { scanner = null; })
                .catch(err => console.error("Gagal clear scanner:", err));
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    function formatRupiah(angka) {
        // ambil hanya digit
        angka = angka.replace(/[^0-9]/g, '');
        if (!angka) return '';

        // pasang titik setiap 3 digit dari belakang
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // format saat user mengetik
    document.querySelectorAll('.rupiah-input').forEach(function (input) {
        input.addEventListener('input', function (e) {
            const cursorPos = input.selectionStart;
            const before = input.value;

            const formatted = formatRupiah(before);
            input.value = formatted;

            // (optional) biar kursor nggak selalu lompat ke akhir
            const diff = before.length - formatted.length;
            input.setSelectionRange(cursorPos - diff, cursorPos - diff);
        });
    });

    // sebelum submit, buang titik dulu → kirim angka polos ke backend
    const forms = document.querySelectorAll('form');
    forms.forEach(function (form) {
        form.addEventListener('submit', function () {
            document.querySelectorAll('.rupiah-input').forEach(function (input) {
                input.value = input.value.replace(/\./g, '');
            });
        });
    });
});