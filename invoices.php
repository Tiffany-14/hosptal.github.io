<?php 
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hệ thống thanh toán</title>
</head>
<style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #64a8daff;
            margin-bottom: 20px;
        }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        .btn-create {
            background-color: #64a8daff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-create:hover {
            background-color: #64a8daff;
        }
        .invoice-list {
            margin-top: 20px;
        }
        .invoice-list h2 {
            color: #333;
        }
        .desc {
            font-size: 14px;
            color: #777;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #64a8daff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .paid {
            background-color: #d4edda;
            color: #155724;
        }
        .unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
        .btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-pay {
            background-color: #FFC107;
        }
        .btn-pay:hover {
            background-color: #e0a800;
        }
    </style>

<body>
    <div class="container">
        <h1>Hệ thống thanh toán</h1>

        <div class="top-bar">
            <button class="btn-create">📝 Tạo hóa đơn mới</button>
        </div>

        <div class="invoice-list">
            <h2>Danh sách hóa đơn</h2>
            <p class="desc">Quản lý và theo dõi thanh toán</p>
            <table>
                <thead>
                    <tr>
                        <th>Mã hóa đơn</th>
                        <th>Bệnh nhân</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td><?= $invoice['id'] ?></td>
                            <td><?= $invoice['patient'] ?></td>
                            <td><?= $invoice['amount'] ?></td>
                            <td><?= $invoice['date'] ?></td>
                            <td>
                                <?php if ($invoice['status'] == 'paid'): ?>
                                    <span class="status paid">Đã thanh toán</span>
                                <?php else: ?>
                                    <span class="status unpaid">Chưa thanh toán</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn">Xem</button>
                                <button class="btn">In</button>
                                <?php if ($invoice['status'] == 'unpaid'): ?>
                                    <button class="btn btn-pay">Thanh toán</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
