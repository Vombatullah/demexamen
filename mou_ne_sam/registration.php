<?php
$pageTitle = 'Регистрация';
require_once "struktura.php";

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ['login', 'password', 'surname', 'name', 'otchestvo', 'phone', 'email'];
    $data = array_map('trim', $_POST);
    
    foreach ($fields as $field) {
        if (empty($data[$field])) {
            $errors[] = "Поле " . ucfirst($field) . " обязательно для заполнения";
        }
    }
    
    if (empty($errors) && !empty($data['login'])) {
        $login = mysqli_real_escape_string($db, $data['login']);
        $check_login = mysqli_query($db, "SELECT id_user FROM user WHERE username = '$login'");
        
        if (mysqli_num_rows($check_login) > 0) {
            $errors[] = "Пользователь с таким логином уже существует";
        } else {
            $escaped_data = array_map(function($value) use ($db) {
                return mysqli_real_escape_string($db, $value);
            }, $data);
            
            $sql = "INSERT INTO user (user_type_id, surname, name, otchestvo, phone, email, username, password) 
                    VALUES ('1', '{$escaped_data['surname']}', '{$escaped_data['name']}', '{$escaped_data['otchestvo']}', 
                    '{$escaped_data['phone']}', '{$escaped_data['email']}', '{$escaped_data['login']}', MD5('{$escaped_data['password']}'))";
            
            if (mysqli_query($db, $sql)) {
                $success = "Регистрация прошла успешно! Теперь вы можете войти в систему.";
                $data = array_fill_keys($fields, '');
            } else {
                $errors[] = "Ошибка при регистрации: " . mysqli_error($db);
            }
        }
    }
}
?>

<main>    
    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success-message"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <?php foreach (['login', 'password', 'surname', 'name', 'otchestvo', 'phone', 'email'] as $field): ?>
            <div class="form-group">
                <label><?= ucfirst($field) ?> *</label>
                <input type="<?= $field === 'password' ? 'password' : ($field === 'email' ? 'email' : 'text') ?>" 
                       name="<?= $field ?>" value="<?= htmlspecialchars($data[$field] ?? '') ?>" required>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn-register">Зарегистрироваться</button>
    </form>
    
    <div class="login-link">
        <p>Уже есть аккаунт? <a href="index.php">Войдите здесь</a></p>
    </div>
</main>