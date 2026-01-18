<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Smart Scale Interface</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f2f5;
            height: 100vh;
            overflow: hidden;
            /* Mencegah scroll di mode kiosk */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-container {
            height: 100vh;
            padding: 15px;
        }

        .card-custom {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* --- STYLING TOMBOL PILIHAN --- */
        .btn-select {
            border: 2px solid #dee2e6;
            background-color: #fff;
            color: #555;
            border-radius: 12px;
            padding: 15px 10px;
            font-weight: bold;
            transition: all 0.2s;
            width: 100%;
            margin-bottom: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 85px;
            /* Tinggi seragam */
        }

        .btn-select i {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        /* State Aktif */
        .btn-select.active-male {
            background-color: #e3f2fd;
            border-color: #2196f3;
            color: #2196f3;
        }

        .btn-select.active-female {
            background-color: #fce4ec;
            border-color: #e91e63;
            color: #e91e63;
        }

        .btn-select.active-act {
            background-color: #e8f5e9;
            border-color: #4caf50;
            color: #4caf50;
        }

        /* --- STYLING NUMPAD --- */
        .numpad-display {
            font-size: 2rem;
            text-align: center;
            background: #eee;
            border: none;
            border-radius: 10px;
            height: 60px;
            font-weight: bold;
            color: #333;
        }

        .btn-numpad {
            height: 60px;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 2px;
            border-radius: 10px;
        }

        /* --- STYLING HASIL --- */
        .result-box {
            background: #2c3e50;
            color: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 15px;
        }

        .result-value {
            font-size: 3.5rem;
            font-weight: bold;
            line-height: 1;
        }

        .result-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
        }

        .detail-item {
            border-bottom: 1px solid #eee;
            padding: 8px 0;
            font-size: 1.1rem;
        }

        .detail-item span {
            font-weight: bold;
            float: right;
            color: #2c3e50;
        }

        .btn-start {
            height: 70px;
            font-size: 1.8rem;
            border-radius: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* Responsif Landscape Adjustment */
        .section-title {
            font-size: 1.1rem;
            color: #888;
            margin-bottom: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="container-fluid main-container">
        <div class="row h-100 g-3">

            <div class="col-lg-7 col-md-7 h-100">
                <div class="card-custom p-4">

                    <div class="row h-100">
                        <div class="col-6 d-flex flex-column justify-content-between">

                            <div>
                                <div class="section-title"><i class="fas fa-venus-mars"></i> Jenis Kelamin</div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button class="btn btn-select" id="btn-pria" onclick="selectGender('L')">
                                            <i class="fas fa-mars"></i> Pria
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-select" id="btn-wanita" onclick="selectGender('P')">
                                            <i class="fas fa-venus"></i> Wanita
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="section-title"><i class="fas fa-running"></i> Aktivitas</div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <button class="btn btn-select" id="act-ringan" onclick="selectActivity('ringan')">
                                            <i class="fas fa-bed"></i> Ringan
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-select" id="act-sedang" onclick="selectActivity('sedang')">
                                            <i class="fas fa-walking"></i> Sedang
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-select" id="act-rutin" onclick="selectActivity('rutin')">
                                            <i class="fas fa-dumbbell"></i> Rutin
                                        </button>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-select" id="act-berat" onclick="selectActivity('berat')">
                                            <i class="fas fa-fire"></i> Berat
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-6 d-flex flex-column">
                            <div class="section-title"><i class="fas fa-birthday-cake"></i> Umur (Tahun)</div>

                            <input type="text" id="umur-display" class="form-control numpad-display mb-2" readonly placeholder="0">

                            <div class="row g-1 mb-auto">
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(7)">7</button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(8)">8</button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(9)">9</button></div>

                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(4)">4</button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(5)">5</button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(6)">6</button></div>

                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(1)">1</button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(2)">2</button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(3)">3</button></div>

                                <div class="col-4"><button class="btn btn-danger w-100 btn-numpad" onclick="padClear()"><i class="fas fa-trash"></i></button></div>
                                <div class="col-4"><button class="btn btn-secondary w-100 btn-numpad" onclick="padInput(0)">0</button></div>
                                <div class="col-4"><button class="btn btn-warning w-100 btn-numpad" onclick="padDel()"><i class="fas fa-backspace"></i></button></div>
                            </div>

                            <button onclick="start()" class="btn btn-success w-100 btn-start mt-3 shadow">
                                <i class="fas fa-play-circle"></i> MULAI
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-5 col-md-5 h-100">
                <div class="card-custom p-4 bg-light">
                    <h4 class="text-center fw-bold mb-4 text-secondary">HASIL PENGUKURAN</h4>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="result-box bg-primary">
                                <div class="result-value"><span id="tinggi">0</span></div>
                                <div class="result-label">Tinggi (cm)</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="result-box bg-success">
                                <div class="result-value"><span id="berat">0</span></div>
                                <div class="result-label">Berat (kg)</div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded shadow-sm flex-grow-1">
                        <h5 class="fw-bold border-bottom pb-2 mb-3"><i class="fas fa-clipboard-list"></i> Analisa Kesehatan</h5>

                        <div class="detail-item">Status Tubuh <span id="status" class="badge bg-secondary fs-6">-</span></div>
                        <div class="detail-item">Berat Ideal (BBI) <span id="bbi">0 Kg</span></div>
                        <div class="detail-item">Kalori Harian <span id="kalori">0 Kkal</span></div>
                        <div class="detail-item">Target <span id="tujuan">-</span></div>
                        <div class="detail-item">Estimasi Waktu <span id="durasi">0 Minggu</span></div>

                        <div class="mt-3 bg-white p-3 rounded shadow-sm">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-envelope"></i> Kirim Hasil ke Email
                            </h6>

                            <input type="email" id="emailTujuan" class="form-control mb-2"
                                placeholder="Masukkan email pengguna">

                            <button class="btn btn-primary w-100 fw-bold" onclick="sendEmail()">
                                <i class="fas fa-envelope"></i> KIRIM HASIL
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // --- kirim email--
        function sendEmail() {
            const email = document.getElementById("emailTujuan").value;

            if (!email) {
                alert("Masukkan alamat email!");
                return;
            }

            if (!sesi_id) {
                alert("Belum ada hasil pengukuran!");
                return;
            }

            const payload = {
                sesi_id: sesi_id,
                email: email,
                tinggi: document.getElementById("tinggi").innerText,
                berat: document.getElementById("berat").innerText,
                status: document.getElementById("status").innerText,
                bbi: document.getElementById("bbi").innerText,
                kalori: document.getElementById("kalori").innerText,
                tujuan: document.getElementById("tujuan").innerText,
                durasi: document.getElementById("durasi").innerText
            };

            fetch("send_email.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        alert("Hasil berhasil dikirim ke email!");
                        document.getElementById("emailTujuan").value = "";
                    } else {
                        alert("Gagal mengirim email");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Error koneksi email server");
                });
        }


        // --- VARIABLE STATE ---
        let selectedGender = null;
        let selectedActivity = null;
        let sesi_id = null;
        let currentAge = "";


        // --- FUNGSI UI INTERAKTIF ---

        function selectGender(val) {
            selectedGender = val;
            // Reset UI
            document.getElementById('btn-pria').className = 'btn btn-select';
            document.getElementById('btn-wanita').className = 'btn btn-select';

            // Set Active
            if (val === 'L') {
                document.getElementById('btn-pria').classList.add('active-male');
            } else {
                document.getElementById('btn-wanita').classList.add('active-female');
            }
        }

        function selectActivity(val) {
            selectedActivity = val;
            // Reset UI
            ['ringan', 'sedang', 'rutin', 'berat'].forEach(act => {
                document.getElementById('act-' + act).className = 'btn btn-select';
            });
            // Set Active
            document.getElementById('act-' + val).classList.add('active-act');
        }

        // --- FUNGSI NUMPAD ---
        function padInput(num) {
            if (currentAge.length < 3) { // Max 3 digit
                currentAge += num;
                document.getElementById('umur-display').value = currentAge;
            }
        }

        function padDel() {
            currentAge = currentAge.slice(0, -1);
            document.getElementById('umur-display').value = currentAge;
        }

        function padClear() {
            currentAge = "";
            document.getElementById('umur-display').value = "";
        }

        // --- LOGIKA UTAMA ---

        async function start() {
            // Validasi Input
            if (!selectedGender) return alert("Pilih Jenis Kelamin!");
            if (!selectedActivity) return alert("Pilih Jenis Aktivitas!");
            if (!currentAge || currentAge == "0") return alert("Masukkan Umur!");

            const data = new FormData();
            // Nama kita set default "Guest" atau kosong karena inputnya dibuang
            data.append("nama", "User");
            data.append("umur", currentAge);
            data.append("jk", selectedGender);
            data.append("aktivitas", selectedActivity);

            try {
                // Button loading state
                const btn = document.querySelector('.btn-start');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                btn.disabled = true;

                const res = await fetch("start.php", {
                    method: "POST",
                    body: data
                });

                const text = await res.text();
                console.log("RAW RESPONSE:", text);

                // Cek jika response kosong atau error php
                try {
                    const json = JSON.parse(text);
                    if (json.sesi_id) {
                        sesi_id = json.sesi_id;
                        // Feedback Audio/Visual bisa ditaruh disini

                        // Reset Tampilan Hasil
                        resetResults();

                        // Connect Websocket
                        listenCalculation();
                    }
                } catch (e) {
                    console.error("JSON Parse Error", e);
                    alert("Error dari server (Invalid JSON)");
                }

                // Restore button
                btn.innerHTML = originalText;
                btn.disabled = false;

            } catch (err) {
                console.error("ERROR START:", err);
                alert("Gagal koneksi ke server");
                document.querySelector('.btn-start').disabled = false;
            }
        }

        function resetResults() {
            document.getElementById("tinggi").innerText = "0";
            document.getElementById("berat").innerText = "0";
            document.getElementById("status").innerText = "-";
            document.getElementById("status").className = "badge bg-secondary fs-6";
            document.getElementById("bbi").innerText = "0 Kg";
            document.getElementById("kalori").innerText = "0 Kkal";
            document.getElementById("tujuan").innerText = "-";
            document.getElementById("durasi").innerText = "0 Minggu";
        }

        // ==== WebSocket Realtime ====


        function websocketOpen(tipe) {
            //  debugger
            let ws = null;
            console.log("WebSocket start");
            // GANTI IP INI SESUAI SERVER ANDA
            ws = new WebSocket("ws://192.168.101.250:5000");
            ws.onopen = () => {
                console.log("WebSocket connected");
                // Kirim subscribe sesi_id
                ws.send(JSON.stringify({
                    action: "subscribe",
                    sesi_id: tipe
                }));
            };
            return ws;
        }

        function websocketClose() {
            if (ws) {
                ws.close();
                ws = null;
            }
        }

        async function listenDefault() {
            if (sesi_id) {
                listenCalculation()
            } else {
                let ws = await websocketOpen("default");
                ws.onmessage = (event) => {
                    const data = JSON.parse(event.data);
                    if (data.tinggi !== undefined) document.getElementById("tinggi").innerText = data.tinggi;
                    if (data.berat !== undefined) document.getElementById("berat").innerText = data.berat;
                    if (data.status !== undefined) {
                        const el = document.getElementById("status");
                        el.innerText = data.status;
                        if (data.status === 'Ideal') el.className = "badge bg-success fs-6";
                        else if (data.status === 'Obesitas') el.className = "badge bg-danger fs-6";
                        else if (data.status === 'Overweight') el.className = "badge bg-warning text-dark fs-6";
                        else el.className = "badge bg-info text-dark fs-6";
                    }
                    if (data.tujuan !== undefined) document.getElementById("tujuan").innerText = data.tujuan;
                };

                ws.onclose = () => {
                    console.log("WebSocket disconnected, reconnect in 2s");
                    setTimeout(listenDefault, 500);
                };

                ws.onerror = (err) => {
                    websocketClose();
                };
            }
        }

        function listenCalculation() {
            if (!sesi_id) return;

            // GANTI IP INI SESUAI SERVER ANDA
            let ws = websocketOpen(sesi_id)

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                if (data.sesi_id !== sesi_id) return;
                if (data.tinggi !== undefined) document.getElementById("tinggi").innerText = data.tinggi;
                if (data.berat !== undefined) document.getElementById("berat").innerText = data.berat;

                if (data.status !== undefined) {
                    const el = document.getElementById("status");
                    el.innerText = data.status;
                    if (data.status === 'Ideal') el.className = "badge bg-success fs-6";
                    else if (data.status === 'Obesitas') el.className = "badge bg-danger fs-6";
                    else if (data.status === 'Overweight') el.className = "badge bg-warning text-dark fs-6";
                    else el.className = "badge bg-info text-dark fs-6";
                }

                if (data.bbi !== undefined) document.getElementById("bbi").innerText = data.bbi + " Kg";
                if (data.kalori !== undefined) document.getElementById("kalori").innerText = data.kalori + " Kkal";
                if (data.tujuan !== undefined) document.getElementById("tujuan").innerText = data.tujuan;
                if (data.durasi !== undefined) document.getElementById("durasi").innerText = data.durasi + " Minggu";
            };

            ws.onclose = () => {
                console.log("WebSocket disconnected");
            };
        }
        document.addEventListener('DOMContentLoaded', function() {
            listenDefault()
        });
    </script>

</body>

</html>