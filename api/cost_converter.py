from flask import Flask, jsonify
import re

app = Flask(__name__)

# 貨幣和預定義匯率
ALLOWED_CURRENCIES = {
    'HKD': 7.8,
    'EUR': 0.82,
    'JPY': 110
}

@app.route('/cost_convert/<amount>/<currency>/<rate>', methods=['GET'])
def cost_convert(amount, currency, rate):
    try:
        # 驗證金額是否為正數
        amount = float(amount)
        if amount <= 0:
            return jsonify({
                'result': 'rejected',
                'reason': 'Amount must be a positive number'
            }), 400

        # 驗證貨幣是否有效
        if currency not in ALLOWED_CURRENCIES:
            return jsonify({
                'result': 'rejected',
                'reason': "Error: Currency must be 'HKD' or 'EUR' or 'JPY'"
            }), 400

        # 驗證匯率是否為正數
        rate = float(rate)
        if rate <= 0:
            return jsonify({
                'result': 'rejected',
                'reason': 'Rate must be a positive number'
            }), 400

        # 驗證輸入匯率是否與預定義匯率匹配
        if abs(rate - ALLOWED_CURRENCIES[currency]) > 0.01:  # 允許小誤差
            return jsonify({
                'result': 'rejected',
                'reason': f"Invalid rate for {currency}, expected {ALLOWED_CURRENCIES[currency]}"
            }), 400

        # 計算轉換金額
        converted_amount = amount * rate

        return jsonify({
            'result': 'accepted',
            'converted_amount': converted_amount
        }), 200

    except ValueError:
        return jsonify({
            'result': 'rejected',
            'reason': 'Invalid input: Amount and rate must be numeric'
        }), 400

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)