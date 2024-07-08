<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=vitri&action=index">Vị Trí</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sửa Vị Trí</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
    <div id="alert-message" class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <script>
        setTimeout(function() {
            $('#alert-message').alert('close');
        }, 5000);
    </script>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Sửa Vị Trí</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="index.php?model=vitri&action=edit&id=<?= $viTri['vi_tri_id']; ?>" id="vitriForm">
                <div class="form-group">
                    <label for="ten_vi_tri">Tên Vị Trí</label>
                    <input type="text" class="form-control" id="ten_vi_tri" name="ten_vi_tri" value="<?= htmlspecialchars($viTri['ten_vi_tri']); ?>" required>
                </div>

                <h5 class="mt-4">Chi Tiết Vị Trí</h5>
                <div class="table-responsive">
                    <table id="tableChiTiet" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Loại tài sản</th>
                                <th>Tên Tài Sản</th>
                                <th>Số Lượng Trong Kho</th>
                                <th>Số Lượng Chuyển</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($viTriChiTietsOld as $index => $detail): ?>
                                <tr id="row<?= $index; ?>">
                                    <td>
                                        <input type="text" class="form-control" name="loai_tai_san[]" value="<?= htmlspecialchars($detail['ten_loai_tai_san']); ?>" readonly>
                                        <input type="hidden" name="loai_tai_san_id[]" value="<?= $detail['loai_tai_san_id']; ?>">
                                    </td>
                                    <td>
                                        <select class="form-control select-tai-san" name="tai_san_id[]" required>
                                            <option value="">Chọn tài sản</option>
                                            <?php foreach ($taiSans as $taiSan): ?>
                                                <option value="<?= $taiSan['tai_san_id']; ?>" 
                                                        <?= ($taiSan['tai_san_id'] == $detail['tai_san_id']) ? 'selected' : ''; ?> 
                                                        data-loai-tai-san="<?= htmlspecialchars($taiSan['ten_loai_tai_san']); ?>" 
                                                        data-loai-tai-san-id="<?= $taiSan['loai_tai_san_id']; ?>">
                                                    <?= htmlspecialchars($taiSan['ten_tai_san']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control so-luong-kho" min="0" name="so_luong_kho[]" value="<?= $detail['so_luong_kho'] ?? 0; ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control so-luong-chuyen" name="so_luong_chuyen[]" value="<?= $detail['so_luong'] ?? 0; ?>" required min="0">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="xoaDong(this)">Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-primary mt-2" onclick="themDong()">Thêm Dòng</button>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">Lưu Thay Đổi</button>
                    <a href="index.php?model=vitri&action=index" class="btn btn-secondary">Quay Lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initializeTaiSanListeners() {
    document.querySelectorAll('.select-tai-san').forEach(function(select) {
        select.addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var row = this.closest('tr');
            var loaiTaiSanInput = row.querySelector('input[name="loai_tai_san[]"]');
            var loaiTaiSanIdInput = row.querySelector('input[name="loai_tai_san_id[]"]');

            if (selectedOption) {
                loaiTaiSanInput.value = selectedOption.getAttribute('data-loai-tai-san');
                loaiTaiSanIdInput.value = selectedOption.getAttribute('data-loai-tai-san-id');
            } else {
                loaiTaiSanInput.value = '';
                loaiTaiSanIdInput.value = '';
            }

            // Cập nhật số lượng trong kho
            fetchQuantityInStock(this.value);
        });
    });
}

function fetchQuantityInStock(taiSanId) {
    if (taiSanId) {
        $.ajax({
            url: 'index.php?model=vitri&action=getQuantityInStock',
            method: 'POST',
            data: { tai_san_id: taiSanId},
            dataType: 'json',
            success: function(response) {
                // Find the select element with the matching selected value
                var selectElement = document.querySelector('select[name="tai_san_id[]"] option[value="' + taiSanId + '"]:checked').closest('select');
                
                // Get the closest row and find the so-luong-kho input within it
                var row = selectElement.closest('tr');
                var soLuongKhoInput = row.querySelector('.so-luong-kho');
                
                soLuongKhoInput.value = response.quantity;
            },
            error: function(xhr, status, error) {
                console.error("AJAX error: " + status + ": " + error);
            }
        });
    }
}

function themDong() {
    var table = document.getElementById('tableChiTiet').getElementsByTagName('tbody')[0];
    var rowCount = table.rows.length;
    var row = table.insertRow(rowCount);
    row.id = 'row' + rowCount;

    var loaiTaiSanCell = row.insertCell(0);
    var taiSanCell = row.insertCell(1);
    var soLuongKhoCell = row.insertCell(2);
    var soLuongChuyenCell = row.insertCell(3);
    var hanhDongCell = row.insertCell(4);

    loaiTaiSanCell.innerHTML = '<input type="text" class="form-control" name="loai_tai_san[]" readonly>' +
        '<input type="hidden" name="loai_tai_san_id[]">';

    taiSanCell.innerHTML = '<select class="form-control select-tai-san" name="tai_san_id[]" required>' +
        '<option value="">Chọn tài sản</option>' +
        <?php foreach ($taiSans as $taiSan): ?>
        '<option value="<?= $taiSan['tai_san_id']; ?>" data-loai-tai-san="<?= htmlspecialchars($taiSan['ten_loai_tai_san']); ?>" data-loai-tai-san-id="<?= $taiSan['loai_tai_san_id']; ?>"><?= htmlspecialchars($taiSan['ten_tai_san']); ?></option>' +
        <?php endforeach; ?>
        '</select>';

    soLuongKhoCell.innerHTML = '<input type="number" class="form-control so-luong-kho" name="so_luong_kho[]" readonly>';

    soLuongChuyenCell.innerHTML = '<input type="number" class="form-control so-luong-chuyen" name="so_luong_chuyen[]" required min="0">';

    hanhDongCell.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="xoaDong(this)">Xóa</button>';

    initializeTaiSanListeners()
}

function xoaDong(button) {
    var row = button.closest('tr');
    row.parentNode.removeChild(row);
}

document.addEventListener('DOMContentLoaded', initializeTaiSanListeners);
</script>
