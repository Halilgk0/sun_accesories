# Sun Accesories

İzmir'deki atölyenin takı vitrini. Sipariş almaz, ödeme almaz, ziyaretçiden
hiçbir bilgi istemez — parçaları gösterir ve ilgilenen kişiyi Instagram'dan
yazmaya yönlendirir.

**Canlı adres:** https://sun-accesories.vercel.app

---

## Katalog paneline nasıl girilir

Ürünleri ve kategorileri değiştirdiğin yer. Sitenin hiçbir yerinden bağlantı
verilmez, arama motorlarına kapalıdır; yalnızca adresini bilen bulur.

### 1. Adrese git

```
https://sun-accesories.vercel.app/atolye-9da59c40bdd67f
```

Bu adresi tarayıcına yer imi olarak kaydet. Uzun ve rastgele olması kasıtlı:
panelin saklı kalmasının ilk yarısı bu.

> Adresi unutursan: Vercel → **sun-accesories** → Settings → Environment
> Variables → `ADMIN_PATH` değişkenindedir. Sitenin adresinin sonuna eklersin.

### 2. Şifreyi gir

Karşına **"Katalog yönetimi"** ekranı çıkar. Şifreyi yaz, **Gir**'e bas.

Şifre bu dosyada **yazmaz** ve yazmamalı: README her kopyada, her yedekte ve
git geçmişinde kalıcı olarak durur. Şifreni bir parola yöneticisinde sakla.

> Şifreyi kaybedersen geri okuyamazsın — Vercel'de "sensitive" olarak
> saklanıyor. Bu durumda aşağıdaki **Şifreyi değiştirmek** adımlarını izleyip
> yenisini koyarsın.

Yanlış şifre girersen uyarır. Dakikada 5 denemeden fazlasına izin verilmez.

### 3. İşini yap

Giriş yapınca **Katalog** listesi açılır. Her satırda parçanın fotoğrafı,
adı, kategorisi, fiyatı ve stoğu görünür.

| Yapmak istediğin | Nereye basarsın |
| --- | --- |
| Yeni parça eklemek | Sağ üstteki **Yeni parça ekle** |
| Bir parçayı değiştirmek | Satırdaki **Düzenle** |
| Bir parçayı kaldırmak | Satırdaki **Sil** (önce onay sorar, geri alınamaz) |
| Parçanın sitedeki hâlini görmek | Satırdaki **Sayfasını aç** |
| Kategori eklemek / düzenlemek | Üstteki **Kategoriler** |

**Fotoğraf:** düzenleme ekranındaki *Fotoğraf yükle* alanından telefonundan ya
da bilgisayarından seçersin (en fazla 4 MB · JPG, PNG, WEBP, AVIF). Seçer
seçmez önizlemede görünür. İstersen daha önce yüklediğin fotoğraflardan da
seçebilirsin.

**Adres alanı:** yayındaki bir parçanın adresini değiştirirsen o parçaya giden
bütün bağlantılar kırılır. Form bu yüzden yayındaki parçalarda adresi
kendiliğinden değiştirmez.

**Kategori silme:** içinde parça olan kategori silinemez. Önce o parçaları
başka bir kategoriye taşıman gerekir.

### 4. Çıkarken

Sağ üstteki **Çık**'a bas. Özellikle ortak ya da başkasının bilgisayarından
girdiysen bunu atlama.

---

## Şifreyi değiştirmek

1. Vercel → **sun-accesories** → Settings → Environment Variables
2. `ADMIN_PASSWORD` satırında **Edit**
3. Yeni şifreyi yaz, kaydet
4. Deployments → en üstteki yayında `⋯` → **Redeploy**

Değişiklik ancak yeniden yayından sonra geçerli olur. Aynı yoldan `ADMIN_PATH`
ile panelin adresini de değiştirebilirsin.

---

## Yeni yayın sonrası: "Veritabanını hazırla"

Veritabanı yapısını değiştiren bir güncelleme çıktığında site, yeni tablolar
oluşturulana kadar hata verir. Düzeltmesi tek adım:

1. Panele gir
2. Üstteki **Veritabanını hazırla**ya bas

Bu işlemi birden çok kez çalıştırmak zararsızdır; mevcut parçaların silinmez.

> Neden otomatik değil: Vercel'de yayın ile trafik arasında bir ara adım yok.
> Bunu konteyner açılışına koymak, açılışı bekleyen isteklerin zaman aşımına
> uğramasına yol açıyordu.

---

## Ortam değişkenleri

Vercel → Settings → Environment Variables altında tanımlı.

| Değişken | Ne işe yarar |
| --- | --- |
| `ADMIN_PATH` | Panelin adresi. Boş bırakılırsa panel hiç var olmaz. |
| `ADMIN_PASSWORD` | Panelin şifresi. Boş bırakılırsa panel açılmaz. |
| `CONTACT_INSTAGRAM` | "Bu parça için yaz" düğmesinin açtığı hesap (yalnızca kullanıcı adı). |
| `DB_CONNECTION` | `pgsql` — Neon Postgres. |
| `DATABASE_URL` | Neon entegrasyonunun kendi eklediği bağlantı bilgisi. |

---

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


php artisan serve --host=192.168.1.103 --port=8000