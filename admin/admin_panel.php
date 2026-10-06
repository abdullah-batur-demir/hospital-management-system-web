<?php
require_once "includes/auth_kontrol.php";
require_once "../ortak/baglanti.php";

$mesaj = $_SESSION["mesaj"] ?? "";
$hata = $_SESSION["hata"] ?? "";

unset($_SESSION["mesaj"]);
unset($_SESSION["hata"]);

$hastalar = $db->query("SELECT * FROM Hastalar ORDER BY hasta_id DESC")->fetchAll(PDO::FETCH_ASSOC);
$doktorlar = $db->query("SELECT * FROM Doktorlar ORDER BY doktor_id DESC")->fetchAll(PDO::FETCH_ASSOC);
$poliklinikler = $db->query("SELECT * FROM Poliklinikler ORDER BY poliklinik_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="../assets/css/admin_panel.css?v=20260508-poliklinik">
</head>
<body>

<div class="layout">

  <aside class="sidebar">
    <div class="brand">
      <div class="brand-icon">+</div>
      <div>
        <h2>Admin Panel</h2>
        <p>Hastane Yönetimi</p>
      </div>
    </div>

    <div class="menu">
      <div class="menu-title">Hasta İşlemleri</div>
      <button class="menu-btn active" data-icon="👤" data-target="hasta-ekle">Hasta Ekle</button>
      <button class="menu-btn" data-icon="📋" data-target="hasta-liste">Hasta Listele / Düzenle</button>

      <div class="menu-title">Doktor İşlemleri</div>
      <button class="menu-btn" data-icon="🩺" data-target="doktor-ekle">Doktor Ekle</button>
      <button class="menu-btn" data-icon="🏥" data-target="doktor-liste">Doktor Listele / Düzenle</button>

      <div class="menu-title">Poliklinik İşlemleri</div>
      <button class="menu-btn" data-icon="🏢" data-target="poliklinik-ekle">Poliklinik Ekle</button>
      <button class="menu-btn" data-icon="📑" data-target="poliklinik-liste">Poliklinik Listele / Düzenle</button>

      <div class="menu-title">Hesap İşlemleri</div>
      <button class="menu-btn" data-icon="🔐" data-target="sifre-degistir">Şifre Değiştir</button>
    </div>

    <a class="logout" href="../cikis.php">Çıkış Yap</a>
  </aside>

  <main class="content">

    <div class="topbar">
      <div>
        <h1>Hoş geldiniz, <?php echo htmlspecialchars($_SESSION["admin_ad"]); ?></h1>
        <p>Hasta, doktor ve poliklinik kayıtlarını buradan yönetebilirsiniz.</p>
      </div>

      <div class="user-box">Oturum: Admin</div>
    </div>

    <div class="dashboard-summary">
      <div class="summary-card summary-blue">
        <span class="summary-label">Toplam Hasta</span>
        <strong><?php echo count($hastalar); ?></strong>
        <small>Kayıtlı hasta profili</small>
      </div>

      <div class="summary-card summary-green">
        <span class="summary-label">Toplam Doktor</span>
        <strong><?php echo count($doktorlar); ?></strong>
        <small>Aktif doktor kaydı</small>
      </div>

      <div class="summary-card summary-orange">
        <span class="summary-label">Panel Durumu</span>
        <strong>Online</strong>
        <small>Yönetim ekranı hazır</small>
      </div>

      <div class="summary-card summary-slate">
        <span class="summary-label">Oturum</span>
        <strong>Admin</strong>
        <small>Yetkili erişim</small>
      </div>
    </div>

    <?php if ($mesaj != ""): ?>
      <div class="message success"><?php echo htmlspecialchars($mesaj); ?></div>
    <?php endif; ?>

    <?php if ($hata != ""): ?>
      <div class="message error"><?php echo htmlspecialchars($hata); ?></div>
    <?php endif; ?>

    <section class="panel active" id="hasta-ekle">
      <div class="panel-head"><div><h2>Hasta Ekle</h2><div class="panel-desc">Hastalar tablosuna yeni kayıt ekleyin.</div></div></div>

      <form action="actions/hasta_ekle.php" method="POST">
        <div class="form-grid">
          <label>
            TC No
            <input type="text" name="tc_no" maxlength="11" required>
          </label>

          <label>
            Şifre
            <input type="password" name="sifre" required>
          </label>

          <label>
            İsim
            <input type="text" name="isim" required>
          </label>

          <label>
            Doğum Tarihi
            <input type="date" name="dogum_tar" required>
          </label>

          <label>
            Cinsiyet
            <select name="cinsiyet" required>
              <option value="">Seçiniz</option>
              <option value="Erkek">Erkek</option>
              <option value="Kadın">Kadın</option>
            </select>
          </label>

          <label>
            Telefon No
            <input type="text" name="tel_no" required>
          </label>
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary">Hasta Ekle</button>
          <button type="reset" class="btn btn-secondary">Temizle</button>
        </div>
      </form>
    </section>

    <section class="panel" id="hasta-liste">
      <div class="panel-head">
        <div>
          <h2>Hasta Listele / Düzenle</h2>
          <div class="panel-desc">Kayıtlı hastalar aşağıda listelenir. Düzenleme veya silme işlemi yapabilirsiniz.</div>
        </div>
        <div class="table-tools">
          <label class="table-search" aria-label="Hasta TC arama">
            <input id="hastaTcArama" data-table-search="#hasta-table" data-search-column="1" type="search" inputmode="numeric" autocomplete="off" placeholder="Hasta TC ara...">
          </label>
        </div>
      </div>

      <div class="table-wrap">
        <table id="hasta-table">
          <thead>
            <tr>
              <th>Hasta ID</th>
              <th>TC No</th>
              <th>Şifre Hash</th>
              <th>İsim</th>
              <th>Doğum Tarihi</th>
              <th>Cinsiyet</th>
              <th>Telefon</th>
              <th>İşlem</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($hastalar as $hasta): ?>
              <tr>
                <td><?php echo htmlspecialchars($hasta["hasta_id"]); ?></td>
                <td><?php echo htmlspecialchars($hasta["tc_no"]); ?></td>
                <td class="hash-cell"><?php echo htmlspecialchars($hasta["sifre_hash"]); ?></td>
                <td><?php echo htmlspecialchars($hasta["isim"]); ?></td>
                <td><?php echo htmlspecialchars($hasta["dogum_tar"]); ?></td>
                <td><?php echo htmlspecialchars($hasta["cinsiyet"]); ?></td>
                <td><?php echo htmlspecialchars($hasta["tel_no"]); ?></td>
                <td>
                  <div class="islem-butonlari">
  <a class="edit-btn" href="actions/hasta_duzenle.php?id=<?php echo htmlspecialchars($hasta["hasta_id"]); ?>">
    Düzenle
  </a>

  <form action="actions/hasta_sil.php" method="POST" class="delete-form" data-confirm-message="Bu hastayı silmek istediğine emin misin?">
    <input type="hidden" name="hasta_id" value="<?php echo htmlspecialchars($hasta["hasta_id"]); ?>">
    <button type="submit">Sil</button>
  </form>
</div>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if (count($hastalar) == 0): ?>
              <tr>
                <td colspan="8">Henüz hasta kaydı yok.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel" id="doktor-ekle">
      <div class="panel-head"><div><h2>Doktor Ekle</h2><div class="panel-desc">Doktorlar tablosuna yeni kayıt ekleyin.</div></div></div>

      <form action="actions/doktor_ekle.php" method="POST">
        <div class="form-grid">
          <label>
            TC No
            <input type="text" name="tc_no" maxlength="11" required>
          </label>

          <label>
            Şifre
            <input type="password" name="sifre" required>
          </label>

          <label>
            İsim
            <input type="text" name="isim" required>
          </label>

          <label>
            Telefon No
            <input type="text" name="tel_no" required>
          </label>

          <label>
            Uzmanlık
            <input type="text" name="uzmanlik" required>
          </label>

          <label>
            Poliklinik ID
            <input type="text" name="poliklinik_id" required>
          </label>
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary">Doktor Ekle</button>
          <button type="reset" class="btn btn-secondary">Temizle</button>
        </div>
      </form>
    </section>

    <section class="panel" id="doktor-liste">
      <div class="panel-head">
        <div>
          <h2>Doktor Listele / Düzenle</h2>
          <div class="panel-desc">Kayıtlı doktorlar aşağıda listelenir. Düzenleme veya silme işlemi yapabilirsiniz.</div>
        </div>
        <div class="table-tools">
          <label class="table-search" aria-label="Doktor TC arama">
            <input id="doktorTcArama" data-table-search="#doktor-table" data-search-column="1" type="search" inputmode="numeric" autocomplete="off" placeholder="Doktor TC ara...">
          </label>
        </div>
      </div>

      <div class="table-wrap">
        <table id="doktor-table">
          <thead>
            <tr>
              <th>Doktor ID</th>
              <th>TC No</th>
              <th>Şifre Hash</th>
              <th>İsim</th>
              <th>Telefon</th>
              <th>Uzmanlık</th>
              <th>Poliklinik ID</th>
              <th>İşlem</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($doktorlar as $doktor): ?>
              <tr>
                <td><?php echo htmlspecialchars($doktor["doktor_id"]); ?></td>
                <td><?php echo htmlspecialchars($doktor["tc_no"]); ?></td>
                <td class="hash-cell"><?php echo htmlspecialchars($doktor["sifre_hash"]); ?></td>
                <td><?php echo htmlspecialchars($doktor["isim"]); ?></td>
                <td><?php echo htmlspecialchars($doktor["tel_no"]); ?></td>
                <td><?php echo htmlspecialchars($doktor["uzmanlık"]); ?></td>
                <td><?php echo htmlspecialchars($doktor["poliklinik_id"]); ?></td>
                <td>
                  <div class="islem-butonlari">
  <a class="edit-btn" href="actions/doktor_duzenle.php?id=<?php echo htmlspecialchars($doktor["doktor_id"]); ?>">
    Düzenle
  </a>

  <form action="actions/doktor_sil.php" method="POST" class="delete-form" data-confirm-message="Bu doktoru silmek istediğine emin misin?">
    <input type="hidden" name="doktor_id" value="<?php echo htmlspecialchars($doktor["doktor_id"]); ?>">
    <button type="submit">Sil</button>
  </form>
</div>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if (count($doktorlar) == 0): ?>
              <tr>
                <td colspan="8">Henüz doktor kaydı yok.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>


    <section class="panel" id="poliklinik-ekle">
      <div class="panel-head">
        <div>
          <h2>Poliklinik Ekle</h2>
          <div class="panel-desc">Poliklinikler tablosuna yeni poliklinik kaydı ekleyin.</div>
        </div>
      </div>

      <form action="actions/poliklinik_ekle.php" method="POST">
        <div class="form-grid">
          <label>
            Poliklinik Adı
            <input type="text" name="poliklinik_ad" required>
          </label>

          <label>
            Lokasyon
            <input type="text" name="lokasyon" placeholder="Örn: B blok 5. kat" required>
          </label>

          <label class="full-field">
            Açıklama
            <textarea name="aciklama" rows="4" placeholder="Poliklinik hakkında kısa açıklama yazın." required></textarea>
          </label>
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary">Poliklinik Ekle</button>
          <button type="reset" class="btn btn-secondary">Temizle</button>
        </div>
      </form>
    </section>

    <section class="panel" id="poliklinik-liste">
      <div class="panel-head">
        <div>
          <h2>Poliklinik Listele / Düzenle</h2>
          <div class="panel-desc">Kayıtlı poliklinikler aşağıda listelenir. Düzenleme veya silme işlemi yapabilirsiniz.</div>
        </div>
        <div class="table-tools">
          <label class="table-search" aria-label="Poliklinik adı arama">
            <input id="poliklinikArama" data-table-search="#poliklinik-table" data-search-column="1" type="search" autocomplete="off" placeholder="Poliklinik adı ara...">
          </label>
        </div>
      </div>

      <div class="table-wrap">
        <table id="poliklinik-table" class="poliklinik-table">
          <thead>
            <tr>
              <th>Poliklinik ID</th>
              <th>Poliklinik Adı</th>
              <th>Lokasyon</th>
              <th>Açıklama</th>
              <th>İşlem</th>
            </tr>
          </thead>

          <tbody>
            <?php foreach ($poliklinikler as $poliklinik): ?>
              <tr>
                <td><?php echo htmlspecialchars($poliklinik["poliklinik_id"]); ?></td>
                <td><?php echo htmlspecialchars($poliklinik["poliklinik_ad"]); ?></td>
                <td><?php echo htmlspecialchars($poliklinik["lokasyon"]); ?></td>
                <td class="aciklama-cell"><?php echo htmlspecialchars($poliklinik["aciklama"]); ?></td>
                <td>
                  <div class="islem-butonlari">
                    <a class="edit-btn" href="actions/poliklinik_duzenle.php?id=<?php echo htmlspecialchars($poliklinik["poliklinik_id"]); ?>">
                      Düzenle
                    </a>

                    <form action="actions/poliklinik_sil.php" method="POST" class="delete-form" data-confirm-message="Bu polikliniği silmek istediğine emin misin?">
                      <input type="hidden" name="poliklinik_id" value="<?php echo htmlspecialchars($poliklinik["poliklinik_id"]); ?>">
                      <button type="submit">Sil</button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if (count($poliklinikler) == 0): ?>
              <tr>
                <td colspan="5">Henüz poliklinik kaydı yok.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section class="panel" id="sifre-degistir">
      <div class="panel-head">
        <div>
          <h2>Şifre Değiştir</h2>
          <div class="panel-desc">Admin hesabınızın giriş şifresini buradan güvenli şekilde değiştirebilirsiniz.</div>
        </div>
      </div>

      <form action="actions/admin_sifre_degistir.php" method="POST" autocomplete="off" class="password-form">
        <div class="form-grid password-grid">
          <label>
            Mevcut Şifre
            <input type="password" name="mevcut_sifre">
          </label>

          <label>
            Yeni Şifre
            <input type="password" name="yeni_sifre">
          </label>

          <label>
            Yeni Şifre Tekrar
            <input type="password" name="yeni_sifre_tekrar">
          </label>
        </div>

        <div class="password-note">
          Şifre değişikliği yapılabilmesi için mevcut şifrenin doğru girilmesi gerekir. Yeni şifre iki alanda aynı olmalıdır.
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary">Şifreyi Güncelle</button>
          <button type="reset" class="btn btn-secondary">Temizle</button>
        </div>
      </form>
    </section>

  </main>
</div>

<div class="confirm-overlay" id="confirmOverlay" aria-hidden="true">
  <div class="confirm-box" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
    <div class="confirm-icon">!</div>
    <h3 id="confirmTitle">Silme işlemi</h3>
    <p id="confirmMessage">Bu kaydı silmek istediğine emin misin?</p>

    <div class="confirm-actions">
      <button type="button" class="confirm-cancel" id="confirmCancel">Vazgeç</button>
      <button type="button" class="confirm-delete" id="confirmDelete">Evet, sil</button>
    </div>
  </div>
</div>

<script src="../assets/js/admin_panel.js?v=20260508-poliklinik"></script>
</body>
</html>
