@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Nhập thông tin thanh toán') }}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form id="visitorForm">
                            @csrf
                            <div class="row mb-3">
                                <label for="phone"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Số điện thoại') }}</label>
                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control is-invalid " name="phone"
                                        value="{{ old('phone') }}" inputmode="numeric" autocomplete="cc-number"
                                        maxlength="11" placeholder="xxx xxx xxxx"
                                        oninvalid="this.setCustomValidity('Số điện thoại không hợp lệ')" onchange
                                        onpropertychange onkeyuponpaste oninput="changePhone(this)" required>
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="amount"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Họ và tên') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text" disabled
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">

                                <label for="purpose"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Mục đích thanh toán') }}</label>
                                <div class="col-md-6">
                                    <select disabled id="purpose"
                                        class="w-100 form-select @error('purpose') is-invalid @enderror" name="purpose"
                                        required>
                                        <option value="0">Lựa chọn mục đích thanh toán</option>
                                        @foreach ($purposes as $purpose)
                                            <option value="{{ $purpose->code }}">{{ $purpose->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('purpose')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-md-4 offset-md-4">
                                    <button type="button" disabled class="btn btn-primary w-100" id="btn-submit"
                                        onclick="openPaymentPage()">
                                        {{ __('Tiếp tục') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function changePhone(element) {
            let phoneValue = element.value;
            if (!/^\d{10}$/.test(phoneValue)) {
                element.classList.add('is-invalid');
                document.getElementById('name').disabled = true;
                document.getElementById('purpose').disabled = true;
                document.getElementById('btn-submit').disabled = true;
                return;
            } else {
                element.classList.remove('is-invalid');
                element.classList.add('is-valid');
            }
            window.axios.post('/api/check-visitor-by-phone', {
                    phone: element.value
                }).then(function(response) {
                    if (response.data.data == null) {
                        document.getElementById('name').disabled = false;
                        alert(
                            `Số điện thoại ${document.getElementById('phone').value} chưa được đăng ký \n Vui lòng nhập thông tin họ và tên`
                        );
                    } else {
                        document.getElementById('name').value = response.data.data.name;
                    }
                    document.getElementById('purpose').disabled = false;
                    document.getElementById('btn-submit').disabled = false;
                })
                .catch(function(error) {
                    alert("Có lỗi xảy ra, vui lòng thử lại sau");
                });
        };

        function openPaymentPage() {
            // stop process action
            let phone = document.getElementById('phone').value;
            let name = document.getElementById('name').value;
            let purpose_code = document.getElementById('purpose').value;

            if (name == '') {
                alert('Vui lòng nhập họ và tên');
            } else if (purpose_code == 0) {
                alert('Vui lòng chọn mục đích thanh toán');
            } else {
                let url = `/pay/${purpose_code}/${phone}/${name}`;
                window.open(url, '_blank').focus();
            }
        }
    </script>
@endsection
