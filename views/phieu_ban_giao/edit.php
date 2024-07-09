<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Chỉnh sửa phiếu bàn giao tài sản</h6>
        </div>
        <div class="card-body">
            <form method="POST"
                action="index.php?model=phieubangiao&action=edit&id=<?= $phieuBanGiao['phieu_ban_giao_id']; ?>"
                id="phieuBanGiaoForm">
                <!-- Các trường thông tin chung của phiếu bàn giao -->
                <div class="form-group row">
                    <label for="nguoiNhan" class="col-sm-2 col-form-label">Người tạo yêu cầu:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nguoiNhan" name="nguoi_nhan"
                            value="<?= $user_nhan['ten']; ?>" readonly>
                        <input type="hidden" name="user_nhan_id" value="<?= $user_nhan['user_id']; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="ngayTao" class="col-sm-2 col-form-label">Ngày tạo phiếu:</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="ngayTao" name="ngay_tao"
                            value="<?= $phieuBanGiao['ngay_gui']; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="viTri" class="col-sm-2 col-form-label">Vị trí:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="viTri" name="vi_tri_id" required>
                            <option value="">Chọn vị trí</option>
                            <?php foreach ($vi_tri_list as $vi_tri): ?>
                                <?php if ($vi_tri['khoa'] === $user_nhan['khoa']): ?>
                                    <option value="<?= $vi_tri['vi_tri_id']; ?>"
                                        <?= ($vi_tri['vi_tri_id'] == $phieuBanGiao['vi_tri_id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($vi_tri['ten_vi_tri']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <h5 class="mt-4">Yêu cầu cung cấp các tài sản:</h5>
                <table id="chiTietTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Loại tài sản</th>
                            <th>Tên tài sản</th>
                            <th>Số lượng</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chiTietPhieuBanGiaoWithDetails as $chiTiet): ?>
                            <tr>
                                <td>
                                    <select class="form-control loai-tai-san" name="loai_tai_san_id[]" required>
                                        <option value="">Chọn loại tài sản</option>
                                        <?php $loai_tai_san_list = $this->loaiTaiSanModel->readAll(); ?>
                                        <?php foreach ($loai_tai_san_list as $loai): ?>
                                            <option value="<?= $loai['loai_tai_san_id']; ?>"
                                                <?= ($loai['loai_tai_san_id'] == $chiTiet['loai_tai_san_id']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($loai['ten_loai_tai_san']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select-tai-san" name="tai_san_id[]" required>
                                        <option value="">Chọn tài sản</option>
                                        <?php foreach ($tai_san_list as $tai_san): ?>
                                            <option value="<?= $tai_san['tai_san_id']; ?>"
                                                data-loai="<?= $tai_san['loai_tai_san_id']; ?>"
                                                <?= ($tai_san['tai_san_id'] == $chiTiet['tai_san_id']) ? 'selected' : ''; ?>
                                                style="display: <?= ($tai_san['loai_tai_san_id'] == $chiTiet['loai_tai_san_id']) ? '' : 'none'; ?>;">
                                                <?= htmlspecialchars($tai_san['ten_tai_san']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control" name="so_luong[]"
                                        value="<?= $chiTiet['so_luong']; ?>" required min="1">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeTaiSan(this)">Xóa</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary" onclick="addTaiSan()">Thêm tài sản</button>

                <div class="form-group mt-3">
                    <label for="ghiChu">Ghi chú:</label>
                    <textarea class="form-control" id="ghiChu" name="ghi_chu"
                        rows="3"><?= $phieuBanGiao['ghi_chu']; ?></textarea>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    function addTaiSan() {
        var table = document.getElementById('chiTietTable').getElementsByTagName('tbody')[0];
        var newRow = table.insertRow(table.rows.length);
        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);

        cell1.innerHTML = document.querySelector('.loai-tai-san').outerHTML;
        cell2.innerHTML = document.querySelector('.select-tai-san').outerHTML;
        cell3.innerHTML = '<input type="number" class="form-control" name="so_luong[]" value="1" required min="1">';
        cell4.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="removeTaiSan(this)">Xóa</button>';

        newRow.querySelector('.loai-tai-san').addEventListener('change', function () {
            updateTaiSanOptions(this);
        });
        newRow.querySelector('.select-tai-san').addEventListener('change', function () {
            mergeDuplicateRows();
        });
    }

    function removeTaiSan(button) {
        var row = button.closest('tr');
        if (document.querySelectorAll('#chiTietTable tbody tr').length > 1) {
            row.parentNode.removeChild(row);
        } else {
            alert('Không thể xóa dòng cuối cùng.');
        }
    }

    function updateTaiSanOptions(selectLoai) {
        var loaiTaiSanId = selectLoai.value;
        var selectTaiSan = selectLoai.closest('tr').querySelector('.select-tai-san');

        Array.from(selectTaiSan.options).forEach(option => {
            if (option.value === "") {
                option.style.display = "";
            } else {
                option.style.display = option.dataset.loai === loaiTaiSanId ? "" : "none";
            }
        });

        selectTaiSan.value = ""; // Reset selection
    }

    function mergeDuplicateRows() {
        var table = document.getElementById('chiTietTable');
        var rows = table.getElementsByTagName('tr');
        var rowsToDelete = [];

        for (var i = 1; i < rows.length; i++) {
            for (var j = i + 1; j < rows.length; j++) {
                var loaiTaiSan1 = rows[i].querySelector('.loai-tai-san').value;
                var taiSan1 = rows[i].querySelector('.select-tai-san').value;
                var loaiTaiSan2 = rows[j].querySelector('.loai-tai-san').value;
                var taiSan2 = rows[j].querySelector('.select-tai-san').value;

                if (loaiTaiSan1 === loaiTaiSan2 && taiSan1 === taiSan2) {
                    var soLuong1 = parseInt(rows[i].querySelector('input[name="so_luong[]"]').value);
                    var soLuong2 = parseInt(rows[j].querySelector('input[name="so_luong[]"]').value);
                    rows[i].querySelector('input[name="so_luong[]"]').value = soLuong1 + soLuong2;
                    rowsToDelete.push(rows[j]);
                }
            }
        }

        for (var i = rowsToDelete.length - 1; i >= 0; i--) {
            rowsToDelete[i].parentNode.removeChild(rowsToDelete[i]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.loai-tai-san').forEach(function (select) {
            select.addEventListener('change', function () {
                updateTaiSanOptions(this);
            });
        });

        document.querySelectorAll('.select-tai-san').forEach(function (select) {
            select.addEventListener('change', function () {
                mergeDuplicateRows();
            });
        });
    });
</script>