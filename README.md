# PHP & MySQL: Hastane Otomasyon Sistemi (Hospital Management System)

Bu proje, hastane içindeki hasta, doktor ve yönetici işlemlerini tek bir sistem altında toplamak ve kayıt süreçlerini dijitalleştirmek amacıyla geliştirilmiş web tabanlı bir otomasyon sistemidir. Proje, sağlık sektöründeki veri yönetimi ve rol bazlı erişim problemlerine güvenli bir çözüm sunmayı hedefler.

### 🛠️ Sistem Mimarisi ve Tasarım Prensipleri

Uygulama, sürdürülebilir ve güvenli bir yapı için modern web standartlarıyla inşa edilmiştir:
* **İlişkisel Veri Tabanı Tasarımı:** Veri bütünlüğünü ve tutarlılığını sağlamak amacıyla MySQL kullanılarak normalize edilmiş tablolar (Hasta, Doktor, Admin vb.) tasarlanmıştır.
* **Kriptografik Veri Güvenliği:** Kullanıcı ve yönetici şifreleri veritabanında düz metin yerine güvenli özetleme (hash) algoritmalarıyla tutulmaktadır. Doğrulama süreçlerinde `password_verify` kullanılmıştır.
* **SQL Injection Koruması:** Veritabanı sorgularında dışarıdan gelen müdahaleleri engellemek için PDO (PHP Data Objects) ile hazırlanmış (prepared) sorgular tercih edilmiştir.
* **Rol Bazlı Oturum Yönetimi (RBAC):** PHP Session yapısı ile hasta, doktor ve admin için bağımsız yetkilendirme sağlanmış, yetkisiz sayfa erişimleri engellenmiştir.

### 🚀 Fonksiyonel Özellikler

* **Çoklu Giriş Sistemi:** Hasta, Doktor ve Yönetici (Admin) için TC Kimlik / Kullanıcı Adı bazlı özelleştirilmiş ve ayrılmış giriş panelleri.
* **Kapsamlı Yönetici (Admin) Paneli:** Hasta ve doktor kayıtlarının sisteme eklenmesi, listelenmesi, düzenlenmesi ve silinmesi (CRUD) işlemleri.
* **Dinamik Hata Yönetimi (Robustness):** Boş alan bırakma, hatalı kullanıcı adı veya yanlış şifre girişlerinde anlık çalışan kullanıcı dostu bildirimler (Toast Notifications).
* **Ölçeklenebilir Altyapı:** Randevu alma, muayene süreci, tanı koyma ve reçete yazma gibi ileride eklenecek modüller için hazır veritabanı iskeleti.

### 🎨 Kullanıcı Arayüzü (GUI)

* **Modern ve Responsive Web Tasarımı:** Tüm cihazlarla uyumlu, temiz ve anlaşılır CSS yapısı (`bg-grid` destekli arayüz).
* **Merkezi Yönlendirme:** Kullanıcıların karmaşa yaşamadan tek bir ana sayfa (index.html) üzerinden kendi rollerine uygun panele erişebildiği akıcı kullanıcı deneyimi.
* **Etkileşimli Bildirimler:** JavaScript destekli form doğrulama (validation) ve dinamik hata mesajları.

### 🛠️ Geliştirme Ortamı

* **Frontend (İstemci):** HTML5, CSS3, JavaScript (Vanilla)
* **Backend (Sunucu):** PHP 
* **Veri Tabanı:** MySQL (PDO - PHP Data Objects)
* **Mimari:** İstemci-Sunucu (Client-Server) Modeli
