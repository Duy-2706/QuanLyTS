<div class="container-fluid">
    <div class="row mt-3">
        <div class="col">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php?model=loaitaisan&action=index">Loại TAI SANgit </a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>

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
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Quản Lý Loại Tài Sản</h5>
                <div>
                    <button id="toggleSearch" class="btn btn-secondary">Tìm kiếm</button>
                    <a href="index.php?model=loaitaisan&action=create" class="btn btn-primary">Thêm Mới</a>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div id="searchForm" class="mb-3" style="display: none;">
                    <form action="index.php?model=loaitaisan&action=index" method="post" class="form-inline">
                    <div class="col-md-4 mb-2">
                        <div class="d-flex align-items-center">
                            <label for="loaiTaiSanSearch" class="mr-2 mb-0" style="white-space: nowrap;">Loại tài sản:</label>
                            <input type="text" id="loaiTaiSanSearch" class="form-control" name = "loai_ts_tk" placeholder="Nhập loại tài sản">
                        </div>
                     </div>
                      <div class="col-md-3 mb-2">
                         <button type="submit" class="btn btn-success" name = "btn_tim_kiem">Tìm kiếm</button>
                     </div>
                     </form>
            </div>
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered " width="100%" cellspacing="0">
                    <thead class="bg-light text-black text-center">
                        <tr>
                            <th>ID</th>
                            <th>Loại Tài Sản</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody id="loaiTaiSanTable">
                         <?php $i = 0; foreach ($loaiTaiSans as $loaiTaiSan): ?>
                            <?php if ($loaiTaiSan['loai_tai_san_id'] != 0): ?>
                                <tr>
                                <!-- <?php if ($i % 2 == 0): ?>
                                    <tr style="background-color: white;">
                                <?php else: ?>
                                    <tr style="background-color: lightgrey;">
                                <?php endif; ?> -->
                                    <td class="text-center"><?= $loaiTaiSan['loai_tai_san_id'] ?></td>
                                    <td><?= htmlspecialchars($loaiTaiSan['ten_loai_tai_san']) ?></td>
                                    <td class="d-flex justify-content-center">
                                        <a href="index.php?model=loaitaisan&action=edit&id=<?= $loaiTaiSan['loai_tai_san_id'] ?>"
                                            class="btn btn-warning btn-sm sm-1">Sửa</a>
                                        <form action="index.php?model=loaitaisan&action=delete&id=<?= $loaiTaiSan['loai_tai_san_id'] ?>"
                                            method="POST" style="display: inline-block;"
                                            onsubmit="return confirmDelete();">
                                            <button type="submit" class="btn btn-danger btn-sm mx-2">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php ++$i; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // var table = $('#dataTable').DataTable({
    //     dom: 'rtip',
    //     language: {
    //         "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Vietnamese.json"
    //     }
    // });

    // Thêm sự kiện tìm kiếm cho input
    // $('#loaiTaiSanSearch').on('keyup', function () {
    //     table.column(1).search(this.value).draw();
    // });

    var toggleButton = document.getElementById('toggleSearch');
    var searchForm = document.getElementById('searchForm');

    toggleButton.addEventListener('click', function () {
        if (searchForm.style.display === 'none') {
            searchForm.style.display = 'block';
            toggleButton.textContent = 'Ẩn tìm kiếm';
        } else {
            searchForm.style.display = 'none';
            toggleButton.textContent = 'Tìm kiếm';
        }
    });
});

function confirmDelete() {
    return confirm('Bạn có chắc muốn xóa loại tài sản này? Hành động này không thể hoàn tác và tất cả các tài sản thuộc loại này sẽ được cập nhật loại tài sản về mặc định.');
}
</script>
