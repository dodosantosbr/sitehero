<?php
    $host = "localhost";
    $dbname = "postgres";
    $username = "postgres";
    $password = "PASSWORD";

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
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
        echo "<div class='error'>Username can only be maximum of 12 minimum of 1 characters</div>";
        die();
    }

    if (strlen($password) < 6) {
		echo "<div class='error'>Password must be at least 6 characters</div>";
        die();
    }

    $password = hash('sha256', $password);

    $check_existing = $conn->prepare("SELECT * FROM hops.account WHERE user_name = :username");
    $check_existing->execute(array(':username' => $username));

    if ($check_existing->rowCount() > 0) {
        echo "<div class='error'>$username already exists</div>";
        die();
    }

    $query = "INSERT INTO hops.account (user_name, password, user_type, server, ncash, bank_gold, created_at, disabled_until, checkin_counter) VALUES (:username, :password, :user_type, :server, :ncash, :bank_gold, :created_at, :disabled_until, :checkin_counter)";
    $insert_user = $conn->prepare($query);
    $insert_user->execute(array(
        ':username' => $username,
        ':password' => $password,
        ':user_type' => $user_type,
        ':server' => $server,
        ':ncash' => $ncash,
        ':bank_gold' => $bank_gold,
        ':created_at' => $created_at,
        ':disabled_until' => $disabled_until,
        ':checkin_counter' => $checkin_counter
    ));

    if ($insert_user) {
        echo "<div class='success'>$username is successfully registered!</div>";
    }
?>
