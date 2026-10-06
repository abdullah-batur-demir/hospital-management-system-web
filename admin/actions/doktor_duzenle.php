<?php
require_once "../includes/auth_kontrol.php";
require_once "../../ortak/baglanti.php";

$doktor_id = $_GET["id"] ?? "";

if ($doktor_id === "") {
    $_SESSION["hata"] = "Doktor ID bulunamadı.";
    header("Location: ../admin_panel.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $doktor_id = $_POST["doktor_id"] ?? "";
    $tc_no = trim($_POST["tc_no"] ?? "");
    $sifre = $_POST["sifre_hash"] ?? "";
    $isim = trim($_POST["isim"] ?? "");
    $tel_no = trim($_POST["tel_no"] ?? "");
    $uzmanlik = trim($_POST["uzmanlık"] ?? "");
    $poliklinik_id = trim($_POST["poliklinik_id"] ?? "");

    if ($tc_no === "" || $isim === "" || $tel_no === "" || $uzmanlik === "" || $poliklinik_id === "") {
        $_SESSION["hata"] = "Doktor güncellenemedi: Zorunlu alanları doldurun.";
        header("Location: ../admin_panel.php");
        exit;
    }

    try {
        if ($sifre !== "") {
            $sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);

            $sorgu = $db->prepare("
                UPDATE Doktorlar
                SET tc_no = ?, sifre_hash = ?, isim = ?, tel_no = ?, uzmanlık = ?, poliklinik_id = ?
                WHERE doktor_id = ?
            ");

            $sorgu->execute([
                $tc_no,
                $sifre_hash,
                $isim,
                $tel_no,
                $uzmanlik,
                $poliklinik_id,
                $doktor_id
            ]);
        } else {
            $sorgu = $db->prepare("
                UPDATE Doktorlar
                SET tc_no = ?, isim = ?, tel_no = ?, uzmanlık = ?, poliklinik_id = ?
                WHERE doktor_id = ?
            ");

            $sorgu->execute([
                $tc_no,
                $isim,
                $tel_no,
                $uzmanlik,
                $poliklinik_id,
                $doktor_id
            ]);
        }

        $_SESSION["mesaj"] = "Doktor bilgileri başarıyla güncellendi.";
    } catch (PDOException $e) {
        $_SESSION["hata"] = "Doktor güncellenirken hata oluştu: " . $e->getMessage();
    }

    header("Location: ../admin_panel.php");
    exit;
}

$sorgu = $db->prepare("SELECT * FROM Doktorlar WHERE doktor_id = ?");
$sorgu->execute([$doktor_id]);
$doktor = $sorgu->fetch(PDO::FETCH_ASSOC);

if (!$doktor) {
    $_SESSION["hata"] = "Doktor bulunamadı.";
    header("Location: ../admin_panel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Doktor Düzenle</title>
  <link rel="stylesheet" href="../../assets/css/admin_panel.css">
</head>
<body>

<div class="edit-page"><div class="content edit-card">
  <div class="panel active">
    <h2>Doktor Düzenle</h2>
    <div class="panel-desc">Doktor bilgilerini güncelleyin.</div>

    <form method="POST">
      <input type="hidden" name="doktor_id" value="<?php echo htmlspecialchars($doktor["doktor_id"]); ?>">

      <div class="form-grid">
        <label>
          TC No
          <input type="text" name="tc_no" maxlength="11" value="<?php echo htmlspecialchars($doktor["tc_no"]); ?>" required>
        </label>

        <label>
          Yeni Şifre
          <input type="password" name="sifre_hash" placeholder="Boş bırakırsan şifre değişmez">
        </label>

        <label>
          İsim
          <input type="text" name="isim" value="<?php echo htmlspecialchars($doktor["isim"]); ?>" required>
        </label>

        <label>
          Telefon No
          <input type="text" name="tel_no" value="<?php echo htmlspecialchars($doktor["tel_no"]); ?>" required>
        </label>

        <label>
          Uzmanlık
          <input type="text" name="uzmanlık" value="<?php echo htmlspecialchars($doktor["uzmanlık"]); ?>" required>
        </label>

        <label>
          Poliklinik ID
          <input type="text" name="poliklinik_id" value="<?php echo htmlspecialchars($doktor["poliklinik_id"]); ?>" required>
        </label>
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="../admin_panel.php" class="btn btn-secondary">Geri Dön</a>
      </div>
    </form>
  </div>
</div></div>

</body>
</html>