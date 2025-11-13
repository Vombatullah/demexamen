<?php
$pageTitle = 'Авторизация';
require_once "struktura.php";
$loginError = '';

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['user_type_id'] == 2) {
        header("Location: admin.php");
        exit();
    } else {
        header("Location: zayavka.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST["login"] ?? "";
    $password = $_POST["password"] ?? "";

    $login = strip_tags($login);
    $password = strip_tags($password);

    $user = find($login, $password); 

    if ($user) {
        $_SESSION['user'] = $user; 

        if ($user['user_type_id'] == 2) {
            header("Location: admin.php");
            exit();
        } else {
            header("Location: zayavka.php");
            exit();
        }
    } else {
        $loginError = "Неверный логин или пароль.";
    }
}
?>

    <main>    
       <!--
        <form>
        <label>Логин
            <input type="text" name="login"> 
        </label> 
        <label>Пароль
            <input type="text" name="password"> 
        </label> 
        <button>Вход</button>
        </form> 
        <p class="Error">
            <?php    /*        
            $password=strip_tags($_GET["password"] ?? "");
            $login=strip_tags($_GET["login"] ?? "");            
            if ($login && $password){                
                echo find($login,$password);
                if (find($login, $password)) {
                    echo "Успешная авторизация: " . $login . ", " . $password;
                } else {
                    echo "Ошибка авторизации: " . $login . ", " . $password . " - error";
                }
            }*/
            ?>
        </p>-->
        <form method="post" action="index.php">
        <label>Логин
        <input type="text" name="login" required value="<?php echo htmlspecialchars($login ?? ''); ?>" autocomplete="username">
        </label>

        <label>Пароль
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit">Вход</button>
    </form>

    <?php if (!empty($loginError)): ?>
        <p class="Error"><?php echo htmlspecialchars($loginError); ?></p>
    <?php endif; ?>
    </main>





