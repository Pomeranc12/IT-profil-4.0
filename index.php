<?php
// 1. Načtení stávajících dat z JSONu
$json_data = file_get_contents('profile.json');
$interests = json_decode($json_data, true) ?? [];

$message = "";
$messageType = "";

// 2. Zpracování POST požadavku [cite: 19, 20]
if (isset($_POST["new_interest"])) { // [cite: 21]
    $new_interest = trim($_POST["new_interest"]); // 

    // Kontrola, zda není pole prázdné [cite: 23]
    if (empty($new_interest)) {
        $message = "Pole nesmí být prázdné."; // [cite: 47]
        $messageType = "error"; // [cite: 58]
    } else {
        // Kontrola duplicity (bez ohledu na velikost písmen) [cite: 24, 32]
        $lowered_interests = array_map('strtolower', $interests);
        
        if (in_array(strtolower($new_interest), $lowered_interests)) { // [cite: 28, 29]
            $message = "Tento zájem už existuje."; // [cite: 46, 57]
            $messageType = "error"; // [cite: 58]
        } else {
            // Přidání do pole a uložení [cite: 25, 26]
            $interests[] = $new_interest;
            file_put_contents('profile.json', json_encode($interests, JSON_UNESCAPED_UNICODE)); // [cite: 30, 31]
            
            $message = "Zájem byl úspěšně přidán."; // [cite: 43, 54]
            $messageType = "success"; // [cite: 55]
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>IT Profil 4.0</title>
</head>
<body>

    <?php if (!empty($message)): ?>
        <p class="<?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?> </p>
    <?php endif; ?>

    <form method="POST"> <input type="text" name="new_interest" required> <button type="submit">Přidat zájem</button> </form>

    <ul>
        <?php foreach ($interests as $interest): ?>
            <li><?php echo htmlspecialchars($interest); ?></li>
        <?php endforeach; ?>
    </ul>

</body>
</html>