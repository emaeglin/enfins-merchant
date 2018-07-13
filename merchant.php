<?php

CONST IDENT = 'TEST_IDENT';     // change to real IDENT
CONST SECRET = 'TEST_SECRET';   // change to real SECRET

const API_SERVER = "api.enfins.com";
const API_SCHEME = "https://";
const API_VERSION = "/v1";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !empty($_POST['amount']) && is_numeric($_POST['amount']) &&
        !empty($_POST['currency']) &&
        !empty($_POST['description'])
    ) {
        $params = array(
            'amount' => $_POST['amount'],
            'currency' => $_POST['currency'],
            'description' => $_POST['description'],
            'm_order' => mt_rand(100, 999),
        );
        if ($url = CreateBill($params)) {
            header('Location: ' . $url);
            exit;
        }
    }
}

function CreateBill($params)
{
    $method = '/create_bill';
    $params['ident'] = IDENT;
    $query = http_build_query($params);
    $sign = GenSign($query, SECRET);

    $url = API_SCHEME . API_SERVER . API_VERSION . $method . '?' . $query . '&sign=' . $sign;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    if ($response['result']) {
        return $response['data']['url'];
    } else {
        //exception
    }

    return false;
}

function GenSign($query, $secret)
{
    return hash_hmac("sha1", $query, $secret);
}

?>
<html>
<head></head>

<style>
    body {
        border-top: solid 4px #5d6f86;
        background: #eff1f5;
        min-height: 100vh;
        padding: 10% 0 0 0;
        margin: 0;
    }

    form {
        width: 380px;
        margin: 0 auto;
        box-shadow: 0 2px 4px 0 rgba(34, 45, 63, 0.06);
        border-radius: 4px;
        background: #ffffff;
        padding: 1px 24px 24px;
        box-sizing: border-box;
    }

    label {
        display: block;
        color: #5d6f86;
        display: block;
        margin: 24px 0 5px;
    }

    input, select {
        width: 100%;
        min-height: 48px;
        height: 48px;
        padding: 20px;
        box-sizing: border-box;
        outline: none;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px ;
        border: solid 1px #d2dae3;

    }

    textarea {
        width: 100%;
        min-height: 48px;
        padding: 20px;
        height: 64px;
        box-sizing: border-box;
        outline: none;
        border: solid 1px #d2dae3;
        resize: none;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px ;
    }

    .auth_logo {
        text-align: center;
        margin: 0 0 25px;
    }
    .auth_logo div{
        margin: 14px 0 0 0;
        color: #27283f;
        font-size: 16px;
    }

    .auth_logo .logo_small {
        width: 64px;
        height: 53px;
        margin: 0 auto;
    }
    button{
        font-size: 14px;
        font-weight: 600;
        color: #ffffff;
        background: #3d9bfe;
        text-decoration: none;
        padding: 15px 28px 16px 29px;
        box-shadow: 0 4px 16px 0 rgba(34, 45, 63, 0.24);
        box-sizing: border-box;
        display: inline-block;
        vertical-align: top;
        border-radius: 2px;
        height: 48px;
        text-align: center;
        cursor: pointer;
        -webkit-transition: all 0.5s ease;
        -moz-transition: all 0.5s ease;
        -o-transition: all 0.5s ease;
        transition: all 0.5s ease;
        margin: 34px 0 24px;
        width: 100%;
        border: none;
    }
    .logo_small {
        background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NCA1Mi41MSI+PHRpdGxlPmxvZ29fc21hbGw8L3RpdGxlPjxnIGlkPSLQodC70L7QuV8yIiBkYXRhLW5hbWU9ItCh0LvQvtC5IDIiPjxnIGlkPSLQodC70L7QuV8xLTIiIGRhdGEtbmFtZT0i0KHQu9C+0LkgMSI+PGcgaWQ9IlBhZ2UtMSI+PGcgaWQ9IkxvZ2luIj48ZyBpZD0iR3JvdXAtMi1Db3B5LTIiPjxnIGlkPSJHcm91cC0xMiI+PHBvbHlnb24gaWQ9IkNvbWJpbmVkLVNoYXBlLUNvcHktMyIgcG9pbnRzPSIxNy43OCAwIDAgMjYuMjYgMTcuNzggNTIuNTEgMzkuMTEgNTIuNTEgNDIuNjcgNDUuOTUgMjMuMTEgNDUuOTUgMTEuNTYgMjkuNTQgNDAuODkgMjkuNTQgMzcuMzMgMjIuOTcgMTEuNTYgMjIuOTcgMjMuMTEgNi41NiA0Mi42NyA2LjU2IDM5LjExIDAgMTcuNzggMCIgc3R5bGU9ImZpbGw6IzIyMmQzZiIvPjxwb2x5Z29uIGlkPSJDb21iaW5lZC1TaGFwZS1Db3B5LTkiIHBvaW50cz0iNDEuMDMgMjIuOTcgNDkuMjQgMzkuMzggNDIuNjcgNTIuNTEgNTAuODcgNTIuNTEgNTcuNDQgMzkuMzkgNDkuMjMgMjIuOTcgNDEuMDMgMjIuOTciIHN0eWxlPSJmaWxsOiMzZTlhZmQiLz48cG9seWdvbiBpZD0iQ29tYmluZWQtU2hhcGUtQ29weS0xMCIgcG9pbnRzPSI0Mi42NyAwIDU5LjkgMzQuNDYgNjQgMjYuMjYgNTAuODcgMCA0Mi42NyAwIiBzdHlsZT0iZmlsbDojM2U5YWZkIi8+PC9nPjwvZz48L2c+PC9nPjwvZz48L2c+PC9zdmc+);
    }
</style>
<body>
<div class="auth_logo">
    <div class="logo_small"></div>
    <div>Enfins</div>
</div>
<form method="post">

    <div>
        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount"
               value="<?php echo (!empty($_POST['amount']) && is_numeric($_POST['amount'])) ? $_POST['amount'] : ''; ?>">
    </div>
    <div>
        <label for="currency">Currency:</label>
        <select name="currency">
            <option <?php echo (!empty($_POST['currency']) && $_POST['currency'] == 'USD') ? 'selected' : ''; ?>
                    value="USD">USD
            </option>
            <option <?php echo (!empty($_POST['currency']) && $_POST['currency'] == 'EUR') ? 'selected' : ''; ?>
                    value="EUR">EUR
            </option>
            <option <?php echo (!empty($_POST['currency']) && $_POST['currency'] == 'UAH') ? 'selected' : ''; ?>
                    value="UAH">UAH
            </option>
            <option <?php echo (!empty($_POST['currency']) && $_POST['currency'] == 'RUB') ? 'selected' : ''; ?>
                    value="RUB">RUB
            </option>
        </select>
    </div>
    <div>
        <label for="description">Description:</label>
        <textarea id="description"
                  name="description"><?php echo (!empty($_POST['description'])) ? $_POST['description'] : ''; ?></textarea>
    </div>
    <div>
        <button type="submit">Submit</button>
    </div>
</form>
</body>
</html>
