<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>LocalStorage Viewer</title>
    <?php include 'views/meta.php' ?>
    <style>
        .storage-card{max-width:800px;margin:30px auto}
        .storage-item{border:1px solid #ddd;margin:10px 0;padding:10px;border-radius:5px}
        .key{font-weight:bold;color:#007bff}
        .value{color:#28a745;word-break:break-all}
        .clear-btn{margin:10px 0}
        .info-box{background:#e9ecef;padding:15px;border-radius:5px;margin:20px 0}
    </style>
</head>
<body>
<?php include 'views/header.php'?>

<div class="container storage-card">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>LocalStorage Viewer - Xem dữ liệu đã lưu</h3>
        </div>
        <div class="panel-body">
            <div class="info-box">
                <h4>Thông tin:</h4>
                <p><strong>LocalStorage</strong> lưu trữ dữ liệu trong trình duyệt, dữ liệu sẽ không mất khi đóng trình duyệt.</p>
                <p>Dữ liệu được lưu khi bạn chọn "Remember Me" trong form đăng nhập.</p>
            </div>

            <div class="clear-btn">
                <button id="clear-all" class="btn btn-danger">Xóa tất cả localStorage</button>
                <button id="refresh" class="btn btn-primary">Làm mới</button>
            </div>

            <div id="storage-content">
                <!-- Dữ liệu localStorage sẽ được hiển thị ở đây -->
            </div>

            <div id="empty-message" style="display:none;" class="alert alert-info">
                <strong>Không có dữ liệu nào trong localStorage!</strong><br>
                Hãy đăng nhập và chọn "Remember Me" để lưu dữ liệu.
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Hướng dẫn sử dụng:</h4>
        </div>
        <div class="panel-body">
            <ol>
                <li>Vào trang <a href="login.php">Login</a></li>
                <li>Nhập tên đăng nhập và mật khẩu</li>
                <li>Chọn checkbox "Remember Me (Lưu vào localStorage)"</li>
                <li>Nhấn Submit</li>
                <li>Quay lại trang này để xem dữ liệu đã được lưu</li>
            </ol>
            <p><strong>Lưu ý:</strong> Dữ liệu trong localStorage sẽ được tự động điền vào form đăng nhập lần sau.</p>
        </div>
    </div>
</div>

<script>
function displayLocalStorage() {
    const container = document.getElementById('storage-content');
    const emptyMessage = document.getElementById('empty-message');
    const hasData = localStorage.length > 0;
    
    if (!hasData) {
        container.innerHTML = '';
        emptyMessage.style.display = 'block';
        return;
    }
    
    emptyMessage.style.display = 'none';
    let html = '';
    
    for (let i = 0; i < localStorage.length; i++) {
        const key = localStorage.key(i);
        const value = localStorage.getItem(key);
        
        html += `
            <div class="storage-item">
                <div class="key">Key: ${key}</div>
                <div class="value">Value: ${value}</div>
                <button class="btn btn-sm btn-warning remove-item" data-key="${key}">Xóa</button>
            </div>
        `;
    }
    
    container.innerHTML = html;
    
    // Add event listeners for remove buttons
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const key = this.getAttribute('data-key');
            localStorage.removeItem(key);
            displayLocalStorage();
        });
    });
}

// Clear all localStorage
document.getElementById('clear-all').addEventListener('click', function() {
    if (confirm('Bạn có chắc muốn xóa tất cả dữ liệu localStorage?')) {
        localStorage.clear();
        displayLocalStorage();
        alert('Đã xóa tất cả dữ liệu localStorage!');
    }
});

// Refresh display
document.getElementById('refresh').addEventListener('click', function() {
    displayLocalStorage();
});

// Initial display
displayLocalStorage();

// Listen for storage changes from other tabs
window.addEventListener('storage', function(e) {
    displayLocalStorage();
});
</script>

</body>
</html>

