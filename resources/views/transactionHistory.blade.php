@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Danh sách giao dịch</h2>

        <button class="btn btn-secondary mb-3" id="btn-back">Trở về</button>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Ngân hàng</th>
                        <th>Số tài khoản</th>
                        <th>Số tiền vào</th>
                        <th>Số tiền ra</th>
                        <th>Nội dung chuyển khoản</th>
                        <th>Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->gateway }}</td>
                            <td>{{ $transaction->account_number }}</td>
                            <td>{{ $transaction->amount_in }}</td>
                            <td>{{ $transaction->amount_out }}</td>
                            <td>{{ $transaction->transaction_content }}</td>
                            <td>{{ $transaction->transaction_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Sự kiện cho nút "Trở về"
        document.getElementById('btn-back').addEventListener('click', function() {
            window.history.back(); // Quay lại trang trước đó
        });
    </script>


@endsection
