<?php

namespace App\Http\Controllers;

use App\Models\Purpose;
use App\Models\RequiredFee;
use App\Models\Visitor;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use NumberFormatter;

class PaymentController extends Controller
{
    private $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    private $method = 'aes-256-cbc';
    private $key;
    private $iv;

    private $base62chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';


    public function __construct()
    {
        $this->key = 'thisIsASecretKey12345'; // Khóa bí mật của bạn (cần 32 ký tự cho aes-256)
        $this->iv = substr(hash('sha256', 'thisIsASecretIv12345'), 0, 16); // IV phải dài 16 byte
    }

    public function checkVisitorByPhoneNumber(Request $request)
    {
        $phoneNumber = $request->input('phone');
        $visitor = Visitor::where('phone', $phoneNumber)->first();
        if ($visitor) {
            return response([
                'status' => 'success',
                'data' => $visitor,
                'message' => 'Visitor found'
            ], 200);
        } else {
            return response([
                'status' => 'error',
                'message' => 'Visitor not found',
                'data' => null
            ],200);
        }
    }

    public function displayPaymentForm($purposeCode, $phone, $name)
    {
        try {
            $_purpose = Purpose::where('code', $purposeCode)
                        ->with('bank')
                        ->first();
            error_log($_purpose);
            $visitor = Visitor::firstOrCreate(
                ['phone' => $phone],  // Điều kiện tìm kiếm
                ['name' => $name]     // Nếu không tồn tại, tạo mới với tên
            );
            error_log($visitor);
            $requiredFee = RequiredFee::where('purpose_code', $_purpose->code)->orderBy('id', 'DESC')->first();
            error_log($requiredFee);
            if ($_purpose && $visitor && $requiredFee && $requiredFee->amount > 0) {
                $prefixDescription = $_purpose->prefix;
                $visitorName = $visitor->name;
                $visitorPhone = $visitor->phone;
                $now = Carbon::now();
                $month = $now->year;
                $year = $now->month;

                $bankAccount = $_purpose->bank->bank_account;
                $bankName = $_purpose->bank->bank_name;
                $amount = $requiredFee->amount;
                $timestamp = Carbon::now()->timestamp;
                $string = "$visitorPhone-$timestamp";
                $crypto = $_purpose->note;//Crypt::encryptString($string);
                $decoded = $_purpose->note; //Crypt::decryptString($crypto);

                $moneyDescription = "$prefixDescription-$visitorName-$visitorPhone-$timestamp";
                $fmt = numfmt_create( 'vi_VN', NumberFormatter::CURRENCY);
                $purpose = (object) [
                    'purposeName' => $_purpose->name,
                    'purposeDescription' => $moneyDescription,
                    'bankAccount' => $_purpose->bank->bank_account,
                    'bankName' => $_purpose->bank->bank_name,
                    'transactionDescription' => $crypto,
                    'transactionDescriptionDecoded' => $decoded,
                    'bankFullname' => $_purpose->bank->bank_fullname,
                    'bankUsername' => $_purpose->bank->bank_username,
                    'visitorName' => $visitorName,
                    'visitorPhone' => $visitorPhone,
                    'amount' => $requiredFee->amount,
                    'amount_formatted' => numfmt_format_currency($fmt, $requiredFee->amount, 'VND'),
                    'note' => $_purpose->note,
                    'qrcode' => "https://qr.sepay.vn/img?acc=$bankAccount&bank=$bankName&amount=$amount&des=$crypto&template=compact"
                ];
            }
            return view('pay', ['purpose' => $purpose]);
        } catch (\Throwable $th) {
            dd($th->getLine() . ' - ' . $th->getMessage());
            return view('pay', ['purpose' => null]);
        }
    }

    public function storeTransaction(Request $request)
    {
        // Lấy dữ liệu từ webhook
        $data = $request->json()->all();

        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'No data'], 400);
        }

        // Kiểm tra loại giao dịch
        $amount_in = $data['transferType'] === 'in' ? $data['transferAmount'] : 0;
        $amount_out = $data['transferType'] === 'out' ? $data['transferAmount'] : 0;

        try {
            // Tạo một bản ghi trong bảng tb_transactions
            Transaction::create([
                'gateway' => $data['gateway'] ?? null,
                'transaction_date' => $data['transactionDate'] ?? now(),
                'account_number' => $data['accountNumber'] ?? null,
                'sub_account' => $data['subAccount'] ?? null,
                'amount_in' => $amount_in,
                'amount_out' => $amount_out,
                'accumulated' => $data['accumulated'] ?? 0,
                'code' => $data['code'] ?? null,
                'transaction_content' => $data['content'] ?? null,
                'reference_number' => $data['referenceCode'] ?? null,
                'body' => $data['description'] ?? null,
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to insert record: ' . $e->getMessage()], 500);
        }
    }

    function safe_b64encode($string) {
        $data = base64_encode($string);
        // Replace URL unsafe characters with URL safe characters
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    function encode($data, $key) {
        $key = hash('sha256', $key, true); // Generate a 256-bit key
        $iv = openssl_random_pseudo_bytes(16); // Generate a random IV
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
        // Combine IV and encrypted data
        $encrypted = $iv . $encrypted;
        return $this->safe_b64encode($encrypted);
    }

    function safe_b64decode($string) {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    function decode($data, $key) {
        $key = hash('sha256', $key, true); // Generate a 256-bit key
        $data = $this->safe_b64decode($data);
        $iv = substr($data, 0, 16); // Extract the IV
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    }
}