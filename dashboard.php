<?php
session_start();

// Kiểm tra nếu session không tồn tại
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Quay về trang đăng nhập
    exit();
}
?>
<?php require_once "db.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Quản Lý Hàng Hóa</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Danh Sách Hàng Hóa</h2>
        <button id="addButton" class="btn btn-success">Thêm mới</button>
        <button id="deleteButton" class="btn btn-danger">Xóa</button>
        <a href="logout.php" class="btn btn-warning" style="margin-left: 10px;">Đăng Xuất</a>
        <!-- Hiển thị bảng dữ liệu -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Chọn</th>
                    <th>Tên Hàng Hóa</th>
                    <th>Nhà Cung Cấp</th>
                    <th>Hình Ảnh</th>
                    <th>Sửa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM hanghoa";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' class='delete-checkbox' value='" . $row["id"] . "'></td>";
                    echo "<td>" . $row["tenhanghoa"] . "</td>";
                    echo "<td>" . $row["nhacungcap"] . "</td>";
                    echo "<td><img alt=". $row["hinhanh"] ." src='uploads/" . $row["hinhanh"] . "' width='100' height='100'></td>";
                    echo "<td><button class='btn btn-primary editButton' data-id='" . $row["id"] . "'>Sửa</button></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Form -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="id_edit" name="id">
                        <div class="form-group">
                            <label>Tên Hàng Hóa</label>
                            <input type="text" class="form-control" name="tenhanghoa" id="tenhanghoa" required>
                        </div>
                        <div class="form-group">
                            <label>Nhà Cung Cấp</label>
                            <input type="text" class="form-control" name="nhacungcap" id="nhacungcap" required>
                        </div>
                        <div class="form-group">
                            <label>Hình Ảnh</label>
                            <input type="file" name="hinhanh" id="hinhanh">
                        </div>
                        <!-- Thẻ img để xem trước ảnh -->
                        <img id="imagePreview" src="" alt="Xem trước ảnh"
                            style="max-width: 100px; display: none; margin-top: 10px;">
                        <button type="submit" class="btn btn-info">Lưu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Thêm mới
        $('#addButton').on('click', function() {
            $('#editForm')[0].reset();
            $('#id_edit').val('');
            $('#editModal').modal('show');
        });

        // Sửa
        $('.editButton').on('click', function() {
            let id = $(this).data('id');
            $.post('get_data.php', {
                id: id
            }, function(data) {
                let hanghoa = JSON.parse(data);
                $('#id_edit').val(hanghoa.id);
                $('#tenhanghoa').val(hanghoa.tenhanghoa);
                $('#nhacungcap').val(hanghoa.nhacungcap);

                // Hiển thị ảnh cũ
                if (hanghoa.hinhanh) {
                    $('#imagePreview').attr('src', 'uploads/' + hanghoa.hinhanh).show();
                } else {
                    $('#imagePreview').hide();
                }

                $('#editModal').modal('show');
            });
        });

        // Xóa
        $('#deleteButton').on('click', function() {
            let ids = [];
            $('.delete-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            if (ids.length) {
                $.post('delete_records.php', {
                    ids: ids
                }, function() {
                    location.reload();
                });
            } else {
                alert('Vui lòng chọn dữ liệu để xóa!');
            }
        });

        // Submit Form
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: $('#id_edit').val() ? 'update_data.php' : 'add_data.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function() {
                    alert('Lưu thành công!');
                    $('#editModal').modal('hide');
                    location.reload();
                }
            });
        });
    });
    </script>
</body>

</html>