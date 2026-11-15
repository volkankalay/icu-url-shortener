# ICU Shortener

ICU Shortener, uzun URL’leri kısa bağlantılara dönüştüren ve ziyaretleri raporlayan bir Laravel 12 uygulamasıdır. Bağlantıları web panelinden veya Bearer token ile API üzerinden oluşturabilirsin.

## Özellikler

- HTTP ve HTTPS adresleri için büyük-küçük harfe duyarlı, dört karakterli kısa kodlar. Panelden özel kısa kod belirlenebilir. Uygulama rotalarıyla çakışan kodlar ayrılır.
- Bağlantı listesi, kopyalama, düzenleme ve son yedi günün ziyaret grafiği.
- Toplam yönlendirme ve tekil ziyaret sayaçları. Aynı IP, aynı bağlantı için günde bir ziyaret kaydı oluşturur.
- IPv4 ve IPv6, tarayıcı, işletim sistemi, cihaz, yönlendiren alan adı ve tarayıcı dilini içeren ziyaret geçmişi. Mevcut bilgiler ziyaret kaydının `options` JSON alanında tutulur.
- Yönetici, editör ve üye hesapları. Editörler tüm bağlantıları görebilir ve kısa kodlarını düzenleyebilir. Kullanıcı yönetimi, bağlantıları devre dışı bırakma ve site ayarları yöneticilere açıktır.
- Mobil uyumlu panel, gündüz ve gece temaları, özelleştirilebilir logo ve favicon.

## Gereksinimler

- PHP 8.4.27 veya üzeri bir PHP 8.x sürümü ve Composer 2.
- Yerel kurulum için MySQL veya MariaDB ve PHP `pdo_mysql` uzantısı. Testler için `pdo_sqlite`.
- `storage` ve `bootstrap/cache` dizinlerine yazma izni.

Ön yüz dosyaları `public` dizininde hazır bulunur. Node.js kurulumu veya derleme adımı gerekmez.

## Yerel kurulum

1. Depoyu klonla ve bağımlılıkları kur.

   ```sh
   git clone https://github.com/volkankalay/icu-url-shortener.git
   cd icu-url-shortener
   composer install
   ```

2. `.env.example` dosyasını `.env` olarak kopyala ve uygulama anahtarını oluştur.

   ```powershell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

   Bash kullanıyorsan kopyalama komutu `cp .env.example .env`.

3. MySQL veya MariaDB’de boş bir veritabanı oluştur. `.env` dosyasındaki adresi ve bağlantı bilgilerini kendi ortamına göre düzenle.

   ```dotenv
   APP_NAME="ICU Shortener"
   APP_URL=http://127.0.0.1:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=icu_shortener
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Tabloları ve yerel demo verilerini oluştur, ardından uygulamayı başlat.

   ```sh
   php artisan migrate --seed
   php artisan serve
   ```

   İlk seed işlemi site ayarlarının kısa bağlantı alan adını `APP_URL` üzerinden belirler. Daha sonra **Yönetim → Site ayarları** bölümünden değiştirebilirsin. Laravel Herd kullanıyorsan `APP_URL` değerini Herd’in verdiği site adresine ayarla ve Herd üzerinden aç.

### Demo hesapları

| Hesap türü | Kullanıcı adı | E-posta |
| --- | --- | --- |
| Yönetici | `admin` | `admin@demo.com` |
| Editör | `editor` | `editor@demo.com` |
| Üye | `regular` | `regular@demo.com` |

Demo hesaplarının ortak parolası `123456`. `php artisan migrate --seed` komutunu yerel geliştirme ortamında kullan.

### Canlı kurulum

Web sunucusunun belge kökünü `public` dizinine ayarla. `.env` dosyasında `APP_ENV=production`, `APP_DEBUG=false` ve sitenin HTTPS adresini tanımla. Boş veritabanında tabloları ve başlangıç site ayarlarını oluştur.

```sh
php artisan migrate --force
php artisan db:seed --class=SettingSeeder --force
```

İlk yönetici için kayıt formundan bir hesap oluştur. Ardından `php artisan tinker` içinde hesabı kendi e-posta adresinle bulup yönetici yap.

```php
$user = \App\Models\User::where('email', 'yonetici@example.com')->firstOrFail();
$user->user_type = \App\Enums\UserType::ADMIN;
$user->save();
```

## API

İstekleri `Content-Type: application/json` başlığıyla gönder.

1. **`POST /api/auth/token`** isteğinde `username` veya `email` alanlarından birini `password` ile birlikte gönder.

   ```json
   {"username":"admin","password":"123456"}
   ```

   `200 OK` yanıtı `access_token`, `token_type` ve `expires_in` döndürür. Bearer token bir saat geçerlidir ve uygulama önbelleğinde saklanır.

2. **`POST /api/links`** isteğine `Authorization: Bearer <access_token>` başlığını ve hedef URL’yi ekle.

   ```json
   {"url":"https://example.com/article"}
   ```

   `201 Created` yanıtı kısa adresi döndürür. Aynı hesap aynı URL’yi tekrar gönderirse mevcut kısa adres döner.

   ```json
   {"success":true,"short_url":"http://127.0.0.1:8000/09aZ"}
   ```

Geçersiz giriş bilgileri veya geçersiz ya da süresi dolmuş token için `401`, doğrulama hataları için `422` döner. Giriş kapalıysa token isteği `403` döner.

## Geliştirme

```sh
php artisan test
composer format:dirty
composer format
```

Testler bellek içi SQLite kullanır. `format:dirty` commit edilmemiş PHP dosyalarını, `format` ise Pint kapsamındaki tüm PHP dosyalarını biçimlendirir.
