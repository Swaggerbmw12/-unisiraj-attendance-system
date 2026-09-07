<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2><i class="bi bi-qr-code-scan"></i> Scan QR Code</h2>
            <p class="text-muted">Scan the QR code displayed by your lecturer to record attendance</p>
        </div>
    </div>
    
    <!-- Scanning Instructions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h5><i class="bi bi-info-circle"></i> How to Record Attendance</h5>
                <ol class="mb-0">
                    <li>Click "Start Camera" to activate your device camera</li>
                    <li>Point your camera at the QR code displayed by your lecturer</li>
                    <li>The system will automatically detect and submit your attendance</li>
                    <li>You can only record attendance once per session</li>
                </ol>
            </div>
        </div>
    </div>
    
    <!-- Main Scan Interface -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-qr-code"></i> Attendance QR Code</h5>
                </div>
                <div class="card-body">
                    <!-- Camera Scanner -->
                    <div class="text-center mb-4">
                        <div id="qr-reader" style="width: 100%; display: none;"></div>
                        
                        <div id="scanner-placeholder" class="qr-scanner-placeholder bg-light p-5 rounded" style="min-height: 300px; display: flex; align-items: center; justify-content: center; border: 3px dashed #dee2e6;">
                            <div>
                                <i class="bi bi-camera" style="font-size: 100px; color: #6c757d;"></i>
                                <p class="text-muted mt-3 mb-2">Click below to start camera</p>
                                <button type="button" class="btn btn-primary btn-lg" id="start-scanner">
                                    <i class="bi bi-camera-video"></i> Start Camera
                                </button>
                            </div>
                        </div>
                        
                        <div id="scanner-status" class="mt-3" style="display: none;">
                            <button type="button" class="btn btn-danger" id="stop-scanner">
                                <i class="bi bi-stop-circle"></i> Stop Camera
                            </button>
                        </div>
                    </div>
                    
                    <!-- Manual Token Entry -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-pencil"></i> Manual Entry</h6>
                            <p class="text-muted small">If scanning doesn't work, enter the attendance code manually</p>
                            
                            <form method="POST" action="<?= url('student/attendance/verify') ?>" id="scanForm">
                                <div class="mb-3">
                                    <label for="token" class="form-label">Attendance Token/Code</label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="token" 
                                           name="token" 
                                           placeholder="Paste or type the attendance code here"
                                           required>
                                    <div class="form-text">
                                        The code is shown on the lecturer's screen or in the QR code URL
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle"></i> Submit Attendance
                                    </button>
                                    <a href="<?= url('student/dashboard') ?>" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left"></i> Back to Dashboard
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tips Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-lightbulb text-warning"></i> Tips for Success</h6>
                    <ul class="mb-0 small">
                        <li>Ensure good lighting when scanning</li>
                        <li>Hold your device steady for best results</li>
                        <li>Make sure you're enrolled in the course</li>
                        <li>Check your attendance history after submitting</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-shield-check text-success"></i> Security Notice</h6>
                    <ul class="mb-0 small">
                        <li>Each QR code is unique and time-limited</li>
                        <li>You cannot record attendance twice for the same session</li>
                        <li>Expired or invalid codes will be rejected</li>
                        <li>Your IP address and device info are logged</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.qr-scanner-placeholder {
    transition: all 0.3s ease;
}

#qr-reader {
    border: 3px solid #0d6efd;
    border-radius: 8px;
    overflow: hidden;
}

#qr-reader video {
    border-radius: 5px;
}

#scanForm {
    animation: fadeIn 0.5s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes scan-line {
    0% { transform: translateY(0); }
    100% { transform: translateY(300px); }
}
</style>

<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrCode = null;
let isScanning = false;

// Start Scanner
document.getElementById('start-scanner').addEventListener('click', function() {
    startScanner();
});

// Stop Scanner
document.getElementById('stop-scanner').addEventListener('click', function() {
    stopScanner();
});

function startScanner() {
    if (isScanning) return;
    
    const qrReader = document.getElementById('qr-reader');
    const placeholder = document.getElementById('scanner-placeholder');
    const statusDiv = document.getElementById('scanner-status');
    
    // Show reader, hide placeholder
    qrReader.style.display = 'block';
    placeholder.style.display = 'none';
    statusDiv.style.display = 'block';
    
    html5QrCode = new Html5Qrcode("qr-reader");
    
    const config = {
        fps: 10,
        qrbox: { width: 250, height: 250 },
        aspectRatio: 1.0
    };
    
    html5QrCode.start(
        { facingMode: "environment" }, // Use back camera
        config,
        onScanSuccess,
        onScanError
    ).then(() => {
        isScanning = true;
        console.log("QR Code scanner started successfully");
    }).catch(err => {
        console.error("Unable to start scanner:", err);
        alert("Unable to access camera. Please check camera permissions or try manual entry.");
        stopScanner();
    });
}

function stopScanner() {
    if (!html5QrCode || !isScanning) return;
    
    html5QrCode.stop().then(() => {
        isScanning = false;
        document.getElementById('qr-reader').style.display = 'none';
        document.getElementById('scanner-placeholder').style.display = 'flex';
        document.getElementById('scanner-status').style.display = 'none';
        console.log("QR Code scanner stopped");
    }).catch(err => {
        console.error("Error stopping scanner:", err);
    });
}

function onScanSuccess(decodedText, decodedResult) {
    console.log("QR Code detected:", decodedText);
    
    // Stop scanner immediately
    stopScanner();
    
    // Check if it's a URL or just a token
    let token = '';
    
    if (decodedText.includes('/student/attendance/scan?token=')) {
        // Extract token from URL
        const url = new URL(decodedText);
        token = url.searchParams.get('token');
    } else if (decodedText.includes('token=')) {
        // Extract token from query string
        const params = new URLSearchParams(decodedText.split('?')[1]);
        token = params.get('token');
    } else {
        // Assume the entire string is the token
        token = decodedText;
    }
    
    if (token) {
        // Fill the form and submit
        document.getElementById('token').value = token;
        
        // Show success message
        const scanForm = document.getElementById('scanForm');
        const submitBtn = scanForm.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Verifying...';
        submitBtn.disabled = true;
        
        // Auto-submit
        setTimeout(() => {
            scanForm.submit();
        }, 500);
    } else {
        alert("Invalid QR code. Please try again or use manual entry.");
        startScanner();
    }
}

function onScanError(errorMessage) {
    // Scanning errors are normal (QR not in view), don't log
    // console.warn("Scan error:", errorMessage);
}

// Manual form submission
document.getElementById('scanForm').addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    if (!btn.disabled) {
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Verifying...';
    }
});

// Auto-fill token from URL parameter (for QR code redirects)
const urlParams = new URLSearchParams(window.location.search);
const tokenFromUrl = urlParams.get('token');
if (tokenFromUrl) {
    document.getElementById('token').value = tokenFromUrl;
    // Show notification that token was detected
    const alert = document.createElement('div');
    alert.className = 'alert alert-success alert-dismissible fade show';
    alert.innerHTML = `
        <i class="bi bi-check-circle"></i> Attendance code detected from QR scan!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.container-fluid').firstChild);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (isScanning) {
        stopScanner();
    }
});
</script>
