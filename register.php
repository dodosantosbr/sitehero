<?php
    $host = "localhost";
    $dbname = "postgres";
    $username = "postgres";
    $password = "adm99";
    $port = "4520"

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password, $port);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Error : " . $e->getMessage();
        die();
    }

    $username = strtolower(trim($_POST['username']));
    $password = trim($_POST['password']);
    $user_type = 1;
    $server = 0;
    $ncash = 0;
    $bank_gold = 0;
    $created_at = "1970-01-01 05:30:00+07:30";
    $disabled_until = "1970-01-01 05:30:00+07:30";
    $checkin_counter = 0;

    $username = preg_replace('/[^a-zA-Z0-9]/', '', $username);

    if (strlen($username) >= 12) {
        echo "<div class='error'>O nome de usuário só pode ter no máximo 12 caracteres no mínimo 1</div>";
        die();
    }

    if (strlen($password) < 6) {
		echo "<div class='error'>A senha deve ter pelo menos 6 caracteres</div>";
        die();
    }

    $password = hash('sha256', $password);

    $check_existing = $conn->prepare("SELECT * FROM hops.account WHERE user_name = :username");
    $check_existing->execute(array(':username' => $username));

    if ($check_existing->rowCount() > 0) {
        echo "<div class='error'>$username já existe</div>";
        die();
    }

    $query = "INSERT INTO hops.account (user_name, password, user_type, server, ncash, bank_gold, created_at, disabled_until, checkin_counter) VALUES (:username, :password, :user_type, :ip, :server, :ncash, :bank_gold, :mail, :created_at, :disabled_until, :checkin_counter)";
    $insert_user = $conn->prepare($query);
    $insert_user->execute(array(
        ':username' => $username,
        ':password' => $password,
        ':user_type' => $user_type,
	':ip' => $ip,
        ':server' => $server,
        ':ncash' => $ncash,
        ':bank_gold' => $bank_gold,
	':mail' => $mail
        ':created_at' => $created_at,
        ':disabled_until' => $disabled_until,
        ':checkin_counter' => $checkin_counter
    ));

    if ($insert_user) {
        echo "<div class='success'>$username foi registrado com sucesso!</div>";
    }
?>
