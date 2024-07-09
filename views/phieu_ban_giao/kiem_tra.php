<div class="container-fluid">
<?php if (isset($_SESSION['message'])): ?>
        <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; ?>
        </div>
        <?php
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <script>
            setTimeout(function() {
                var alert = document.getElementById('alert-message');
                if (alert) {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 150); // Optional: wait for the fade-out transition to complete
                }
            }, 2000); // 2000 milliseconds = 2 seconds
        </script>
    <?php endif; ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Kiểm tra phiếu bàn giao tài sản</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Người tạo yêu cầu:</strong> <?= htmlspecialchars($nguoiNhan['ten']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Ngày tạo phiếu:</strong> <?= date('d/m/Y', strtotime($phieuBanGiao['ngay_gui'])); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Vị trí:</strong> <?= htmlspecialchars($viTri['ten_vi_tri']); ?>
                </div>
                <div class="col-md-6">
                    <strong>Người kiểm tra:</strong> <?= htmlspecialchars($_SESSION['ten']); ?>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <strong>Ghi chú:</strong> <?= nl2br(htmlspecialchars($phieuBanGiao['ghi_chu'])); ?>
                </div>
            </div>

            <h5 class="mt-4">Danh sách tài sản yêu cầu:</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Loại tài sản</th>
                        <th>Tên tài sản</th>
                        <th>Số lượng yêu cầu</th>
                        <th>Số lượng trong kho</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chiTietWithAdditionalData as $chiTiet): ?>
                        <tr>
                            <td><?= htmlspecialchars($chiTiet['ten_loai_tai_san']); ?></td>
                            <td><?= htmlspecialchars($chiTiet['ten_tai_san']); ?></td>
                            <td><?= htmlspecialchars($chiTiet['so_luong']); ?></td>
                            <td><?= htmlspecialchars($chiTiet['so_luong_trong_kho']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form action="index.php?model=phieubangiao&action=kiem_tra&id=<?= $phieuBanGiao['phieu_ban_giao_id']; ?>" method="POST" class="mt-4">
                <button type="submit" name="action" value="gui" class="btn btn-primary">Gửi phê duyệt</button>
                <button type="submit" name="action" value="huy" class="btn btn-danger">Hủy phiếu</button>
                <a href="index.php?model=phieubangiao&action=index" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>