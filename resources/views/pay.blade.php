@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <div class="border shadow p-4">
                    <h4 class="fw-bold">{{ $purpose->note }}</h4>
                    <i class="text-danger">(Sau khi chuyển khoản vui lòng không F5 cho đến khi nhận được thông báo chuyển
                        khoản thành công)</i>
                    <div class="align-items-center justify-content-between">
                        <img src="{{ $purpose->qrcode }}" alt="DB Pay logo" class="w-100">
                    </div>
                    <div class="fs-6 pb-2 mt-3" style="font-size: 12px">
                        <span>Số tiền: <strong>{{ $purpose->amount_formatted }}<span
                                    class="text-secondary"></span></strong></span> <br>
                        <span>Ngân hàng: {{ $purpose->bankName }} ({{ $purpose->bankAccount }})
                        </span> <br>
                        <span>
                            Chủ tài khoản: {{ $purpose->bankUsername }}
                        </span> <br>
                        <span>
                            Nội dung chuyển khoản: {{ $purpose->transactionDescriptionDecoded }}
                        </span> <br>
                        <span>
                            Người chuyển tiền: {{ $purpose->visitorName }}
                        </span> <br>
                        <span>
                            Số điện thoại: {{ $purpose->visitorPhone }}
                        </span> <br>
                    </div>
                    <div class="fs-6 pb-2 mt-3" style="font-size: 12px">
                        <button class="btn btn-warning w-100 d-none" id="btn-check-payment">
                            Kiểm tra thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // countdown 40 seconds then shơw the button
        setTimeout(() => {
            document.getElementById('btn-check-payment').classList.remove('d-none');
        }, 1000);

        document.getElementById('btn-check-payment').addEventListener('click', function() {
            const transactionDescription = `{{ $purpose->transactionDescriptionDecoded }}`;

            axios.get(`/check-transaction/${transactionDescription}`)
                .then(response => {
                    alert(response.data.message);
                })
                .catch(error => {
                    if (error.response) {
                        // Lấy message từ server khi có lỗi
                        alert(error.response.data.message || 'Đã xảy ra lỗi. Vui lòng thử lại.');
                    } else {
                        alert('Đã xảy ra lỗi kết nối. Vui lòng thử lại.');
                    }
                });
        });
    </script>
@endsection
