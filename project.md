1. Gambaran sistem DiSekolahKu

Secara konsep, ini adalah SaaS School Management System.

Aktor utamanya kemungkinan:

SUPER ADMIN / PLATFORM
        │
        ├── Sekolah A
        │     ├── Admin/TU
        │     ├── Kepala Sekolah
        │     ├── Guru
        │     ├── Siswa
        │     └── Orang Tua
        │
        ├── Sekolah B
        └── Sekolah C

Menariknya, mereka menyebut satu akun dapat mengelola beberapa sekolah, sehingga arsitekturnya mengarah ke multi-tenant SaaS.

2. Modul utama yang perlu kita bangun

Saya akan pecah menjadi sekitar 10 modul inti.

A. Manajemen Sekolah

Data:

NPSN
nama sekolah
jenjang
status negeri/swasta
provinsi
kabupaten/kota
kecamatan
desa/kelurahan
alamat
kepala sekolah
tahun ajaran
logo sekolah

Form pendaftaran publiknya sendiri sudah menggunakan NPSN, nama sekolah, jenjang, status, dan wilayah administratif.

B. Manajemen Pengguna

Role:

Platform Admin
   ↓
School Owner/Admin
   ↓
Kepala Sekolah
   ↓
Guru
   ↓
Wali Kelas
   ↓
Siswa
   ↓
Orang Tua/Wali

Yang penting adalah Role & Permission.

Misalnya:

Fitur	Admin	Kepala	Guru	Orang Tua
Kelola siswa	✓	✓	-	-
Absensi	✓	✓	✓	lihat
Jadwal	✓	✓	✓	lihat
Pelanggaran	✓	✓	✓	lihat
Laporan	✓	✓	terbatas	-
3. Absensi — kemungkinan menjadi core system

Ini salah satu fitur utama yang ditonjolkan DiSekolahKu.

Ada dua jenis:

Absensi harian
Siswa datang
     ↓
Check-in
     ↓
Sistem mencatat waktu
     ↓
Status:
Hadir / Terlambat / Izin / Sakit / Alpha
     ↓
Orang tua mendapatkan notifikasi
Absensi per mata pelajaran

Misalnya:

07.30 - Matematika
08.15 - Bahasa Indonesia
09.00 - IPA

Guru melakukan absensi pada kelas yang dia ajar.

Website juga menyebutkan rekapan kehadiran masuk/pulang serta kehadiran setiap pelajaran.

4. Jadwal Pelajaran

Database sederhananya:

Tahun Ajaran
     ↓
Kelas
     ↓
Hari
     ↓
Jam
     ↓
Mata Pelajaran
     ↓
Guru

Contoh:

Senin

07:30 - 08:15
Matematika
Guru: Ahmad
Kelas: VII-A

08:15 - 09:00
Bahasa Indonesia
Guru: Siti
Kelas: VII-A

Ini kemudian terhubung langsung ke absensi pelajaran.

5. Sistem Pelanggaran

Ini juga merupakan salah satu fitur utama platform tersebut.

Kita bisa membuat:

Jenis Pelanggaran
├── Ringan
├── Sedang
└── Berat

Masing-masing mempunyai poin.

Contoh:

Terlambat        5 poin
Tidak memakai atribut 10 poin
Tidak masuk tanpa keterangan 15 poin
Membawa barang terlarang 30 poin

Kemudian:

Siswa
 ↓
Pelanggaran
 ↓
Poin
 ↓
Riwayat
 ↓
Notifikasi Orang Tua
6. Aplikasi Orang Tua

Ini bagian yang menurut saya sangat penting kalau kita ingin membuat produk sendiri.

Dashboard orang tua:

┌─────────────────────────┐
│ Selamat datang, Bapak   │
│                         │
│ Anak: Ahmad             │
│ Kelas: VIII-A           │
├─────────────────────────┤
│ 🟢 Hadir                │
│                         │
│ Masuk 07:12             │
├─────────────────────────┤
│ 📅 Jadwal Hari Ini      │
├─────────────────────────┤
│ ⚠️ Pelanggaran          │
├─────────────────────────┤
│ 📊 Kehadiran            │
└─────────────────────────┘

DiSekolahKu sendiri menyebut aplikasi mobile untuk guru dan orang tua serta notifikasi kedatangan, kepulangan, masuk pelajaran, dan pelanggaran.

7. Notifikasi

Kita jangan hanya membuat sistem administrasi.

Buat notification engine.

Contohnya:

EVENT
 ↓
Student Checked In
 ↓
Notification Service
 ↓
Parent
 ↓
Push Notification

Notifikasi:

Ahmad telah tiba di sekolah pukul 07.12.

Kemudian:

Ahmad terlambat masuk kelas Matematika pukul 08.03.

Atau:

Ahmad mendapatkan catatan pelanggaran 10 poin.

8. Dashboard Kepala Sekolah

Ini yang akan membuat sistem terlihat profesional.

Dashboard:

JUMLAH SISWA
1.248

KEHADIRAN HARI INI
94,7%

TERLAMBAT
37

IZIN
12

SAKIT
18

ALPHA
9

Kemudian grafik:

Kehadiran 7 Hari
██████████  Senin
█████████   Selasa
██████████  Rabu
████████    Kamis
██████████  Jumat

Dan daftar:

Siswa dengan keterlambatan tertinggi

Kelas dengan kehadiran terendah

Pelanggaran bulan ini

Ini akan memberikan nilai lebih dibanding sekadar aplikasi absensi.

9. Laporan

Sistem harus bisa menghasilkan:

Laporan siswa
kehadiran
keterlambatan
izin
sakit
alpha
pelanggaran
Laporan kelas
persentase kehadiran
rekap siswa
rekap pelajaran
Laporan sekolah
statistik seluruh siswa
statistik guru
statistik kehadiran
statistik pelanggaran

Output:

PDF
Excel
Print
10. Multi-School / Multi-Tenant

Ini menurut saya bagian paling penting secara teknis.

Jangan membuat:

Database Sekolah A
Database Sekolah B
Database Sekolah C

Lebih baik:

users
schools
school_users
students
teachers
classes
subjects
schedules
attendance
violations
notifications
...

Hampir semua tabel operasional memiliki:

school_id

Sehingga:

User
 ↓
School
 ↓
Data sekolah tersebut

Tetapi user tertentu bisa mempunyai:

User: Yayasan ABC

School:
├── SMA ABC
├── SMP ABC
└── SD ABC

Konsep ini sejalan dengan klaim DiSekolahKu tentang satu akun untuk mengelola banyak unit sekolah.

11. Arsitektur yang saya rekomendasikan

Kalau kita yang membangun, saya akan buat seperti:

                    INTERNET
                       │
                 Cloudflare
                       │
                Load Balancer
                       │
              ┌───────────────┐
              │   Web App     │
              │   Dashboard   │
              └───────┬───────┘
                      │
                 REST API
                      │
          ┌───────────┴───────────┐
          │                       │
      PostgreSQL              Redis
          │                       │
          │                  Queue/Cache
          │                       │
          └───────────┬───────────┘
                      │
               Notification
                  Service
                 /        \
              FCM       WhatsApp
Frontend

Saya akan pilih:

Next.js / React

Backend

Bisa:

Laravel

atau

NestJS

Kalau target kita adalah cepat jadi dan mudah dikelola tim Indonesia, Laravel sangat masuk akal.

Database

PostgreSQL

Mobile

Awalnya:

Flutter

supaya Android bisa dibuat lebih cepat.

12. Tetapi jangan langsung membuat semuanya

Kalau kita benar-benar ingin membangun produk ini, saya justru menyarankan MVP terlebih dahulu.

V1
1. Multi-school
2. Login
3. Manajemen siswa
4. Manajemen guru
5. Kelas
6. Mata pelajaran
7. Jadwal
8. Absensi harian
9. Absensi pelajaran
10. Dashboard
11. Laporan
V2
12. Orang tua
13. Mobile app
14. Push notification
15. Pelanggaran
16. Poin
17. Pengumuman
V3
18. Pembayaran sekolah
19. SPP
20. Ujian/nilai
21. Raport
22. PPDB
23. Perpustakaan
24. Inventaris
13. Ada satu hal yang saya ingin kita lakukan berbeda

Jangan membuat "DiSekolahKu versi kita".

Kita bisa membuat:

platform operasional sekolah yang lebih modular dan bisa dikustomisasi untuk sekolah Indonesia.

Misalnya sekolah dapat mengaktifkan:

☑ Absensi
☑ Jadwal
☑ Pelanggaran
☑ Orang Tua
☐ SPP
☐ Nilai
☐ PPDB
☐ Perpustakaan

Sehingga sekolah SD tidak harus membeli fitur SMA.

14. Model bisnisnya juga sudah terlihat

DiSekolahKu menggunakan model berlangganan dan menyediakan paket bulanan/tahunan, serta program afiliasi dengan komisi bertingkat.

Untuk produk kita bisa menggunakan:

Starter

Rp X / sekolah / bulan

Professional

Rp X / sekolah / bulan

Enterprise/Yayasan

Custom.

Dan yang menarik:

Sekolah
   ↓
Berlangganan
   ↓
Admin sekolah
   ↓
Guru
   ↓
Orang tua

Jadi bukan menjual aplikasi ke individu, tetapi menjual sistem kepada sekolah.

Kesimpulan analisis awal

Menurut saya, jangan mulai dari desain UI.

Urutan profesionalnya:

1. Requirement → 2. Use Case → 3. Database/ERD → 4. Role & Permission → 5. API → 6. Dashboard → 7. Mobile → 8. Notification → 9. Billing → 10. Deployment.

Saya sudah mendapatkan gambaran cukup jelas dari halaman publik DiSekolahKu, termasuk modul absensi, jadwal, pelanggaran, aplikasi orang tua/guru, multi-sekolah, laporan, subscription, dan affiliate.

Kalau kita serius membangun sistem ini dari nol, langkah berikutnya yang paling tepat adalah saya buatkan blueprint sistem lengkap: ERD/database, seluruh tabel dan field, role-permission, alur setiap modul, struktur API, struktur dashboard Admin/Guru/Kepala Sekolah/Orang Tua, serta roadmap development. Itu akan menjadi dokumen teknis yang bisa langsung dipakai sebagai dasar coding.

# BLUEPRINT SISTEM

## School Management & Digital School Platform

**Dokumen:** System Blueprint & Technical Product Specification
**Versi:** 1.0
**Status:** Draft Technical Baseline
**Target:** Web Application + Mobile Application
**Model:** Multi-Tenant SaaS
**Target pengguna:** Sekolah, Yayasan, Guru, Siswa, Orang Tua/Wali, Administrator Platform

---

# 1. VISI PRODUK

Platform ini dirancang sebagai sistem operasi digital sekolah yang mengintegrasikan:

* manajemen sekolah
* siswa
* guru
* kelas
* mata pelajaran
* jadwal
* absensi
* monitoring pembelajaran
* kedisiplinan
* pelanggaran
* komunikasi sekolah-orang tua
* notifikasi
* laporan
* publikasi sekolah
* subscription
* multi-school management

Prinsip utama:

> **Satu platform, satu sumber data, banyak sekolah, banyak peran, real-time, aman, dan terukur.**

---

# 2. SASARAN SISTEM

## 2.1 Sasaran utama

Sistem harus mampu:

1. Mengurangi administrasi manual.
2. Menyatukan data sekolah.
3. Menyediakan informasi real-time.
4. Menghubungkan sekolah dengan orang tua.
5. Menghasilkan laporan otomatis.
6. Mendukung banyak sekolah dalam satu platform.
7. Memiliki audit trail.
8. Bisa dikembangkan menjadi SaaS berskala nasional.

---

# 3. MODEL BISNIS

Platform menggunakan model:

```text
PLATFORM
   │
   ├── FREE
   │
   ├── BASIC
   │
   ├── PROFESSIONAL
   │
   ├── SCHOOL
   │
   └── ENTERPRISE / YAYASAN
```

Contoh:

### FREE

* maksimal X siswa
* absensi dasar
* siswa
* guru
* kelas

### BASIC

* semua FREE
* jadwal
* laporan
* notifikasi

### PROFESSIONAL

* semua BASIC
* aplikasi orang tua
* pelanggaran
* monitoring
* analytics

### ENTERPRISE

* multi-school
* yayasan
* custom domain
* API
* dedicated support
* SLA
* integrasi tambahan

---

# 4. ARSITEKTUR PRODUK

Sistem dibagi menjadi 4 lapisan utama.

```text
                    USER
                      │
        ┌─────────────┼─────────────┐
        │             │             │
      WEB           MOBILE        API
        │             │             │
        └─────────────┼─────────────┘
                      │
                 API GATEWAY
                      │
             APPLICATION SERVER
                      │
       ┌──────────────┼──────────────┐
       │              │              │
   PostgreSQL       Redis          Queue
       │              │              │
       └──────────────┼──────────────┘
                      │
              EXTERNAL SERVICES
          ┌───────────┼───────────┐
          │           │           │
        FCM        WhatsApp      Email
          │           │           │
          └───────────┼───────────┘
                      │
                 Object Storage
```

---

# 5. REKOMENDASI TECHNOLOGY STACK

## Backend

**Laravel**

Alasan:

* mature
* ekosistem besar
* mudah mencari developer
* cocok untuk SaaS
* authentication matang
* queue
* scheduler
* notification
* API
* authorization

## Frontend Web

**Next.js / React**

Alternatif:

Laravel + Vue.

## Mobile

**Flutter**

Satu codebase:

```text
Flutter
 ├── Android
 └── iOS
```

## Database

**PostgreSQL**

## Cache

**Redis**

## Queue

Redis Queue / Laravel Horizon.

## Storage

S3-compatible object storage.

## CDN

Cloudflare.

## Container

Docker.

## Server

Cloud VPS / managed cloud.

---

# 6. ARSITEKTUR MULTI-TENANT

Ini harus dirancang sejak awal.

Struktur:

```text
PLATFORM
│
├── ORGANIZATION / YAYASAN
│      │
│      ├── SCHOOL A
│      │
│      ├── SCHOOL B
│      │
│      └── SCHOOL C
│
└── SCHOOL INDEPENDENT
```

Setiap data sekolah harus mempunyai:

```text
school_id
```

Contoh:

```text
students
---------
id
school_id
student_number
name
...
```

Jangan mengandalkan frontend untuk membatasi data.

Backend harus selalu menerapkan tenant isolation.

---

# 7. ORGANIZATION MODEL

Entity utama:

```text
Platform
   │
Organization
   │
School
   │
Academic Year
   │
Class
   │
Student
```

Contoh:

```text
Yayasan Pendidikan ABC
│
├── SD ABC
├── SMP ABC
└── SMA ABC
```

Satu administrator yayasan dapat berpindah sekolah.

---

# 8. USER & ROLE

Role dasar:

```text
SUPER ADMIN
PLATFORM ADMIN
ORGANIZATION ADMIN
SCHOOL OWNER
SCHOOL ADMIN
HEADMASTER
TEACHER
HOMEROOM TEACHER
COUNSELOR
STAFF
STUDENT
PARENT
AFFILIATE
```

Jangan membuat permission hanya berdasarkan role.

Gunakan:

```text
ROLE
   +
PERMISSION
   +
SCOPE
```

Contoh:

```text
Teacher
permission:
attendance.create

scope:
school_id = X
```

---

# 9. PERMISSION MATRIX

Contoh:

| Modul       | Super Admin | Admin Sekolah | Kepala | Guru       | Orang Tua |
| ----------- | ----------- | ------------- | ------ | ---------- | --------- |
| Sekolah     | CRUD        | R             | R      | -          | -         |
| Guru        | CRUD        | CRUD          | R      | R          | -         |
| Siswa       | CRUD        | CRUD          | R      | R terbatas | R anak    |
| Kelas       | CRUD        | CRUD          | R      | R          | R         |
| Jadwal      | CRUD        | CRUD          | R      | R          | R         |
| Absensi     | CRUD        | CRUD          | R      | CRUD       | R         |
| Pelanggaran | CRUD        | CRUD          | R      | CRUD       | R         |
| Laporan     | CRUD        | CRUD          | R      | R terbatas | R anak    |
| Billing     | CRUD        | R             | R      | -          | -         |

---

# 10. MODUL PLATFORM ADMIN

Dashboard:

```text
┌────────────────────────────────────┐
│ PLATFORM ADMIN                     │
├────────────────────────────────────┤
│ Sekolah       1,245                │
│ Siswa         487,230              │
│ Guru          28,420               │
│ Revenue       Rp XXX               │
├────────────────────────────────────┤
│ Active Schools                     │
│ Subscription                       │
│ Payment                            │
│ System Health                      │
└────────────────────────────────────┘
```

Menu:

* Dashboard
* Organizations
* Schools
* Users
* Plans
* Subscriptions
* Payments
* Coupons
* Affiliates
* Notifications
* Support
* Audit Logs
* System Settings

---

# 11. MODUL SEKOLAH

Data:

```text
schools
```

Field utama:

```text
id
organization_id
npsn
name
slug
education_level
school_status
province_id
regency_id
district_id
village_id
address
postal_code
phone
email
website
logo
cover_image
timezone
status
created_at
updated_at
```

---

# 12. TAHUN AJARAN

Entity:

```text
academic_years
```

Contoh:

```text
2026/2027
```

Field:

```text
id
school_id
name
start_date
end_date
is_active
status
```

Hanya satu tahun ajaran aktif per sekolah.

---

# 13. SEMESTER

```text
semesters
```

Field:

```text
id
academic_year_id
name
type
start_date
end_date
is_active
```

Contoh:

```text
Ganjil
Genap
```

---

# 14. DATA SISWA

Table:

```text
students
```

Field:

```text
id
school_id
student_number
nis
nisn
nik
full_name
gender
birth_place
birth_date
religion
address
phone
email
photo
status
admission_date
graduation_date
created_at
updated_at
deleted_at
```

Data sensitif harus dilindungi.

---

# 15. DATA ORANG TUA

```text
parents
```

Field:

```text
id
user_id
father_name
mother_name
guardian_name
phone
email
address
occupation
```

Relasi:

```text
Parent
   │
   ├── Student A
   ├── Student B
   └── Student C
```

Jadi satu orang tua dapat memiliki beberapa anak.

---

# 16. DATA GURU

```text
teachers
```

Field:

```text
id
school_id
user_id
nip
nuptk
employee_number
full_name
gender
birth_date
phone
email
photo
employment_status
join_date
```

---

# 17. KELAS

```text
classes
```

Field:

```text
id
school_id
academic_year_id
name
grade_level
homeroom_teacher_id
capacity
status
```

Contoh:

```text
VII-A
VII-B
VIII-A
IX-A
```

---

# 18. ENROLLMENT

Jangan langsung menaruh class_id permanen pada student.

Gunakan:

```text
student_enrollments
```

Field:

```text
id
student_id
class_id
academic_year_id
start_date
end_date
status
```

Ini penting untuk histori.

Contoh:

```text
2025/2026 → VII-A
2026/2027 → VIII-A
```

---

# 19. MATA PELAJARAN

```text
subjects
```

Field:

```text
id
school_id
code
name
category
description
status
```

---

# 20. GURU-MATA PELAJARAN

```text
teacher_subjects
```

Field:

```text
id
teacher_id
subject_id
class_id
academic_year_id
```

---

# 21. JADWAL

```text
schedules
```

Field:

```text
id
school_id
academic_year_id
class_id
subject_id
teacher_id
day_of_week
start_time
end_time
room_id
status
```

Contoh:

```text
Senin
07:30
Matematika
VII-A
Pak Ahmad
Ruang 3
```

---

# 22. RUANG

```text
rooms
```

Field:

```text
id
school_id
name
building
floor
capacity
room_type
status
```

---

# 23. ABSENSI HARIAN

Pisahkan dari absensi pelajaran.

```text
daily_attendance
```

Field:

```text
id
school_id
student_id
academic_year_id
date
check_in
check_out
status
source
device_id
latitude
longitude
verification_method
recorded_by
created_at
updated_at
```

Status:

```text
PRESENT
LATE
SICK
PERMITTED
ABSENT
EXCUSED
```

---

# 24. ABSENSI PELAJARAN

```text
lesson_attendance
```

Field:

```text
id
school_id
schedule_id
student_id
date
status
check_in
recorded_by
source
notes
```

---

# 25. SUMBER ABSENSI

Sistem harus mendukung:

```text
MANUAL
QR_CODE
NFC
BIOMETRIC
GEOFENCE
IMPORT
API
```

Jangan langsung mengunci sistem pada satu metode.

---

# 26. ANTI-FRAUD ABSENSI

Untuk mencegah manipulasi:

```text
Attendance
     │
     ├── timestamp
     ├── device_id
     ├── IP
     ├── location
     ├── verification method
     └── audit trail
```

Jika menggunakan lokasi:

```text
School Coordinate
        ↓
Geofence Radius
        ↓
Student / Teacher Device
        ↓
Validate
```

Tetapi sistem harus menyediakan fallback jika GPS bermasalah.

---

# 27. MONITORING PELAJARAN

Guru memulai pelajaran:

```text
START LESSON
      ↓
Record timestamp
      ↓
Attendance
      ↓
Teaching activity
      ↓
END LESSON
```

Data:

```text
lesson_sessions
```

Field:

```text
id
schedule_id
teacher_id
class_id
subject_id
date
start_at
end_at
status
notes
```

---

# 28. PELANGGARAN SISWA

Master:

```text
violation_types
```

Contoh:

```text
Terlambat
Tidak menggunakan atribut
Tidak mengerjakan tugas
Tidak masuk tanpa keterangan
Pelanggaran tata tertib
```

Field:

```text
id
school_id
name
category
point
description
severity
status
```

---

# 29. TRANSAKSI PELANGGARAN

```text
student_violations
```

Field:

```text
id
school_id
student_id
violation_type_id
date
points
description
reported_by
evidence
status
```

---

# 30. SISTEM POIN

Contoh:

```text
Total Poin = SUM(pelanggaran)
```

Level:

```text
0–20     Normal
21–50    Perhatian
51–100   Pembinaan
>100     Tindakan lanjutan
```

Angka di atas hanya contoh dan harus dikonfigurasi sekolah.

---

# 31. KONSELING

Tambahkan modul:

```text
counseling_sessions
```

Field:

```text
id
student_id
counselor_id
date
category
summary
action
follow_up_date
status
confidentiality_level
```

Data konseling harus memiliki akses sangat terbatas.

---

# 32. NOTIFICATION ENGINE

Jangan membuat notifikasi secara manual di setiap modul.

Gunakan:

```text
EVENT
 ↓
NOTIFICATION SERVICE
 ↓
CHANNEL
```

Contoh event:

```text
StudentCheckedIn
StudentCheckedOut
LessonStarted
StudentLate
ViolationCreated
AnnouncementPublished
PaymentSuccess
SubscriptionExpiring
```

---

# 33. CHANNEL NOTIFIKASI

```text
Push Notification
Email
WhatsApp
SMS
In-App Notification
```

Database:

```text
notifications
notification_deliveries
notification_preferences
```

---

# 34. CONTOH NOTIFIKASI ORANG TUA

```text
┌─────────────────────────────┐
│ Kehadiran Anak              │
├─────────────────────────────┤
│ Ahmad telah tiba di sekolah │
│                             │
│ 07:12 WIB                   │
│                             │
│ SD ABC                      │
└─────────────────────────────┘
```

---

# 35. APLIKASI ORANG TUA

Dashboard:

```text
┌─────────────────────────────┐
│ Selamat pagi                │
│ Bapak Ahmad                 │
├─────────────────────────────┤
│ Anak Saya                   │
│                             │
│ 👦 Budi                     │
│ Kelas VIII-A                │
├─────────────────────────────┤
│ 🟢 Hadir                    │
│ 07:12                       │
├─────────────────────────────┤
│ Jadwal Hari Ini             │
│                             │
│ 07:30 Matematika            │
│ 08:15 Bahasa Indonesia      │
│ 09:00 IPA                   │
├─────────────────────────────┤
│ Kehadiran                   │
│ Pelanggaran                 │
│ Pengumuman                  │
└─────────────────────────────┘
```

---

# 36. APLIKASI GURU

Dashboard:

```text
┌─────────────────────────────┐
│ Selamat pagi, Pak Ahmad     │
├─────────────────────────────┤
│ Jadwal Hari Ini             │
│                             │
│ 07:30 VIII-A                │
│ Matematika                  │
│                             │
│ [Mulai Pelajaran]           │
├─────────────────────────────┤
│ Absensi                     │
│ Pelanggaran                 │
│ Kelas Saya                  │
│ Jadwal                      │
└─────────────────────────────┘
```

---

# 37. DASHBOARD KEPALA SEKOLAH

```text
┌────────────────────────────────────┐
│ DASHBOARD KEPALA SEKOLAH           │
├────────┬────────┬────────┬─────────┤
│ Siswa  │ Guru   │ Hadir  │ Alpha   │
│ 1.240  │ 85     │ 94.2%  │ 1.3%    │
├────────┴────────┴────────┴─────────┤
│ Grafik Kehadiran                   │
│                                    │
│ ████████████████████               │
│ ██████████████████                 │
│ ███████████████████                │
├────────────────────────────────────┤
│ Pelanggaran Hari Ini               │
│ 12 kasus                           │
├────────────────────────────────────┤
│ Kelas dengan Absensi Terendah      │
│ VIII-B                             │
└────────────────────────────────────┘
```

---

# 38. DASHBOARD ADMIN SEKOLAH

Menu:

```text
Dashboard
├── Sekolah
├── Tahun Ajaran
├── Guru
├── Siswa
├── Orang Tua
├── Kelas
├── Mata Pelajaran
├── Jadwal
├── Absensi
├── Pelanggaran
├── Konseling
├── Pengumuman
├── Laporan
├── User & Permission
└── Pengaturan
```

---

# 39. PENGUMUMAN SEKOLAH

Entity:

```text
announcements
```

Field:

```text
id
school_id
title
content
cover_image
published_at
author_id
audience
status
```

Audience:

```text
ALL
TEACHER
STUDENT
PARENT
CLASS
```

---

# 40. PUBLIKASI SEKOLAH

Sekolah dapat membuat:

* berita
* pengumuman
* agenda
* prestasi
* kegiatan
* informasi sekolah

Publikasi dapat memiliki:

```text
draft
review
published
archived
```

---

# 41. LAPORAN

Sistem laporan:

```text
Attendance Report
Violation Report
Teacher Report
Student Report
Class Report
Lesson Report
School Report
```

Export:

```text
PDF
Excel
CSV
Print
```

---

# 42. REPORT BUILDER

Lebih profesional jika admin dapat memilih:

```text
Tanggal
Kelas
Siswa
Status
Guru
Mata Pelajaran
```

Kemudian:

```text
[Generate Report]
```

---

# 43. DATABASE CORE

Minimal tabel:

```text
organizations
schools
school_settings

users
roles
permissions
role_permissions
user_roles

academic_years
semesters

teachers
students
parents
parent_students

classes
student_enrollments

subjects
teacher_subjects
rooms
schedules

daily_attendance
lesson_attendance
attendance_devices

lesson_sessions

violation_types
student_violations

counseling_sessions

announcements

notifications
notification_deliveries
notification_preferences

files
audit_logs

subscriptions
subscription_items
plans
payments
invoices

affiliates
affiliate_referrals
affiliate_commissions
affiliate_payouts

support_tickets
```

---

# 44. RELASI DATABASE INTI

```text
ORGANIZATION
     │
     └── SCHOOL
           │
           ├── USERS
           ├── TEACHERS
           ├── STUDENTS
           ├── CLASSES
           ├── SUBJECTS
           ├── ROOMS
           └── ACADEMIC YEARS
                    │
                    └── SEMESTER

STUDENT
   │
   ├── ENROLLMENT
   ├── DAILY ATTENDANCE
   ├── LESSON ATTENDANCE
   ├── VIOLATIONS
   └── PARENT

TEACHER
   │
   ├── SUBJECT
   ├── CLASS
   ├── SCHEDULE
   └── LESSON SESSION
```

---

# 45. API ARCHITECTURE

Gunakan REST API versioning:

```text
/api/v1/
```

Contoh:

```text
POST /api/v1/auth/login

GET /api/v1/schools
GET /api/v1/students
POST /api/v1/students
GET /api/v1/students/{id}

GET /api/v1/classes
GET /api/v1/schedules

POST /api/v1/attendance/check-in
POST /api/v1/attendance/check-out

GET /api/v1/attendance

POST /api/v1/violations

GET /api/v1/reports/attendance
```

---

# 46. API AUTHENTICATION

Gunakan:

```text
Access Token
+
Refresh Token
```

Mobile menggunakan token.

Web menggunakan secure session atau token sesuai arsitektur frontend.

---

# 47. API RESPONSE STANDARD

Semua API harus konsisten.

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {},
  "meta": {}
}
```

Error:

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "errors": {}
}
```

---

# 48. AUDIT LOG

Ini WAJIB.

Table:

```text
audit_logs
```

Mencatat:

```text
user_id
school_id
action
module
entity_type
entity_id
old_values
new_values
ip_address
user_agent
created_at
```

Contoh:

```text
Admin mengubah status absensi
08:12
IP xxx
Data sebelum: Alpha
Data sesudah: Hadir
```

---

# 49. SOFT DELETE

Data penting jangan langsung dihapus.

Gunakan:

```text
deleted_at
```

Untuk:

* siswa
* guru
* kelas
* jadwal
* user
* pelanggaran

---

# 50. DATA RETENTION

Tentukan kebijakan:

```text
Active Data
Archived Data
Deleted Data
```

Data historis sekolah tidak boleh hilang hanya karena tahun ajaran berganti.

---

# 51. SECURITY

Minimum:

```text
HTTPS
Password hashing
Rate limiting
CSRF protection
XSS protection
SQL injection protection
RBAC
Tenant isolation
Encrypted secrets
Secure cookies
2FA untuk admin
Audit logging
Backup
```

---

# 52. LOGIN SECURITY

Tambahkan:

```text
Login
↓
Password
↓
Optional OTP / 2FA
↓
Device registration
↓
Session
```

Deteksi:

* login mencurigakan
* terlalu banyak percobaan
* perangkat baru

---

# 53. FILE SECURITY

File seperti:

* foto siswa
* dokumen siswa
* bukti pelanggaran
* dokumen sekolah

Jangan disimpan sebagai public URL permanen.

Gunakan:

```text
Private Object Storage
        ↓
Signed URL
        ↓
Temporary Access
```

---

# 54. BACKUP

Minimal:

```text
Database
├── Daily backup
├── Weekly backup
└── Monthly backup
```

Backup harus disimpan di lokasi berbeda.

Jangan menyimpan backup hanya di server utama.

---

# 55. DISASTER RECOVERY

Target:

```text
RPO: ≤ 24 jam
RTO: ≤ 4 jam
```

Untuk paket enterprise bisa ditingkatkan.

---

# 56. OBSERVABILITY

Gunakan:

```text
Application Logs
Error Tracking
Database Monitoring
Server Monitoring
Queue Monitoring
Uptime Monitoring
```

Dashboard:

```text
CPU
RAM
Disk
Requests
Errors
Queue
Database
```

---

# 57. QUEUE SYSTEM

Proses berat jangan dilakukan langsung dalam request.

Contoh:

```text
Generate 10.000 laporan
        ↓
Queue
        ↓
Worker
        ↓
Generate file
        ↓
Storage
        ↓
Notification
```

Queue juga untuk:

* email
* WhatsApp
* push notification
* laporan
* import
* export

---

# 58. IMPORT DATA

Sekolah biasanya sudah mempunyai Excel.

Wajib ada:

```text
Import Students
Import Teachers
Import Classes
Import Subjects
Import Schedule
```

Workflow:

```text
Upload Excel
 ↓
Validate
 ↓
Preview
 ↓
Show errors
 ↓
Confirm
 ↓
Import
 ↓
Audit Log
```

Jangan langsung memasukkan data tanpa preview.

---

# 59. EXPORT DATA

Sekolah harus bisa mengekspor data miliknya.

```text
Excel
CSV
PDF
```

Export harus mengikuti permission.

---

# 60. BILLING

Entity:

```text
plans
subscriptions
invoices
payments
```

Status subscription:

```text
TRIAL
ACTIVE
PAST_DUE
CANCELLED
EXPIRED
SUSPENDED
```

---

# 61. PAYMENT FLOW

```text
School
 ↓
Choose Plan
 ↓
Create Invoice
 ↓
Payment Gateway
 ↓
Webhook
 ↓
Verify Payment
 ↓
Activate Subscription
 ↓
Generate Receipt
```

Jangan mengaktifkan subscription hanya berdasarkan redirect frontend.

Gunakan webhook server-to-server.

---

# 62. FEATURE LIMIT

Paket dapat menentukan:

```text
max_students
max_teachers
max_admins
storage_limit
notification_limit
feature_flags
```

Contoh:

```text
FREE
100 siswa

PRO
1.000 siswa

ENTERPRISE
Unlimited / custom
```

---

# 63. FEATURE FLAG

Gunakan:

```text
feature_flags
```

Contoh:

```text
attendance
parent_app
violation
analytics
billing
api_access
```

Ini membuat pengembangan lebih fleksibel.

---

# 64. AFFILIATE SYSTEM

Jika model affiliate digunakan:

```text
Affiliate
 ↓
Referral Link
 ↓
School Registration
 ↓
Subscription
 ↓
Commission
 ↓
Balance
 ↓
Payout
```

Entity:

```text
affiliates
affiliate_referrals
affiliate_commissions
affiliate_payouts
```

---

# 65. SUPPORT SYSTEM

Tambahkan:

```text
support_tickets
ticket_messages
ticket_attachments
```

Status:

```text
OPEN
IN_PROGRESS
WAITING_USER
RESOLVED
CLOSED
```

---

# 66. SUPER ADMIN SUPPORT

Dashboard:

```text
Tickets
├── New
├── Urgent
├── Waiting
└── Resolved
```

---

# 67. SEARCH SYSTEM

Global search:

```text
Search
 ↓
Student
Teacher
Class
Parent
Violation
Attendance
```

Search harus dibatasi berdasarkan tenant.

---

# 68. ACTIVITY CENTER

User dapat melihat:

```text
Aktivitas terbaru
```

Contoh:

```text
07:30
Guru Ahmad melakukan absensi VIII-A

07:45
Siswa Budi terlambat

08:01
Pelanggaran dibuat

08:03
Orang tua menerima notifikasi
```

---

# 69. MOBILE NAVIGATION

Untuk orang tua:

```text
Home
Kehadiran
Jadwal
Pelanggaran
Pengumuman
Profil
```

Untuk guru:

```text
Home
Jadwal
Kelas
Absensi
Pelanggaran
Profil
```

---

# 70. WEB NAVIGATION

Sidebar:

```text
Dashboard

MASTER DATA
├── Sekolah
├── Tahun Ajaran
├── Guru
├── Siswa
├── Orang Tua
├── Kelas
├── Mata Pelajaran
└── Ruang

OPERASIONAL
├── Jadwal
├── Absensi
├── Pembelajaran
├── Pelanggaran
└── Konseling

KOMUNIKASI
├── Pengumuman
└── Notifikasi

LAPORAN
├── Kehadiran
├── Siswa
├── Guru
├── Pelanggaran
└── Pembelajaran

SYSTEM
├── User
├── Role
├── Permission
├── Audit Log
└── Settings
```

---

# 71. DESIGN SYSTEM

Gunakan design system sejak awal.

## Typography

```text
Heading
H1
H2
H3

Body
Caption
Label
```

## Component

```text
Button
Input
Select
Date Picker
Modal
Drawer
Dropdown
Tabs
Table
Card
Badge
Toast
Alert
Pagination
Empty State
Skeleton
```

---

# 72. UI PRINCIPLE

Target:

```text
Simple
Clean
Fast
Professional
Mobile Friendly
Accessible
```

Jangan membuat dashboard penuh grafik tetapi sulit digunakan.

Prioritas:

> informasi penting → tindakan utama → detail.

---

# 73. DESIGN DASHBOARD

Gunakan:

```text
Topbar
Sidebar
Breadcrumb
Page Header
Filter
Content
```

Contoh:

```text
┌─────────────────────────────────────────┐
│ Logo       Search          🔔 User      │
├──────────┬──────────────────────────────┤
│ Dashboard│ Dashboard                    │
│ Siswa    │                              │
│ Guru     │ [Siswa] [Guru] [Hadir]       │
│ Kelas    │                              │
│ Jadwal   │ ┌───────────────┐            │
│ Absensi  │ │ Attendance    │            │
│ Laporan  │ │ Chart         │            │
│          │ └───────────────┘            │
└──────────┴──────────────────────────────┘
```

---

# 74. RESPONSIVE

Breakpoints:

```text
Mobile
Tablet
Desktop
Large Desktop
```

Mobile bukan sekadar mengecilkan desktop.

Navigation harus didesain ulang.

---

# 75. ACCESSIBILITY

Minimal:

```text
Keyboard navigation
ARIA label
Readable contrast
Focus state
Error message
Screen reader support
```

---

# 76. WORKFLOW REGISTRASI SEKOLAH

```text
Landing Page
 ↓
Daftar Sekolah
 ↓
NPSN
 ↓
Informasi Sekolah
 ↓
Informasi Admin
 ↓
Email/Phone Verification
 ↓
Trial
 ↓
Setup Wizard
 ↓
Dashboard
```

---

# 77. SETUP WIZARD

Setelah sekolah pertama kali masuk:

```text
STEP 1
Profil Sekolah

STEP 2
Tahun Ajaran

STEP 3
Guru

STEP 4
Siswa

STEP 5
Kelas

STEP 6
Mata Pelajaran

STEP 7
Jadwal

STEP 8
Absensi

STEP 9
Invite Orang Tua

STEP 10
Selesai
```

Progress:

```text
████████░░ 80%
```

---

# 78. WORKFLOW ABSENSI

```text
Guru login
 ↓
Pilih kelas
 ↓
Pilih jadwal
 ↓
Sistem menampilkan siswa
 ↓
Guru mulai absensi
 ↓
Status siswa
 ↓
Submit
 ↓
Validate
 ↓
Save
 ↓
Audit
 ↓
Notification
 ↓
Parent receives notification
```

---

# 79. WORKFLOW PELANGGARAN

```text
Guru
 ↓
Pilih siswa
 ↓
Pilih jenis pelanggaran
 ↓
Masukkan keterangan
 ↓
Lampirkan bukti
 ↓
Submit
 ↓
Approval jika diperlukan
 ↓
Poin ditambahkan
 ↓
Audit
 ↓
Notifikasi orang tua
```

---

# 80. WORKFLOW NAIK KELAS

```text
Academic Year
 ↓
Promotion
 ↓
Select Students
 ↓
Target Class
 ↓
Preview
 ↓
Confirm
 ↓
Create Enrollment
 ↓
Keep Historical Record
```

Jangan mengubah histori kelas lama.

---

# 81. WORKFLOW PINDAH SEKOLAH

Status:

```text
ACTIVE
TRANSFERRED
GRADUATED
DROPPED_OUT
INACTIVE
```

Data histori tetap tersimpan.

---

# 82. WORKFLOW KELULUSAN

```text
Student
 ↓
Final Academic Year
 ↓
Graduate
 ↓
Set graduation_date
 ↓
Generate record
 ↓
Archive
```

---

# 83. DATA LIFECYCLE

```text
Created
 ↓
Active
 ↓
Updated
 ↓
Archived
 ↓
Retained
```

Tidak boleh ada penghapusan data penting tanpa audit.

---

# 84. TESTING STRATEGY

## Unit Test

Test service dan business logic.

## Feature Test

Test:

```text
Login
Student CRUD
Attendance
Violation
Subscription
```

## Integration Test

Test:

```text
Payment → Webhook → Subscription
Attendance → Notification
```

## API Test

Test semua endpoint.

## Security Test

Test:

```text
Unauthorized access
Tenant isolation
Privilege escalation
Rate limiting
File access
```

---

# 85. QA CHECKLIST

Sebelum production:

```text
[ ] Login
[ ] Logout
[ ] Password reset
[ ] Role
[ ] Permission
[ ] Multi-tenant
[ ] Student
[ ] Teacher
[ ] Parent
[ ] Class
[ ] Subject
[ ] Schedule
[ ] Attendance
[ ] Violation
[ ] Notification
[ ] Report
[ ] Import
[ ] Export
[ ] Billing
[ ] Audit
[ ] Backup
[ ] Mobile
```

---

# 86. PERFORMANCE TARGET

Target awal:

```text
API response:
< 300 ms untuk operasi umum

Page load:
< 2–3 detik

Availability:
99.9%

Queue:
monitorable

Database:
indexed
```

Angka ini menjadi target engineering, bukan jaminan otomatis.

---

# 87. DATABASE INDEX

Index penting:

```text
school_id
student_id
teacher_id
class_id
academic_year_id
date
created_at
status
```

Composite index:

```text
school_id + date
school_id + student_id
school_id + academic_year_id
class_id + date
```

---

# 88. TRANSACTIONAL INTEGRITY

Contoh absensi:

```text
BEGIN TRANSACTION

Create attendance
Create audit log
Create notification job

COMMIT
```

Jika gagal:

```text
ROLLBACK
```

---

# 89. IDEMPOTENCY

Untuk transaksi penting:

```text
attendance
payment
webhook
notification
```

gunakan idempotency key agar request ganda tidak menghasilkan data ganda.

---

# 90. PAYMENT WEBHOOK SECURITY

Webhook harus:

```text
Verify Signature
 ↓
Check Event ID
 ↓
Check Existing Transaction
 ↓
Process
 ↓
Mark Processed
```

---

# 91. CRON / SCHEDULER

Scheduled jobs:

```text
Subscription expiry reminder
Daily attendance summary
Monthly reports
Backup
Cleanup temporary files
Notification retry
```

---

# 92. NOTIFICATION RETRY

Jika gagal:

```text
Attempt 1
 ↓
Attempt 2
 ↓
Attempt 3
 ↓
Failed
 ↓
Dead Letter / Error Queue
```

---

# 93. OFFLINE MOBILE

Untuk absensi lapangan, mobile sebaiknya memiliki:

```text
Local Storage
 ↓
Offline attendance
 ↓
Internet kembali
 ↓
Sync
 ↓
Server validation
```

Tetapi data offline harus diberi timestamp dan device identifier.

---

# 94. SYNC ENGINE

```text
Mobile
 ↓
Local Event
 ↓
Sync Queue
 ↓
API
 ↓
Server
 ↓
Conflict Detection
 ↓
Success
```

---

# 95. CONFLICT RESOLUTION

Contoh:

```text
Mobile:
Hadir 07:12

Server:
Alpha 07:15
```

Sistem tidak boleh diam-diam memilih salah satu.

Harus ada:

```text
Conflict
 ↓
Review
 ↓
Resolution
 ↓
Audit
```

---

# 96. ADMIN SETTINGS

```text
School Profile
Attendance Rules
Late Rules
Violation Rules
Notification Rules
Academic Rules
Working Hours
Timezone
Holiday
Branding
Integrations
```

---

# 97. ATTENDANCE RULE ENGINE

Contoh:

```text
07:00–07:30 = On Time
07:31–08:00 = Late
>08:00 = Very Late
```

Tetapi aturan harus configurable.

---

# 98. HOLIDAY CALENDAR

```text
holidays
```

Contoh:

```text
Hari Libur Nasional
Libur Sekolah
Cuti Bersama
Kegiatan Sekolah
```

Absensi otomatis tidak meminta absensi pada hari nonaktif.

---

# 99. CALENDAR ENGINE

Sistem harus mengetahui:

```text
Academic Calendar
Holiday
Exam
School Event
Class Schedule
```

---

# 100. EVENT SYSTEM

Gunakan event-driven architecture untuk modul yang membutuhkan integrasi.

Contoh:

```text
StudentCheckedIn
      ↓
AttendanceSaved
      ↓
NotificationRequested
      ↓
PushNotification
```

---

# 101. MODULAR BACKEND

Struktur kode:

```text
app/
├── Modules/
│   ├── Auth/
│   ├── Schools/
│   ├── Students/
│   ├── Teachers/
│   ├── Classes/
│   ├── Subjects/
│   ├── Schedules/
│   ├── Attendance/
│   ├── Violations/
│   ├── Notifications/
│   ├── Reports/
│   ├── Billing/
│   └── Affiliates/
```

Ini lebih mudah dirawat daripada satu folder controller besar.

---

# 102. API LAYER

```text
Controller
 ↓
Request Validation
 ↓
Service
 ↓
Domain Logic
 ↓
Repository / Model
 ↓
Database
```

Jangan memasukkan seluruh business logic ke controller.

---

# 103. FRONTEND STRUCTURE

```text
src/
├── app/
├── components/
├── features/
│   ├── attendance/
│   ├── students/
│   ├── teachers/
│   ├── schedules/
│   └── violations/
├── services/
├── hooks/
├── stores/
├── utils/
└── types/
```

---

# 104. MOBILE STRUCTURE

```text
lib/
├── core/
├── auth/
├── dashboard/
├── attendance/
├── schedule/
├── violation/
├── notification/
├── profile/
├── services/
└── storage/
```

---

# 105. DESIGN SYSTEM COMPONENT TREE

```text
App
│
├── Layout
│   ├── Sidebar
│   ├── Header
│   └── Content
│
├── Page
│   ├── Header
│   ├── Filter
│   ├── Table
│   └── Pagination
│
└── Modal
    ├── Form
    ├── Validation
    └── Actions
```

---

# 106. UX ERROR HANDLING

Jangan hanya:

> Error 500

Gunakan:

> Data belum dapat disimpan. Periksa koneksi internet dan coba kembali.

Untuk user.

Sedangkan developer mendapatkan:

```text
Error ID:
ERR-20260922-XXXXX
```

---

# 107. EMPTY STATE

Contoh:

```text
Belum ada data siswa.

[Import Siswa]
[Tambah Siswa]
```

Bukan layar kosong.

---

# 108. LOADING STATE

Gunakan:

```text
Skeleton
Spinner
Progress
```

Untuk operasi besar:

```text
Generating report...
65%
```

---

# 109. SECURITY ROLE TEST

Contoh:

```text
Teacher A
school_id = 1

Request:
GET student school_id = 2

Expected:
403 Forbidden
```

Ini harus menjadi automated test.

---

# 110. TENANT ISOLATION

Ini salah satu bagian paling kritis.

Semua query harus secara otomatis memperhatikan tenant.

Konsep:

```text
Current User
      ↓
Current Organization
      ↓
Current School
      ↓
Allowed Data
```

Jangan mempercayai:

```text
?school_id=123
```

dari frontend tanpa validasi authorization.

---

# 111. ADMIN IMPERSONATION

Super Admin boleh memiliki fitur:

> Login as / Impersonate

Tetapi:

```text
Start impersonation
 ↓
Audit log
 ↓
Admin activity
 ↓
Stop impersonation
```

Super Admin tidak boleh dapat mengubah data secara diam-diam.

---

# 112. AUDIT DASHBOARD

Super Admin dapat melihat:

```text
Who
Did What
When
Where
On Which Data
Before
After
```

---

# 113. API RATE LIMIT

Contoh:

```text
Login:
5 request/minute

General API:
60 request/minute

Public API:
30 request/minute
```

Angka disesuaikan setelah load test.

---

# 114. PRIVACY

Data siswa termasuk data yang harus diperlakukan sebagai data sensitif secara operasional.

Prinsip:

```text
Collect minimum
Store securely
Use appropriately
Limit access
Audit access
Delete/retain according to policy
```

---

# 115. CONSENT & COMMUNICATION

Untuk komunikasi orang tua:

```text
Notification Preferences
```

Contoh:

```text
☑ Kehadiran
☑ Pelanggaran
☑ Pengumuman
☐ Promosi
```

---

# 116. DOCUMENTATION

Developer wajib menghasilkan:

```text
README
API Documentation
Database ERD
Deployment Guide
Environment Variables
Architecture Document
Security Document
Backup Procedure
Disaster Recovery Procedure
User Manual
Admin Manual
```

---

# 117. API DOCUMENTATION

Gunakan OpenAPI/Swagger.

Dokumentasi:

```text
Authentication
Students
Teachers
Classes
Attendance
Violations
Reports
Notifications
Billing
```

---

# 118. DEVELOPMENT ENVIRONMENT

```text
Local
 ↓
Development
 ↓
Staging
 ↓
Production
```

Jangan coding langsung di production.

---

# 119. CI/CD

```text
Git Push
 ↓
Automated Test
 ↓
Lint
 ↓
Build
 ↓
Security Check
 ↓
Deploy Staging
 ↓
Approval
 ↓
Production
```

---

# 120. GIT STRATEGY

Contoh:

```text
main
develop
feature/*
hotfix/*
release/*
```

Commit:

```text
feat: add student attendance
fix: resolve duplicate attendance
refactor: attendance service
```

---

# 121. ENVIRONMENT

Contoh:

```text
APP_ENV
APP_KEY
APP_URL

DB_HOST
DB_DATABASE
DB_USERNAME
DB_PASSWORD

REDIS_HOST

MAIL_HOST
MAIL_USERNAME
MAIL_PASSWORD

FCM_KEY

STORAGE_KEY

PAYMENT_SECRET

WHATSAPP_API_KEY
```

Secrets tidak boleh masuk Git.

---

# 122. DEPLOYMENT

```text
Internet
   │
Cloudflare
   │
Load Balancer
   │
Application Server
   │
Queue Worker
   │
Database
   │
Redis
   │
Object Storage
```

---

# 123. SCALING

Tahap awal:

```text
1 Application
1 Worker
1 Database
1 Redis
```

Jika berkembang:

```text
Load Balancer
    │
 ┌──┴──┐
App1  App2
 │      │
 └──┬───┘
    │
PostgreSQL
```

---

# 124. DATABASE SCALING

Awal:

```text
PostgreSQL
```

Kemudian:

```text
Read Replica
```

Jika sangat besar:

```text
Partitioning
Caching
Archiving
```

Jangan melakukan microservices terlalu dini.

---

# 125. RECOMMENDED ARCHITECTURE

Untuk V1:

> **Modular Monolith**

Bukan langsung microservices.

```text
One Application
   │
   ├── Auth
   ├── School
   ├── Student
   ├── Attendance
   ├── Notification
   ├── Billing
   └── Reports
```

Ketika skala meningkat, modul tertentu dapat dipisahkan.

---

# 126. MVP

MVP harus berisi:

```text
Authentication
School
Academic Year
Teacher
Student
Parent
Class
Subject
Schedule
Daily Attendance
Lesson Attendance
Dashboard
Notification
Basic Report
```

---

# 127. V1

Tambahkan:

```text
Violation
Counseling
Announcement
Import Excel
Export
Audit Log
Mobile App
```

---

# 128. V2

Tambahkan:

```text
Billing
Subscription
Affiliate
Advanced Analytics
Payment
API
Integration
```

---

# 129. V3

Ekspansi:

```text
Grades
Examination
Report Card
PPDB
SPP
Finance
Library
Inventory
Transport
Canteen
Digital ID
```

---

# 130. ROADMAP DEVELOPMENT

## Sprint 1

Foundation:

* repository
* architecture
* database
* authentication
* authorization

## Sprint 2

School:

* school
* organization
* academic year
* semester

## Sprint 3

Master data:

* teacher
* student
* parent
* class
* subject

## Sprint 4

Schedule:

* room
* schedule
* teacher assignment

## Sprint 5

Attendance:

* daily
* lesson
* rules
* audit

## Sprint 6

Dashboard:

* admin
* teacher
* headmaster

## Sprint 7

Notification:

* event
* push
* email

## Sprint 8

Mobile:

* parent
* teacher

## Sprint 9

Violation:

* master
* points
* history

## Sprint 10

Reports:

* PDF
* Excel
* analytics

## Sprint 11

Billing:

* plans
* subscription
* payment

## Sprint 12

Production hardening:

* security
* load test
* backup
* monitoring
* deployment

---

# 131. PRIORITAS FITUR

### P0 — wajib

```text
Auth
Multi-tenant
School
Student
Teacher
Class
Academic Year
Schedule
Attendance
Permission
Audit
```

### P1 — penting

```text
Parent
Mobile
Notification
Violation
Report
Import/Export
```

### P2 — ekspansi

```text
Billing
Affiliate
Analytics
Counseling
Announcement
```

### P3 — future

```text
Finance
PPDB
Grade
Library
Inventory
```

---

# 132. KPI PRODUK

Ukur:

```text
Active Schools
Active Teachers
Active Parents
Daily Attendance Transactions
Notification Delivery Rate
Monthly Active Users
Retention
Subscription Conversion
Churn
Support Tickets
API Error Rate
System Uptime
```

---

# 133. KPI OPERASIONAL SEKOLAH

Dashboard dapat menghitung:

```text
Attendance Rate
Late Rate
Absence Rate
Violation Rate
Teacher Attendance
Lesson Completion
```

---

# 134. PRODUCT ANALYTICS

Track:

```text
school_created
teacher_invited
student_imported
attendance_created
parent_invited
notification_sent
subscription_started
subscription_renewed
```

Jangan menyimpan data analitik yang tidak diperlukan.

---

# 135. ADMIN ONBOARDING

Setelah sekolah mendaftar:

```text
0% ───────────────── 100%

School Profile       ✓
Academic Year        ✓
Teachers             ✓
Students             ✓
Classes              ✓
Subjects             ✓
Schedules             ✓
Attendance Setup     ✓
Parent Invitation    ✓
```

---

# 136. SISTEM INVITATION

Admin dapat:

```text
Invite Teacher
Invite Parent
Invite Staff
```

Melalui:

```text
Email
WhatsApp
Invitation Link
QR Code
```

---

# 137. ACCOUNT LINKING

Orang tua dapat memiliki:

```text
1 Account
   │
   ├── Child A
   ├── Child B
   └── Child C
```

Bahkan bila anak berada pada unit sekolah berbeda dalam organisasi yang sama, jika kebijakan produk mengizinkan.

---

# 138. QR CODE

QR dapat digunakan untuk:

```text
Student ID
Teacher ID
Attendance
Parent Linking
School Verification
```

QR harus memiliki expiry/signature bila digunakan untuk autentikasi.

---

# 139. DIGITAL ID

Future feature:

```text
Digital Student ID
Digital Teacher ID
```

Berisi:

```text
Name
Photo
School
Class
Student Number
QR
```

---

# 140. SEARCH & FILTER

Semua tabel utama minimal memiliki:

```text
Search
Filter
Sort
Pagination
Export
```

---

# 141. BULK ACTION

Contoh:

```text
☑ Ahmad
☑ Budi
☑ Citra

[Assign Class]
[Export]
[Deactivate]
```

Tetap membutuhkan confirmation untuk tindakan destruktif.

---

# 142. APPROVAL ENGINE

Beberapa tindakan membutuhkan approval:

```text
Change attendance
Delete violation
Change student identity
Refund payment
Change subscription
```

Workflow:

```text
Request
 ↓
Review
 ↓
Approve / Reject
 ↓
Audit
```

---

# 143. CONFIGURATION ENGINE

Hindari hard-code.

Contoh:

```text
late_threshold
attendance_cutoff
violation_point
notification_enabled
```

disimpan sebagai configuration.

---

# 144. SCHOOL BRANDING

Setiap sekolah dapat mempunyai:

```text
Logo
Primary Color
Secondary Color
School Name
Contact
```

Dashboard menyesuaikan branding.

---

# 145. WHITE LABEL

Enterprise:

```text
school.yourplatform.id
```

atau:

```text
app.sekolah.sch.id
```

Jika produk mendukung custom domain.

---

# 146. MULTI-LANGUAGE

Awal:

```text
Bahasa Indonesia
```

Architecture harus siap untuk:

```text
English
```

Gunakan translation keys, bukan hard-coded text.

---

# 147. TIMEZONE

Setiap sekolah mempunyai timezone.

Default Indonesia:

```text
Asia/Jakarta
Asia/Makassar
Asia/Jayapura
```

Khusus Sulawesi:

```text
Asia/Makassar
UTC+08:00
```

---

# 148. FINANCIAL ARCHITECTURE

Jika nanti ada SPP:

```text
Fee Type
Invoice
Payment
Refund
Ledger
```

Jangan mencampur transaksi keuangan dengan subscription platform.

---

# 149. FUTURE ACADEMIC MODULE

Nilai:

```text
subjects
assessments
scores
grading_rules
report_cards
```

Alur:

```text
Teacher
 ↓
Assessment
 ↓
Score
 ↓
Validation
 ↓
Final Grade
 ↓
Report Card
```

---

# 150. FUTURE PPDB

```text
Applicant
 ↓
Registration
 ↓
Document
 ↓
Verification
 ↓
Selection
 ↓
Announcement
 ↓
Enrollment
```

---

# 151. FINAL SYSTEM MAP

```text
                         PLATFORM
                            │
                ┌───────────┴───────────┐
                │                       │
             BILLING                 AFFILIATE
                │                       │
                └───────────┬───────────┘
                            │
                       ORGANIZATION
                            │
                    ┌───────┴───────┐
                    │               │
                 SCHOOL A        SCHOOL B
                    │               │
        ┌───────────┼───────────┐   │
        │           │           │   │
      ADMIN       TEACHER     PARENT
        │           │           │
        └───────────┼───────────┘
                    │
                 STUDENT
                    │
        ┌───────────┼────────────┐
        │           │            │
     SCHEDULE    ATTENDANCE   VIOLATION
        │           │            │
        └───────────┼────────────┘
                    │
              NOTIFICATION
                    │
        ┌───────────┼────────────┐
        │           │            │
       APP        EMAIL       WHATSAPP
```

---

# 152. DEFINITION OF DONE

Sebuah modul tidak dianggap selesai hanya karena halaman UI sudah ada.

Contoh:

### Attendance dianggap selesai jika:

```text
[✓] Database
[✓] API
[✓] Permission
[✓] UI
[✓] Mobile
[✓] Validation
[✓] Audit
[✓] Notification
[✓] Error handling
[✓] Unit test
[✓] Feature test
[✓] Security test
[✓] Documentation
```

---

# 153. URUTAN PEMBANGUNAN YANG SAYA REKOMENDASIKAN

Jangan mulai dengan semua fitur.

Urutan:

```text
PHASE 1
Architecture
        ↓
PHASE 2
Authentication + Multi Tenant
        ↓
PHASE 3
School + Academic
        ↓
PHASE 4
Student + Teacher + Parent
        ↓
PHASE 5
Class + Subject + Schedule
        ↓
PHASE 6
Attendance
        ↓
PHASE 7
Dashboard
        ↓
PHASE 8
Notification
        ↓
PHASE 9
Mobile
        ↓
PHASE 10
Violation
        ↓
PHASE 11
Report
        ↓
PHASE 12
Billing
        ↓
PHASE 13
Production Hardening
```

---

# 154. DOKUMEN YANG HARUS DIBUAT SEBELUM CODING

Jangan langsung memberikan blueprint ini kepada programmer lalu berkata "buat".

Minimal kita pecah menjadi:

```text
01 Product Requirement Document
02 Software Requirement Specification
03 System Architecture Document
04 Database Design
05 ERD
06 API Specification
07 Role & Permission Matrix
08 UI/UX Design System
09 User Flow
10 Wireframe
11 Mobile Specification
12 Security Specification
13 Notification Specification
14 Billing Specification
15 Testing Specification
16 Deployment Specification
17 Backup & Disaster Recovery
18 Admin Manual
19 User Manual
20 Product Roadmap
```

---

# 155. DOKUMEN DEVELOPMENT FINAL

Struktur repository dokumentasi:

```text
/docs

01-product/
02-requirements/
03-architecture/
04-database/
05-api/
06-security/
07-ui-ux/
08-mobile/
09-testing/
10-deployment/
11-operations/
12-user-manual/
13-roadmap/
```

---

# 156. REKOMENDASI FINAL ARSITEKTUR

Untuk produk ini saya menetapkan baseline:

```text
Frontend
Next.js / React

Backend
Laravel

Database
PostgreSQL

Cache
Redis

Queue
Redis + Worker

Mobile
Flutter

Storage
S3 Compatible

CDN / Security
Cloudflare

API
REST + OpenAPI

Architecture
Modular Monolith

Deployment
Docker + CI/CD

Monitoring
Logs + Error Tracking + Metrics

Authentication
Token/Session + RBAC

Tenant
Organization → School

Database strategy
Shared Database + school_id

Backup
Automated + Offsite

Testing
Unit + Feature + Integration + Security + Load
```

---

# 157. CATATAN ARSITEKTURAL PALING PENTING

Ada **lima keputusan** yang tidak boleh salah sejak awal:

### 1. Multi-tenant

Harus dibangun sejak hari pertama.

### 2. Permission

Jangan hanya menggunakan `role`.

### 3. Academic history

Jangan menghapus histori ketika tahun ajaran berubah.

### 4. Audit

Data absensi dan perubahan data harus dapat ditelusuri.

### 5. Modular architecture

Jangan membuat aplikasi menjadi satu controller/database logic yang sulit dikembangkan.

---

# 158. HASIL AKHIR YANG DITUJU

Jika seluruh blueprint ini diterapkan, produk akhirnya bukan sekadar:

> "Aplikasi absensi sekolah."

Melainkan:

> **School Operating System / School Management SaaS**

dengan ekosistem:

```text
                 SCHOOL OS
                     │
     ┌───────────────┼────────────────┐
     │               │                │
  ACADEMIC       OPERATIONS       COMMUNICATION
     │               │                │
  Student         Attendance       Parent
  Teacher         Schedule         Notification
  Class           Violation        Announcement
  Subject         Report           Mobile
     │               │                │
     └───────────────┼────────────────┘
                     │
                 ANALYTICS
                     │
                  BILLING
                     │
                PLATFORM SaaS
```

**Blueprint ini menjadi baseline arsitektur.** Setelah ini, tahap profesional berikutnya bukan menambah fitur secara acak, tetapi membuat **SRS + ERD visual + database migration specification + API specification + wireframe setiap halaman** secara berurutan. Dengan begitu programmer bisa mengerjakan sistem berdasarkan spesifikasi yang konsisten, bukan berdasarkan interpretasi masing-masing.

# SCHOOL MANAGEMENT SaaS

## TECHNICAL SPECIFICATION — PHASE 2

**Dokumen:** Technical Product & Software Specification
**Versi:** 1.0
**Status:** Development Baseline
**Arsitektur:** Multi-Tenant Modular Monolith
**Database:** PostgreSQL
**Backend:** Laravel
**Web:** Next.js / React
**Mobile:** Flutter
**Cache/Queue:** Redis
**Storage:** S3 Compatible Object Storage
**API:** REST + OpenAPI

---

# 1. TUJUAN DOKUMEN

Dokumen ini merupakan turunan teknis dari Product Blueprint.

Dokumen ini mendefinisikan:

* struktur database
* entity relationship
* primary key dan foreign key
* tenant isolation
* role dan permission
* API
* workflow
* UI
* mobile
* security
* notification
* billing
* testing
* deployment
* development task
* definition of done

Tujuannya adalah menghilangkan interpretasi berbeda antara anggota tim development.

---

# 2. KEPUTUSAN ARSITEKTUR

## 2.1 Architecture Style

Gunakan:

> **Modular Monolith + Multi-Tenant SaaS**

Bukan microservices pada tahap awal.

Struktur:

```text
                         INTERNET
                            │
                       CLOUDFLARE
                            │
                    ┌───────┴───────┐
                    │               │
                  WEB             MOBILE
                Next.js           Flutter
                    │               │
                    └───────┬───────┘
                            │
                         REST API
                            │
                    ┌───────┴───────┐
                    │ Laravel App    │
                    │                │
                    │ Auth           │
                    │ School         │
                    │ Student        │
                    │ Teacher        │
                    │ Academic       │
                    │ Attendance     │
                    │ Violation      │
                    │ Notification   │
                    │ Report         │
                    │ Billing        │
                    └───────┬────────┘
                            │
               ┌────────────┼────────────┐
               │            │            │
          PostgreSQL      Redis       Storage
               │            │            │
               │          Queue       Files
               │
          Audit / Data
```

---

# 3. DOMAIN MODULE

Backend dibagi menjadi domain.

```text
app/Modules/

Auth/
Organizations/
Schools/
Users/
Academic/
Students/
Teachers/
Parents/
Classes/
Subjects/
Schedules/
Attendance/
Lessons/
Violations/
Counseling/
Announcements/
Notifications/
Reports/
Files/
Subscriptions/
Payments/
Affiliates/
Support/
Audit/
Settings/
```

Setiap module mempunyai:

```text
Module/
├── Models
├── Controllers
├── Requests
├── Services
├── Policies
├── Jobs
├── Events
├── Listeners
├── Resources
└── Tests
```

---

# 4. TENANT MODEL

Struktur tenant:

```text
PLATFORM
   │
   └── ORGANIZATION
          │
          ├── SCHOOL A
          ├── SCHOOL B
          └── SCHOOL C
```

Untuk sekolah independen:

```text
ORGANIZATION
└── SCHOOL A
```

Organization dapat berupa:

* Yayasan
* Grup sekolah
* Sekolah independen

---

# 5. TENANT RULE

Semua data operasional sekolah harus dapat ditelusuri ke:

```text
organization_id
school_id
```

Contoh:

```text
students
---------
id
school_id
...
```

Untuk entity lintas organisasi:

```text
organizations
users
plans
subscriptions
```

Tidak semua tabel membutuhkan `school_id`.

---

# 6. TENANT CONTEXT

Setiap request harus memiliki context:

```text
Authenticated User
       ↓
Organization Context
       ↓
School Context
       ↓
Permission
       ↓
Data Access
```

Contoh:

```text
User = Guru A
Organization = Yayasan ABC
School = SMP ABC
Role = Teacher
```

Request:

```text
GET /students
```

backend otomatis menghasilkan scope:

```text
WHERE school_id = current_school_id
```

---

# 7. DATABASE CONVENTION

Gunakan:

* UUID untuk ID publik
* timestamp UTC pada backend
* timezone dikonversi pada presentation layer
* `created_at`
* `updated_at`
* `deleted_at` untuk soft-delete bila diperlukan

Contoh:

```text
id UUID PRIMARY KEY
```

Untuk identifier internal yang membutuhkan efisiensi tinggi, UUID tetap dapat digunakan dengan UUIDv7.

---

# 8. DATABASE CORE

## organizations

```text
id
name
slug
type
status
created_at
updated_at
deleted_at
```

`type`:

```text
FOUNDATION
SCHOOL_GROUP
INDEPENDENT
```

---

## schools

```text
id
organization_id
npsn
name
slug
education_level
school_status
province_id
regency_id
district_id
village_id
address
postal_code
phone
email
website
logo_file_id
cover_file_id
timezone
status
created_at
updated_at
deleted_at
```

Constraint:

```text
UNIQUE(organization_id, slug)
UNIQUE(npsn)
```

Jika NPSN memang wajib dan unik pada domain yang digunakan.

---

# 9. SCHOOL SETTINGS

```text
school_settings

id
school_id
attendance_enabled
lesson_attendance_enabled
violation_enabled
parent_notification_enabled
late_threshold_minutes
checkout_enabled
geofence_enabled
geofence_radius
default_language
timezone
created_at
updated_at
```

---

# 10. USERS

```text
users

id
name
email
phone
password
avatar_file_id
email_verified_at
phone_verified_at
last_login_at
status
created_at
updated_at
deleted_at
```

Jangan menaruh semua data siswa/guru/orang tua di `users`.

`users` adalah identity/account.

Profile domain disimpan terpisah.

---

# 11. USER-SCHOOL MEMBERSHIP

```text
school_memberships

id
user_id
school_id
status
joined_at
left_at
```

Ini memungkinkan satu user memiliki akses ke beberapa sekolah.

---

# 12. ROLES

```text
roles

id
organization_id nullable
school_id nullable
name
slug
description
is_system
created_at
updated_at
```

System role:

```text
SUPER_ADMIN
PLATFORM_ADMIN
ORGANIZATION_ADMIN
SCHOOL_ADMIN
HEADMASTER
TEACHER
HOMEROOM_TEACHER
COUNSELOR
STAFF
STUDENT
PARENT
```

---

# 13. PERMISSIONS

Format:

```text
module.action
```

Contoh:

```text
student.view
student.create
student.update
student.delete

attendance.view
attendance.create
attendance.update
attendance.approve

schedule.view
schedule.create
schedule.update
schedule.delete
```

---

# 14. ROLE-PERMISSION

```text
role_permissions

role_id
permission_id
```

---

# 15. USER-ROLE

```text
user_roles

id
user_id
role_id
school_id
```

Role memiliki scope sekolah.

---

# 16. STUDENTS

```text
students

id
school_id
user_id nullable
student_number
nis
nisn
nik
full_name
gender
birth_place
birth_date
religion
address
phone
email
photo_file_id
admission_date
status
created_at
updated_at
deleted_at
```

`user_id` boleh nullable karena siswa tidak wajib memiliki login.

---

# 17. PARENTS

```text
parents

id
user_id
father_name
mother_name
guardian_name
occupation
address
created_at
updated_at
```

---

# 18. PARENT-STUDENT

```text
parent_students

id
parent_id
student_id
relationship
is_primary
can_receive_notifications
```

Relationship:

```text
FATHER
MOTHER
GUARDIAN
```

---

# 19. TEACHERS

```text
teachers

id
school_id
user_id
nip
nuptk
employee_number
full_name
gender
birth_date
phone
email
photo_file_id
employment_status
join_date
status
created_at
updated_at
deleted_at
```

---

# 20. ACADEMIC YEARS

```text
academic_years

id
school_id
name
start_date
end_date
is_active
status
created_at
updated_at
```

Constraint:

```text
Satu school hanya memiliki satu academic year aktif.
```

---

# 21. SEMESTERS

```text
semesters

id
academic_year_id
name
type
start_date
end_date
is_active
```

---

# 22. CLASSES

```text
classes

id
school_id
academic_year_id
name
grade_level
homeroom_teacher_id
capacity
status
created_at
updated_at
```

---

# 23. STUDENT ENROLLMENT

Jangan menggunakan:

```text
students.class_id
```

sebagai satu-satunya histori.

Gunakan:

```text
student_enrollments

id
student_id
class_id
academic_year_id
start_date
end_date
status
```

Dengan demikian:

```text
2025/2026 → VII-A
2026/2027 → VIII-B
2027/2028 → IX-A
```

tetap tersimpan.

---

# 24. SUBJECTS

```text
subjects

id
school_id
code
name
category
description
status
```

---

# 25. TEACHER ASSIGNMENT

```text
teacher_subject_assignments

id
teacher_id
subject_id
class_id
academic_year_id
```

---

# 26. ROOMS

```text
rooms

id
school_id
name
building
floor
capacity
room_type
status
```

---

# 27. SCHEDULES

```text
schedules

id
school_id
academic_year_id
class_id
subject_id
teacher_id
room_id
day_of_week
start_time
end_time
status
```

Validation:

```text
Guru tidak boleh memiliki dua jadwal overlapping.
Kelas tidak boleh memiliki dua jadwal overlapping.
Ruangan tidak boleh memiliki dua jadwal overlapping.
```

---

# 28. DAILY ATTENDANCE

```text
daily_attendance

id
school_id
student_id
academic_year_id
date
check_in_at
check_out_at
status
source
device_id
ip_address
latitude
longitude
verification_method
recorded_by
notes
created_at
updated_at
```

Status:

```text
PRESENT
LATE
SICK
PERMITTED
ABSENT
EXCUSED
```

---

# 29. LESSON ATTENDANCE

```text
lesson_attendance

id
school_id
schedule_id
lesson_session_id
student_id
date
status
check_in_at
recorded_by
notes
created_at
updated_at
```

---

# 30. ATTENDANCE DEVICES

```text
attendance_devices

id
school_id
name
device_identifier
type
location
status
last_seen_at
created_at
updated_at
```

Type:

```text
MOBILE
TABLET
KIOSK
QR_SCANNER
NFC
BIOMETRIC
```

---

# 31. LESSON SESSION

```text
lesson_sessions

id
school_id
schedule_id
teacher_id
class_id
subject_id
date
started_at
ended_at
status
notes
```

Status:

```text
SCHEDULED
STARTED
COMPLETED
CANCELLED
```

---

# 32. VIOLATION TYPES

```text
violation_types

id
school_id
name
category
severity
default_points
description
status
```

---

# 33. STUDENT VIOLATIONS

```text
student_violations

id
school_id
student_id
violation_type_id
date
points
description
reported_by
approved_by
status
created_at
updated_at
```

---

# 34. VIOLATION EVIDENCE

```text
violation_evidence

id
student_violation_id
file_id
uploaded_by
created_at
```

---

# 35. COUNSELING

```text
counseling_sessions

id
school_id
student_id
counselor_id
date
category
summary
action
follow_up_date
status
confidentiality_level
created_at
updated_at
```

Counseling memiliki permission khusus.

---

# 36. ANNOUNCEMENTS

```text
announcements

id
school_id
title
content
cover_file_id
author_id
audience_type
published_at
expires_at
status
created_at
updated_at
```

---

# 37. ANNOUNCEMENT TARGET

Jika hanya untuk kelas tertentu:

```text
announcement_targets

id
announcement_id
class_id nullable
student_id nullable
```

---

# 38. NOTIFICATIONS

```text
notifications

id
user_id
type
title
body
data
read_at
created_at
```

---

# 39. NOTIFICATION DELIVERIES

```text
notification_deliveries

id
notification_id
channel
status
provider
provider_message_id
sent_at
failed_at
failure_reason
attempt_count
```

Channel:

```text
IN_APP
PUSH
EMAIL
WHATSAPP
SMS
```

---

# 40. NOTIFICATION PREFERENCES

```text
notification_preferences

id
user_id
event_type
channel
enabled
```

---

# 41. FILES

```text
files

id
school_id nullable
uploaded_by
disk
path
original_name
mime_type
size
visibility
checksum
created_at
```

Visibility:

```text
PRIVATE
PROTECTED
PUBLIC
```

---

# 42. AUDIT LOGS

```text
audit_logs

id
organization_id
school_id
user_id
action
module
entity_type
entity_id
old_values
new_values
ip_address
user_agent
created_at
```

---

# 43. SUBSCRIPTION

```text
plans

id
name
slug
billing_cycle
price
currency
max_students
max_teachers
storage_limit
features
status
```

---

# 44. SUBSCRIPTIONS

```text
subscriptions

id
organization_id
plan_id
status
starts_at
ends_at
trial_ends_at
cancelled_at
created_at
updated_at
```

---

# 45. INVOICES

```text
invoices

id
organization_id
subscription_id
invoice_number
subtotal
discount
tax
total
currency
status
due_at
paid_at
created_at
```

---

# 46. PAYMENTS

```text
payments

id
invoice_id
provider
provider_transaction_id
amount
currency
status
paid_at
raw_response
created_at
updated_at
```

Jangan menyimpan data kartu pembayaran secara langsung.

---

# 47. AFFILIATE

```text
affiliates

id
user_id
code
commission_rate
status
created_at
```

```text
affiliate_referrals

id
affiliate_id
organization_id
referred_at
status
```

```text
affiliate_commissions

id
affiliate_id
subscription_id
amount
status
created_at
```

```text
affiliate_payouts

id
affiliate_id
amount
status
requested_at
paid_at
```

---

# 48. SUPPORT

```text
support_tickets

id
organization_id
school_id
created_by
subject
priority
status
assigned_to
created_at
updated_at
```

```text
ticket_messages

id
ticket_id
user_id
message
created_at
```

---

# 49. ENTITY RELATIONSHIP DIAGRAM

```text
ORGANIZATION
     │
     ├───────────────┐
     │               │
     ▼               ▼
  SCHOOLS           USERS
     │               │
     │               ▼
     │          SCHOOL MEMBERSHIP
     │
     ├──── TEACHERS ───── USER
     │
     ├──── STUDENTS
     │        │
     │        ├──── ENROLLMENT ─── CLASS
     │        │
     │        ├──── ATTENDANCE
     │        │
     │        ├──── VIOLATION
     │        │
     │        └──── PARENT
     │
     ├──── SUBJECTS
     │
     ├──── ROOMS
     │
     └──── ACADEMIC YEAR
                 │
                 └── SEMESTER
```

---

# 50. CARDINALITY UTAMA

```text
Organization 1 ─── N School

School 1 ─── N Student

School 1 ─── N Teacher

School 1 ─── N Class

Class 1 ─── N Enrollment

Student 1 ─── N Enrollment

Student N ─── N Parent

Teacher N ─── N Subject

Class N ─── N Subject

Student 1 ─── N Attendance

Student 1 ─── N Violation

Teacher 1 ─── N Lesson Session
```

---

# 51. INDEX STRATEGY

Index minimum:

```text
schools.organization_id
students.school_id
students.nisn
students.student_number

teachers.school_id
classes.school_id
classes.academic_year_id

student_enrollments.student_id
student_enrollments.class_id

schedules.school_id
schedules.class_id
schedules.teacher_id

daily_attendance.school_id
daily_attendance.student_id
daily_attendance.date

lesson_attendance.student_id
lesson_attendance.date

violations.school_id
violations.student_id
violations.date
```

Composite:

```text
(school_id, date)
(school_id, student_id, date)
(school_id, academic_year_id)
```

---

# 52. DATA INTEGRITY

Contoh:

```text
student_enrollments
```

tidak boleh memiliki dua enrollment aktif pada academic year yang sama.

Contoh:

```text
Student A
2026/2027
VIII-A ACTIVE

Student A
2026/2027
VIII-B ACTIVE
```

→ ditolak.

---

# 53. RBAC MASTER

## SUPER ADMIN

Akses seluruh platform.

## PLATFORM ADMIN

Operasional platform.

## ORGANIZATION ADMIN

Semua sekolah dalam organization.

## SCHOOL ADMIN

Satu sekolah.

## HEADMASTER

Dashboard dan monitoring sekolah.

## TEACHER

Kelas/mata pelajaran yang ditugaskan.

## HOMEROOM TEACHER

Kelas yang menjadi tanggung jawabnya.

## COUNSELOR

Konseling dan data terkait.

## STAFF

Permission tertentu.

## STUDENT

Data dirinya sendiri.

## PARENT

Data anak yang terhubung.

---

# 54. PERMISSION NAMING

Format:

```text
{module}.{action}
```

Actions:

```text
view
create
update
delete
export
approve
publish
manage
```

Contoh:

```text
student.view
student.create
student.update
student.delete
student.export

attendance.view
attendance.create
attendance.update
attendance.approve
attendance.export
```

---

# 55. PERMISSION MATRIX DETAIL

| Modul        | Super | Org Admin | School Admin | Kepala |   Guru | Wali |   Parent |
| ------------ | ----: | --------: | -----------: | -----: | -----: | ---: | -------: |
| School       |  CRUD |      CRUD |           RU |      R |      - |    - |        - |
| Student      |  CRUD |      CRUD |         CRUD |      R |     R* |   R* |   R anak |
| Teacher      |  CRUD |      CRUD |         CRUD |      R | R diri |    R |        - |
| Class        |  CRUD |      CRUD |         CRUD |      R |     R* |   R* |   R anak |
| Subject      |  CRUD |      CRUD |         CRUD |      R |     R* |   R* |        R |
| Schedule     |  CRUD |      CRUD |         CRUD |      R |     R* |    R |   R anak |
| Attendance   |  CRUD |      CRUD |         CRUD |      R |   CRU* | CRU* |        R |
| Violation    |  CRUD |      CRUD |         CRUD |      R |    CR* |  CR* |   R anak |
| Counseling   |  CRUD |       C/R |          C/R |      R |      - |    - | terbatas |
| Announcement |  CRUD |      CRUD |         CRUD |    CRU |     C* |    - |        R |
| Report       |  CRUD |      CRUD |         CRUD |      R |     R* |    - |   R anak |
| Billing      |  CRUD |      CRUD |            R |      R |      - |    - |        - |

`*` = hanya scope yang diizinkan.

---

# 56. API STRUCTURE

Base:

```text
/api/v1
```

Auth:

```text
POST /auth/login
POST /auth/logout
POST /auth/refresh
POST /auth/forgot-password
POST /auth/reset-password
GET  /auth/me
```

---

# 57. SCHOOL API

```text
GET    /schools
POST   /schools
GET    /schools/{school}
PUT    /schools/{school}
DELETE /schools/{school}
GET    /schools/{school}/settings
PUT    /schools/{school}/settings
```

---

# 58. STUDENT API

```text
GET    /students
POST   /students
GET    /students/{student}
PUT    /students/{student}
DELETE /students/{student}

POST   /students/import
GET    /students/export

GET    /students/{student}/attendance
GET    /students/{student}/violations
GET    /students/{student}/parents
```

---

# 59. TEACHER API

```text
GET    /teachers
POST   /teachers
GET    /teachers/{teacher}
PUT    /teachers/{teacher}
DELETE /teachers/{teacher}

GET    /teachers/{teacher}/schedules
GET    /teachers/{teacher}/classes
```

---

# 60. CLASS API

```text
GET    /classes
POST   /classes
GET    /classes/{class}
PUT    /classes/{class}
DELETE /classes/{class}

GET    /classes/{class}/students
POST   /classes/{class}/students
DELETE /classes/{class}/students/{student}
```

---

# 61. SCHEDULE API

```text
GET  /schedules
POST /schedules
GET  /schedules/{schedule}
PUT  /schedules/{schedule}
DELETE /schedules/{schedule}
```

Filter:

```text
?class_id=
?teacher_id=
?day=
```

---

# 62. ATTENDANCE API

```text
GET  /attendance
POST /attendance/check-in
POST /attendance/check-out
POST /attendance/manual
PUT  /attendance/{attendance}
POST /attendance/{attendance}/approve
```

---

# 63. CHECK-IN REQUEST

```json
{
  "student_id": "uuid",
  "date": "2026-09-22",
  "timestamp": "2026-09-22T07:12:00+08:00",
  "source": "MOBILE",
  "verification_method": "GPS",
  "latitude": -3.9,
  "longitude": 122.5,
  "device_id": "device-uuid"
}
```

Backend tetap menentukan valid/tidak.

Client tidak boleh menjadi sumber kebenaran tunggal.

---

# 64. CHECK-IN RESPONSE

```json
{
  "success": true,
  "message": "Kehadiran berhasil dicatat",
  "data": {
    "attendance_id": "uuid",
    "student_id": "uuid",
    "status": "PRESENT",
    "checked_in_at": "2026-09-22T07:12:00+08:00"
  }
}
```

---

# 65. VIOLATION API

```text
GET  /violation-types
POST /violation-types

GET  /violations
POST /violations
GET  /violations/{violation}
PUT  /violations/{violation}
POST /violations/{violation}/approve
```

---

# 66. NOTIFICATION API

```text
GET  /notifications
POST /notifications/{notification}/read
POST /notifications/read-all
GET  /notification-preferences
PUT  /notification-preferences
```

---

# 67. REPORT API

```text
GET /reports/attendance
GET /reports/violations
GET /reports/students
GET /reports/teachers
GET /reports/classes
```

Untuk report besar:

```text
POST /reports/export
GET  /reports/export/{job}
```

---

# 68. BILLING API

```text
GET  /plans
GET  /subscription
POST /subscription
POST /subscription/cancel

GET  /invoices
GET  /invoices/{invoice}
POST /payments
POST /payments/webhook
```

Webhook tidak boleh menggunakan authentication user biasa.

---

# 69. API PAGINATION

Default:

```text
?page=1
&per_page=25
```

Maximum:

```text
per_page=100
```

Response:

```json
{
  "data": [],
  "meta": {
    "page": 1,
    "per_page": 25,
    "total": 100
  }
}
```

---

# 70. API ERROR CODE

Gunakan HTTP standard.

```text
400 Bad Request
401 Unauthorized
403 Forbidden
404 Not Found
409 Conflict
422 Validation Error
429 Rate Limited
500 Server Error
```

Business error:

```text
ATTENDANCE_ALREADY_EXISTS
STUDENT_NOT_ENROLLED
SCHEDULE_CONFLICT
SUBSCRIPTION_EXPIRED
TENANT_ACCESS_DENIED
```

---

# 71. USER FLOW — SCHOOL REGISTRATION

```text
Landing
   ↓
Register School
   ↓
NPSN
   ↓
School Information
   ↓
Admin Account
   ↓
Verify Email/Phone
   ↓
Choose Plan
   ↓
Trial/Payment
   ↓
School Setup Wizard
   ↓
Dashboard
```

---

# 72. USER FLOW — ADMIN

```text
Login
 ↓
Dashboard
 ↓
Setup School
 ↓
Academic Year
 ↓
Import Teachers
 ↓
Import Students
 ↓
Create Classes
 ↓
Create Subjects
 ↓
Create Schedule
 ↓
Activate Attendance
```

---

# 73. USER FLOW — TEACHER

```text
Login
 ↓
Dashboard
 ↓
Today's Schedule
 ↓
Select Class
 ↓
Start Lesson
 ↓
Attendance
 ↓
Save
 ↓
Teaching Activity
 ↓
Finish Lesson
```

---

# 74. USER FLOW — PARENT

```text
Register / Invitation
 ↓
Verify
 ↓
Link Child
 ↓
Dashboard
 ↓
Select Child
 ↓
Attendance
 ↓
Schedule
 ↓
Notifications
 ↓
Violation
```

---

# 75. USER FLOW — HEADMASTER

```text
Login
 ↓
Dashboard
 ↓
Attendance Overview
 ↓
Class Performance
 ↓
Teacher Activity
 ↓
Violation
 ↓
Reports
```

---

# 76. ATTENDANCE BUSINESS RULE

Pseudo-logic:

```text
IF school attendance disabled
    reject

IF student inactive
    reject

IF student not enrolled
    reject

IF attendance exists
    reject duplicate

IF current time <= threshold
    PRESENT

ELSE
    LATE

SAVE

CREATE AUDIT

DISPATCH Notification Job
```

---

# 77. ATTENDANCE CORRECTION

Guru/admin tidak boleh mengubah histori secara diam-diam.

Flow:

```text
Request Correction
       ↓
Reason Required
       ↓
Approval
       ↓
Update
       ↓
Audit Log
```

---

# 78. NOTIFICATION EVENT

Contoh:

```text
AttendanceCreated
```

Listener:

```text
SendParentPushNotification
SendParentWhatsApp
CreateInAppNotification
```

Jika WhatsApp gagal:

```text
Push tetap berjalan.
```

Channel harus independen.

---

# 79. NOTIFICATION TEMPLATE

Template:

```text
attendance.checked_in

"Halo {{parent_name}}, {{student_name}}
telah tiba di sekolah pada {{time}}."
```

Template tidak di-hard-code di controller.

---

# 80. DASHBOARD DESIGN SYSTEM

Struktur:

```text
┌──────────────────────────────────────────────┐
│ Logo     Search             🔔   User       │
├────────────┬─────────────────────────────────┤
│            │ Breadcrumb                       │
│ Dashboard  │                                 │
│ Siswa      │ Page Title                      │
│ Guru       │ Description                     │
│ Kelas      │                                 │
│ Jadwal     │ ┌────┐ ┌────┐ ┌────┐ ┌────┐   │
│ Absensi    │ │1K  │ │85  │ │94% │ │12  │   │
│ Pelanggaran│ └────┘ └────┘ └────┘ └────┘   │
│ Laporan    │                                 │
│ Pengaturan │ ┌─────────────────────────────┐ │
│            │ │ Attendance Chart             │ │
│            │ └─────────────────────────────┘ │
│            │                                 │
│            │ Recent Activity                 │
└────────────┴─────────────────────────────────┘
```

---

# 81. STUDENT LIST DESIGN

```text
┌──────────────────────────────────────────────┐
│ Siswa                         [+ Tambah]      │
├──────────────────────────────────────────────┤
│ Search siswa...    Kelas ▼ Status ▼          │
├──────────────────────────────────────────────┤
│ □ │ Nama       │ NISN │ Kelas │ Status │ ⋮ │
│ □ │ Ahmad      │ ...  │ VIII A│ Aktif  │ ⋮ │
│ □ │ Budi       │ ...  │ VIII A│ Aktif  │ ⋮ │
└──────────────────────────────────────────────┘
```

Bulk action muncul setelah checkbox dipilih.

---

# 82. STUDENT DETAIL

```text
┌──────────────────────────────────────────────┐
│ ← Siswa                                     │
│                                              │
│ [Foto] Ahmad                                  │
│ NISN: XXXXX                                  │
│ Kelas: VIII-A                                │
│ Status: Aktif                                │
├──────────────────────────────────────────────┤
│ Overview | Attendance | Schedule | Violation │
├──────────────────────────────────────────────┤
│ Informasi Siswa                              │
│ Orang Tua                                    │
│ Riwayat Kelas                                │
└──────────────────────────────────────────────┘
```

---

# 83. ATTENDANCE SCREEN

```text
┌──────────────────────────────────────────────┐
│ Absensi VIII-A                               │
│ Senin, 22 September 2026                     │
├──────────────────────────────────────────────┤
│ Search...                                    │
├──────────────────────────────────────────────┤
│ Ahmad       🟢 Hadir        07:12            │
│ Budi        🟡 Terlambat    07:42            │
│ Citra       🔵 Izin                         │
│ Dedi        🔴 Alpha                        │
├──────────────────────────────────────────────┤
│                 [Simpan Absensi]             │
└──────────────────────────────────────────────┘
```

---

# 84. MOBILE PARENT

Bottom navigation:

```text
Home | Kehadiran | Jadwal | Notifikasi | Profil
```

Home:

```text
Selamat pagi

Anak Saya
┌──────────────────────┐
│ Ahmad                │
│ VIII-A               │
│                      │
│ 🟢 Hadir             │
│ 07:12                │
└──────────────────────┘

Jadwal Hari Ini

07:30 Matematika
08:15 Bahasa Indonesia

Aktivitas terbaru
```

---

# 85. MOBILE TEACHER

```text
Home
│
├── Jadwal berikutnya
├── Kelas hari ini
├── Absensi
└── Notifikasi
```

Quick action:

```text
[Mulai Absensi]
```

harus menjadi action utama.

---

# 86. MOBILE STUDENT

Jika fitur siswa diaktifkan:

```text
Home
Attendance
Schedule
Announcements
Profile
```

---

# 87. WIREFRAME PENGATURAN ABSENSI

```text
Attendance Settings

Daily Attendance       [ON]

Lesson Attendance      [ON]

Late Threshold
[ 15 ] minutes

Check-out              [ON]

Geofence               [ON]

Radius
[ 100 ] meters

Parent Notification    [ON]

[Save Changes]
```

---

# 88. UI STATE

Setiap halaman harus memiliki:

```text
Loading
Success
Empty
Error
Permission Denied
Offline
```

Tidak boleh hanya mendesain state normal.

---

# 89. DESIGN TOKENS

Contoh:

```text
Spacing:
4
8
12
16
24
32
48

Radius:
6
8
12
16

Typography:
12
14
16
18
20
24
32
40
```

Warna ditentukan dalam Design System final, bukan tersebar di kode.

---

# 90. ACCESSIBILITY

Target minimum:

```text
WCAG 2.1 AA
```

Perhatikan:

* contrast
* keyboard navigation
* focus
* label
* error
* screen reader
* touch target

---

# 91. SECURITY THREAT MODEL

## Threat 1 — Tenant Data Leakage

Contoh:

```text
School A
 ↓
GET /students?school_id=B
```

Harus:

```text
403
```

---

# 92. THREAT — PRIVILEGE ESCALATION

Guru mencoba:

```text
PUT /schools/{id}
```

Backend:

```text
Policy
 ↓
Permission
 ↓
403
```

---

# 93. THREAT — ATTENDANCE MANIPULATION

Risiko:

* device spoofing
* timestamp manipulation
* GPS spoofing
* duplicate request
* account sharing

Mitigasi:

```text
server timestamp
device ID
audit
rate limit
idempotency
optional GPS
optional QR
```

---

# 94. THREAT — FILE ACCESS

Jangan:

```text
/files/student-photo.jpg
```

public tanpa kontrol.

Gunakan:

```text
Authorization
 ↓
Signed URL
 ↓
Temporary access
```

---

# 95. THREAT — API ABUSE

Mitigasi:

```text
Rate Limit
Authentication
Authorization
Input Validation
Pagination
Logging
```

---

# 96. THREAT — PAYMENT WEBHOOK

Wajib:

```text
Signature verification
Event ID
Idempotency
Transaction verification
Audit
```

---

# 97. THREAT — ACCOUNT TAKEOVER

Gunakan:

```text
Password hashing
2FA optional/required for privileged users
Login rate limit
Session revocation
Device monitoring
Password reset token expiry
```

---

# 98. SECURITY PRINCIPLE

Tidak ada endpoint yang boleh hanya mengandalkan:

```text
if authenticated
```

Minimal:

```text
authenticated
+
authorized
+
tenant scoped
```

---

# 99. REPORT GENERATION ARCHITECTURE

Untuk report kecil:

```text
Request
 ↓
Generate
 ↓
Response
```

Untuk report besar:

```text
Request
 ↓
Create Job
 ↓
Queue
 ↓
Worker
 ↓
Generate PDF/Excel
 ↓
Storage
 ↓
Notification
 ↓
Download
```

---

# 100. IMPORT ENGINE

Excel import:

```text
Upload
 ↓
Detect columns
 ↓
Validate rows
 ↓
Preview
 ↓
Show errors
 ↓
Confirm
 ↓
Queue import
 ↓
Process
 ↓
Summary
```

Contoh hasil:

```text
Total rows: 1,000
Success: 985
Failed: 15

Download error report
```

---

# 101. BULK IMPORT VALIDATION

Contoh:

```text
NISN kosong
NISN duplicate
Tanggal lahir invalid
Kelas tidak ditemukan
```

Tidak langsung membuat 1.000 record jika terdapat error fatal.

---

# 102. PROMOTION ENGINE

```text
Current Class
      ↓
Select Students
      ↓
Target Class
      ↓
Preview
      ↓
Confirm
      ↓
Create Enrollment
```

Data tahun sebelumnya tetap immutable secara historis.

---

# 103. ACADEMIC YEAR CLOSING

Flow:

```text
Close Academic Year
        ↓
Validation
        ↓
Outstanding Issues
        ↓
Confirmation
        ↓
Archive
        ↓
Create New Academic Year
```

System harus menampilkan checklist sebelum closing.

---

# 104. SCHOOL ONBOARDING CHECKLIST

```text
[ ] School profile
[ ] Academic year
[ ] Semester
[ ] Teachers
[ ] Students
[ ] Classes
[ ] Subjects
[ ] Schedule
[ ] Attendance rules
[ ] Notification
[ ] Parent accounts
```

---

# 105. FEATURE FLAGS

Database:

```text
feature_flags

id
key
name
description
enabled
scope
```

Contoh:

```text
parent_app
whatsapp_notification
geofence
billing
affiliate
advanced_reports
```

---

# 106. CONFIGURATION HIERARCHY

```text
Platform Default
      ↓
Organization Setting
      ↓
School Setting
      ↓
Feature Specific Setting
```

Nilai paling spesifik mengalahkan default.

---

# 107. OBSERVABILITY

Minimum:

```text
Application logs
Access logs
Queue logs
Database metrics
Error tracking
Uptime monitoring
```

Metrics:

```text
requests/min
response time
5xx rate
queue size
failed jobs
database connections
storage
```

---

# 108. HEALTH CHECK

Endpoint:

```text
GET /health
```

Check:

```text
Application
Database
Redis
Storage
Queue
```

Response:

```json
{
  "status": "ok",
  "database": "ok",
  "redis": "ok",
  "storage": "ok"
}
```

Endpoint internal detail harus dibatasi agar tidak membocorkan informasi sensitif.

---

# 109. BACKUP POLICY

Database:

```text
Daily
Weekly
Monthly
```

Retensi contoh:

```text
7 daily
4 weekly
6 monthly
```

Backup terenkripsi dan disimpan di lokasi berbeda.

---

# 110. RESTORE TEST

Backup tanpa restore test bukan backup strategy yang lengkap.

Minimal:

```text
Quarterly restore drill
```

Test:

```text
Backup
 ↓
Restore staging
 ↓
Verify data
 ↓
Record result
```

---

# 111. CI/CD

```text
Developer
 ↓
Git Push
 ↓
Lint
 ↓
Unit Test
 ↓
Feature Test
 ↓
Security Scan
 ↓
Build
 ↓
Deploy Staging
 ↓
QA
 ↓
Approval
 ↓
Production
```

---

# 112. ENVIRONMENT

```text
LOCAL
DEVELOPMENT
STAGING
PRODUCTION
```

Database masing-masing terpisah.

---

# 113. DEVELOPMENT BRANCH

```text
main
develop
feature/*
bugfix/*
hotfix/*
release/*
```

Production hanya dari branch release/main melalui pipeline.

---

# 114. TESTING PYRAMID

```text
              E2E
             /  \
        Integration
          /      \
       Feature  API
          \      /
           Unit
```

Unit test paling banyak.

---

# 115. TEST SCENARIO — TENANT

```text
Given:
User A belongs to School A

When:
User A requests Student from School B

Then:
403 Forbidden
```

---

# 116. TEST SCENARIO — ATTENDANCE

```text
Given:
Student active
Student enrolled
No attendance today

When:
Check-in

Then:
Attendance created
Status calculated
Audit created
Notification queued
```

---

# 117. TEST SCENARIO — DUPLICATE

```text
Given:
Attendance already exists

When:
Same request sent twice

Then:
No duplicate record
```

---

# 118. TEST SCENARIO — PAYMENT

```text
Webhook received
 ↓
Verify signature
 ↓
Check event ID
 ↓
Process payment
 ↓
Update invoice
 ↓
Activate subscription
```

Repeated webhook:

```text
No duplicate activation.
```

---

# 119. DEVELOPMENT TASK BREAKDOWN

## EPIC 01 — FOUNDATION

```text
TASK-001 Repository
TASK-002 Docker
TASK-003 Laravel
TASK-004 PostgreSQL
TASK-005 Redis
TASK-006 CI/CD
TASK-007 Environment
```

---

# 120. EPIC 02 — AUTH

```text
TASK-010 Login
TASK-011 Logout
TASK-012 Password Reset
TASK-013 Email Verification
TASK-014 Session
TASK-015 Role
TASK-016 Permission
TASK-017 Policy
```

---

# 121. EPIC 03 — MULTI TENANT

```text
TASK-020 Organization
TASK-021 School
TASK-022 Membership
TASK-023 Tenant Context
TASK-024 Tenant Scope
TASK-025 Tenant Security Tests
```

---

# 122. EPIC 04 — ACADEMIC

```text
TASK-030 Academic Year
TASK-031 Semester
TASK-032 Class
TASK-033 Subject
TASK-034 Room
TASK-035 Teacher Assignment
TASK-036 Student Enrollment
```

---

# 123. EPIC 05 — PEOPLE

```text
TASK-040 Student
TASK-041 Teacher
TASK-042 Parent
TASK-043 Parent-Student
TASK-044 Invitation
```

---

# 124. EPIC 06 — SCHEDULE

```text
TASK-050 Schedule CRUD
TASK-051 Conflict Detection
TASK-052 Calendar
TASK-053 Teacher Schedule
TASK-054 Class Schedule
```

---

# 125. EPIC 07 — ATTENDANCE

```text
TASK-060 Daily Attendance
TASK-061 Lesson Attendance
TASK-062 Check-in
TASK-063 Check-out
TASK-064 Manual Correction
TASK-065 Approval
TASK-066 Attendance Rule Engine
TASK-067 Audit
```

---

# 126. EPIC 08 — NOTIFICATION

```text
TASK-070 Event System
TASK-071 In-App
TASK-072 Push
TASK-073 Email
TASK-074 WhatsApp integration
TASK-075 Retry
TASK-076 Preferences
```

---

# 127. EPIC 09 — VIOLATION

```text
TASK-080 Violation Type
TASK-081 Violation
TASK-082 Point System
TASK-083 Evidence
TASK-084 Approval
TASK-085 Parent Notification
```

---

# 128. EPIC 10 — REPORT

```text
TASK-090 Attendance Report
TASK-091 Student Report
TASK-092 Violation Report
TASK-093 PDF
TASK-094 Excel
TASK-095 Async Export
```

---

# 129. EPIC 11 — MOBILE

```text
TASK-100 Flutter Foundation
TASK-101 Parent Login
TASK-102 Parent Dashboard
TASK-103 Child Attendance
TASK-104 Schedule
TASK-105 Notification
TASK-106 Teacher Login
TASK-107 Teacher Attendance
```

---

# 130. EPIC 12 — BILLING

```text
TASK-110 Plans
TASK-111 Subscription
TASK-112 Invoice
TASK-113 Payment
TASK-114 Webhook
TASK-115 Feature Limit
```

---

# 131. EPIC 13 — AFFILIATE

```text
TASK-120 Affiliate
TASK-121 Referral
TASK-122 Commission
TASK-123 Balance
TASK-124 Payout
```

---

# 132. EPIC 14 — SECURITY

```text
TASK-130 Rate Limit
TASK-131 Audit
TASK-132 Security Headers
TASK-133 File Security
TASK-134 Tenant Isolation
TASK-135 2FA
TASK-136 Security Testing
```

---

# 133. EPIC 15 — PRODUCTION

```text
TASK-140 Monitoring
TASK-141 Backup
TASK-142 Restore
TASK-143 Logging
TASK-144 Error Tracking
TASK-145 Load Test
TASK-146 Production Deployment
```

---

# 134. DEFINITION OF READY

Task boleh dikerjakan jika:

```text
[✓] Requirement jelas
[✓] Acceptance criteria tersedia
[✓] UI tersedia bila diperlukan
[✓] API contract tersedia
[✓] Database relation jelas
[✓] Permission jelas
```

---

# 135. DEFINITION OF DONE

Task selesai jika:

```text
[ ] Code complete
[ ] Review
[ ] Unit test
[ ] Integration test jika perlu
[ ] Security check
[ ] UI responsive
[ ] Error state
[ ] Loading state
[ ] Permission tested
[ ] Tenant tested
[ ] Documentation updated
[ ] QA passed
```

---

# 136. ACCEPTANCE CRITERIA — STUDENT

```text
Given:
Admin berada di School A

When:
Admin membuat siswa

Then:
Siswa dibuat pada School A

And:
User dari School B tidak dapat melihat siswa tersebut.

And:
Audit log dibuat.
```

---

# 137. ACCEPTANCE CRITERIA — ATTENDANCE

```text
Given:
Siswa terdaftar pada kelas aktif

When:
Guru melakukan absensi

Then:
Attendance tersimpan

And:
Status dihitung berdasarkan aturan sekolah

And:
Audit tercatat

And:
Parent notification dibuat jika aktif.
```

---

# 138. ACCEPTANCE CRITERIA — PARENT

```text
Given:
Parent terhubung dengan Student A

When:
Parent login

Then:
Parent hanya dapat melihat Student A

And:
Tidak dapat mengakses Student B
```

---

# 139. ACCEPTANCE CRITERIA — SUPER ADMIN

Super Admin dapat:

```text
manage organization
manage school
manage subscription
view audit
manage platform
```

Tetapi setiap aktivitas administratif tetap dicatat.

---

# 140. ROADMAP RELEASE

## RELEASE 0.1

Foundation:

```text
Auth
Multi-tenant
School
User
RBAC
```

## RELEASE 0.2

Academic:

```text
Teacher
Student
Parent
Class
Subject
Academic Year
```

## RELEASE 0.3

Operations:

```text
Schedule
Attendance
Dashboard
```

## RELEASE 0.4

Communication:

```text
Notification
Announcement
Parent App
```

## RELEASE 0.5

Discipline:

```text
Violation
Counseling
```

## RELEASE 0.6

Reporting:

```text
Reports
PDF
Excel
Analytics
```

## RELEASE 1.0

Commercial:

```text
Subscription
Payment
Affiliate
Support
Monitoring
Security Hardening
```

---

# 141. PRIORITAS PENGEMBANGAN

## P0

```text
Auth
Tenant
School
User
RBAC
Student
Teacher
Class
Academic
Schedule
Attendance
Audit
```

## P1

```text
Parent
Notification
Mobile
Violation
Report
Import
Export
```

## P2

```text
Billing
Affiliate
Advanced Analytics
Counseling
```

## P3

```text
Finance
PPDB
Grades
Library
Inventory
Transport
Canteen
```

---

# 142. PRODUCT ANALYTICS

Event:

```text
school_registered
school_setup_completed
teacher_created
student_imported
class_created
schedule_created
attendance_created
parent_invited
parent_activated
notification_sent
violation_created
report_generated
subscription_started
subscription_renewed
```

Analytics harus tidak mengganggu transactional database.

---

# 143. KPI TEKNIS

Target:

```text
Uptime ≥ 99.9%
API p95 < 500ms untuk endpoint umum
5xx rate sangat rendah
Queue failure terpantau
Backup success 100%
Critical security issue = 0 sebelum production
```

Target perlu divalidasi melalui load testing.

---

# 144. KPI PRODUK

```text
Active Schools
Monthly Active Teachers
Monthly Active Parents
Daily Attendance Transactions
Parent Activation Rate
Notification Delivery Rate
Subscription Conversion
Renewal Rate
Churn
Support Response Time
```

---

# 145. ADMIN SUPER DASHBOARD

```text
┌────────────────────────────────────────────┐
│ PLATFORM OVERVIEW                          │
├─────────┬─────────┬─────────┬─────────────┤
│ Schools │ Users   │ Students│ Revenue     │
│ 1,245   │ 34,200  │ 480K    │ Rp XXX      │
├─────────┴─────────┴─────────┴─────────────┤
│ Active Subscriptions                       │
│ ███████████████████                       │
├────────────────────────────────────────────┤
│ System Health                              │
│ API       ✓                               │
│ Database  ✓                               │
│ Queue     ✓                               │
│ Storage   ✓                               │
└────────────────────────────────────────────┘
```

---

# 146. SCHOOL ADMIN DASHBOARD

```text
┌────────────────────────────────────────────┐
│ Selamat pagi, Admin                       │
├────────┬────────┬────────┬────────┬───────┤
│ Siswa  │ Guru   │ Hadir  │ Izin   │ Alpha │
│ 1,240  │ 85     │ 94.3%  │ 2.1%   │ 1.2%  │
├────────┴────────┴────────┴────────┴───────┤
│ Kehadiran 7 Hari                          │
│                                            │
│      ╭──────╮                              │
│  ────╯      ╰──────                        │
├────────────────────────────────────────────┤
│ Aktivitas Terbaru                         │
└────────────────────────────────────────────┘
```

---

# 147. HEADMASTER DASHBOARD

Fokus bukan CRUD.

Fokus:

```text
Kehadiran
Kedisiplinan
Guru
Kelas
Trend
```

Contoh:

```text
Attendance Today
94.3%

Compared to yesterday
+1.2%

Late students
37

Violations
12

Classes requiring attention
3
```

"Requiring attention" adalah indikator sistem berdasarkan threshold yang dikonfigurasi, bukan penilaian subjektif.

---

# 148. TEACHER DASHBOARD

Fokus:

```text
Next Class
Today's Classes
Attendance
Students
Notifications
```

Quick action:

```text
START LESSON
```

---

# 149. PARENT DASHBOARD

Fokus:

```text
Child status
Attendance
Schedule
Notifications
```

Jangan menampilkan data sekolah yang tidak relevan.

---

# 150. SYSTEM NAVIGATION FINAL

```text
PLATFORM ADMIN

Dashboard
Organizations
Schools
Users
Subscriptions
Payments
Affiliates
Support
Audit
System Settings


SCHOOL ADMIN

Dashboard
School
Academic
Teachers
Students
Parents
Classes
Subjects
Rooms
Schedules
Attendance
Lessons
Violations
Counseling
Announcements
Reports
Users
Settings


TEACHER

Dashboard
Schedule
Classes
Attendance
Lessons
Violations
Notifications
Profile


PARENT

Dashboard
Children
Attendance
Schedule
Violations
Announcements
Notifications
Profile
```

---

# 151. DATA PRIVACY BOUNDARY

```text
SUPER ADMIN
      │
      ▼
Platform Data

SCHOOL ADMIN
      │
      ▼
School Data

TEACHER
      │
      ▼
Assigned Data

PARENT
      │
      ▼
Own Children

STUDENT
      │
      ▼
Own Data
```

Ini harus diterapkan di backend, bukan hanya menu frontend.

---

# 152. FINAL ARCHITECTURE

```text
                           USERS
                             │
              ┌──────────────┼──────────────┐
              │              │              │
             WEB           MOBILE          API
              │              │              │
              └──────────────┼──────────────┘
                             │
                        API GATEWAY
                             │
                       AUTH / RBAC
                             │
                    TENANT CONTEXT
                             │
                     LARAVEL APP
                             │
 ┌──────────┬──────────┬─────┼─────┬──────────┬──────────┐
 │          │          │     │     │          │          │
School   Academic   People Attendance Notification Report Billing
 │          │          │     │     │          │          │
 └──────────┴──────────┴─────┼─────┴──────────┴──────────┘
                             │
                  ┌──────────┼──────────┐
                  │          │          │
              PostgreSQL   Redis     Storage
                  │          │          │
                  │        Queue        │
                  │          │          │
                  └──────────┼──────────┘
                             │
              ┌──────────────┼──────────────┐
              │              │              │
             FCM          WhatsApp         Email
```

---

# 153. IMPLEMENTATION ORDER

Jangan mengerjakan berdasarkan "halaman mana yang terlihat".

Urutan dependency:

```text
DATABASE
   ↓
AUTH
   ↓
TENANT
   ↓
RBAC
   ↓
MASTER DATA
   ↓
ACADEMIC
   ↓
SCHEDULE
   ↓
ATTENDANCE
   ↓
NOTIFICATION
   ↓
REPORT
   ↓
MOBILE
   ↓
BILLING
```

---

# 154. HAL YANG TIDAK BOLEH DILAKUKAN DEVELOPER

1. Menaruh business logic utama di frontend.
2. Mengandalkan `school_id` dari client tanpa authorization.
3. Menghapus histori akademik.
4. Mengubah attendance tanpa audit.
5. Mengaktifkan subscription hanya berdasarkan redirect pembayaran.
6. Menyimpan secret API di frontend.
7. Membuat laporan besar secara synchronous.
8. Membuat microservices sebelum ada kebutuhan nyata.
9. Menggabungkan data semua tenant tanpa scope.
10. Membuat role tanpa permission granular.
11. Menyimpan file privat sebagai URL publik permanen.
12. Menganggap backup berhasil tanpa pernah melakukan restore test.

---

# 155. HASIL YANG HARUS DIBERIKAN TIM DEVELOPMENT

Pada akhir implementation baseline, repository harus memiliki:

```text
/backend
/frontend
/mobile
/docs
/infrastructure
/tests
```

Dokumentasi:

```text
/docs
├── PRD.md
├── SRS.md
├── ARCHITECTURE.md
├── DATABASE.md
├── API.md
├── RBAC.md
├── SECURITY.md
├── NOTIFICATION.md
├── BILLING.md
├── DEPLOYMENT.md
├── BACKUP.md
├── TESTING.md
└── USER-MANUAL.md
```

---

# 156. FINAL CHECKLIST PHASE 2

## Architecture

```text
[✓] Modular monolith
[✓] Multi tenant
[✓] REST API
[✓] Queue
[✓] Cache
[✓] Object storage
```

## Database

```text
[✓] Organization
[✓] School
[✓] User
[✓] Student
[✓] Teacher
[✓] Parent
[✓] Academic
[✓] Class
[✓] Subject
[✓] Schedule
[✓] Attendance
[✓] Violation
[✓] Notification
[✓] Audit
[✓] Billing
[✓] Affiliate
[✓] Support
```

## Security

```text
[✓] RBAC
[✓] Tenant isolation
[✓] Audit
[✓] Rate limit
[✓] File security
[✓] Payment webhook security
[✓] Backup
```

## Product

```text
[✓] School Admin
[✓] Headmaster
[✓] Teacher
[✓] Parent
[✓] Student
[✓] Platform Admin
```

## Development

```text
[✓] API structure
[✓] User flow
[✓] Wireframe baseline
[✓] Task breakdown
[✓] Testing
[✓] CI/CD
```

---

# 157. STATUS DOKUMEN

Tahap 2 sekarang mendefinisikan:

**Apa yang dibangun → bagaimana datanya → siapa yang dapat mengakses → bagaimana API bekerja → bagaimana pengguna berinteraksi → bagaimana sistem diamankan → bagaimana dikembangkan → bagaimana diuji → bagaimana diproduksi.**

Tahap berikutnya yang paling tepat adalah **Phase 3 — Database & UI/UX Engineering**, bukan langsung coding.

Phase 3 akan menghasilkan dua artefak yang jauh lebih konkret:

### A. Database Engineering

```text
ERD visual lengkap
       ↓
PostgreSQL schema
       ↓
Migration
       ↓
Foreign key
       ↓
Constraint
       ↓
Index
       ↓
Seed data
       ↓
RBAC seed
```

### B. UI/UX Engineering

Setiap layar akan dibuat satu per satu:

```text
Landing Page
Login
Register
School Setup
Dashboard
Students
Student Detail
Teachers
Teacher Detail
Parents
Classes
Subjects
Rooms
Schedule
Attendance
Lesson
Violation
Counseling
Announcements
Reports
Notifications
Settings
Billing
Super Admin
Mobile Parent
Mobile Teacher
```

Dengan demikian **Phase 3 sudah bisa menjadi acuan langsung untuk mulai membuat database dan desain Figma**, sedangkan Phase 4 nantinya dapat masuk ke **implementasi coding dan API secara bertahap**.

# PHASE 3

## DATABASE & UI/UX ENGINEERING

### Sistem Manajemen Sekolah Multi-Tenant SaaS

**Baseline:** Laravel + PostgreSQL + Next.js/React + Flutter + Redis + Object Storage
**Arsitektur:** Modular Monolith + Multi-Tenant
**API:** REST API `/api/v1`

---

# 1. PRINSIP DATABASE

Database harus dirancang untuk:

* Multi-school
* Multi-tenant
* Historical data
* Auditability
* High concurrency
* Reporting
* Import/export
* Mobile application
* Future integration

### Prinsip utama

```text
Organization
    ↓
School
    ↓
Academic Year
    ↓
Semester
    ↓
Class
    ↓
Student Enrollment
    ↓
Student
```

Jangan menyimpan `class_id` permanen langsung pada tabel `students`.

Karena siswa dapat:

```text
2025/2026 → VII-A
2026/2027 → VIII-B
2027/2028 → IX-C
```

Sehingga histori akademik tetap utuh.

---

# 2. DATABASE CORE

## 2.1 organizations

```sql
organizations
-------------
id UUID PK
name VARCHAR(150)
slug VARCHAR(100) UNIQUE
legal_name VARCHAR(200)
email VARCHAR(150)
phone VARCHAR(50)
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
deleted_at TIMESTAMP NULL
```

Status:

```text
ACTIVE
SUSPENDED
INACTIVE
```

---

# 3. SCHOOLS

```sql
schools
-------
id UUID PK
organization_id UUID FK
name VARCHAR(200)
npsn VARCHAR(50) NULL
school_code VARCHAR(50)
level VARCHAR(30)
status VARCHAR(30)
address TEXT
village VARCHAR(100)
district VARCHAR(100)
regency VARCHAR(100)
province VARCHAR(100)
postal_code VARCHAR(20)
latitude DECIMAL(10,7) NULL
longitude DECIMAL(10,7) NULL
logo_file_id UUID NULL
created_at TIMESTAMP
updated_at TIMESTAMP
deleted_at TIMESTAMP NULL
```

Level:

```text
TK
SD
SMP
SMA
SMK
MI
MTs
MA
OTHER
```

Index:

```text
organization_id
school_code
npsn
status
```

Unique:

```text
organization_id + school_code
```

---

# 4. SCHOOL SETTINGS

```sql
school_settings
---------------
id UUID PK
school_id UUID UNIQUE FK
timezone VARCHAR(50)
currency VARCHAR(10)
academic_start_month SMALLINT
attendance_late_threshold INTEGER
attendance_auto_close BOOLEAN
parent_notification_enabled BOOLEAN
whatsapp_enabled BOOLEAN
email_enabled BOOLEAN
push_enabled BOOLEAN
created_at TIMESTAMP
updated_at TIMESTAMP
```

Default timezone untuk sekolah Indonesia dapat menggunakan:

```text
Asia/Makassar
```

tetapi timezone tetap disimpan per sekolah.

---

# 5. USERS

```sql
users
-----
id UUID PK
name VARCHAR(150)
email VARCHAR(150) UNIQUE NULL
phone VARCHAR(30) UNIQUE NULL
password_hash TEXT
avatar_file_id UUID NULL
status VARCHAR(30)
email_verified_at TIMESTAMP NULL
phone_verified_at TIMESTAMP NULL
last_login_at TIMESTAMP NULL
created_at TIMESTAMP
updated_at TIMESTAMP
deleted_at TIMESTAMP NULL
```

Jangan menyimpan role utama di tabel `users`.

Karena satu pengguna dapat memiliki beberapa role.

---

# 6. SCHOOL MEMBERSHIP

```sql
school_memberships
------------------
id UUID PK
school_id UUID FK
user_id UUID FK
employee_number VARCHAR(100) NULL
status VARCHAR(30)
joined_at DATE NULL
left_at DATE NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

Unique:

```text
school_id + user_id
```

---

# 7. ROLE SYSTEM

## roles

```sql
roles
-----
id UUID PK
name VARCHAR(100)
code VARCHAR(50)
scope VARCHAR(30)
description TEXT
created_at TIMESTAMP
```

Contoh:

```text
SUPER_ADMIN
PLATFORM_ADMIN
ORGANIZATION_ADMIN
SCHOOL_ADMIN
HEADMASTER
TEACHER
HOMEROOM_TEACHER
COUNSELOR
STAFF
STUDENT
PARENT
```

---

# 8. PERMISSIONS

```sql
permissions
-----------
id UUID PK
name VARCHAR(150)
code VARCHAR(150) UNIQUE
module VARCHAR(50)
action VARCHAR(50)
created_at TIMESTAMP
```

Format:

```text
student.view
student.create
student.update
student.delete
student.export
```

Contoh:

```text
attendance.view
attendance.create
attendance.update
attendance.approve
attendance.export

teacher.view
teacher.create
teacher.update

schedule.view
schedule.create
schedule.update
schedule.delete

report.view
report.export
```

---

# 9. ROLE PERMISSION

```sql
role_permissions
----------------
role_id UUID FK
permission_id UUID FK
PRIMARY KEY(role_id, permission_id)
```

---

# 10. USER ROLE

```sql
user_roles
----------
user_id UUID FK
role_id UUID FK
school_id UUID NULL FK
PRIMARY KEY(user_id, role_id, school_id)
```

Ini memungkinkan:

```text
User A
 ├── School A → SCHOOL_ADMIN
 └── School B → TEACHER
```

---

# 11. STUDENTS

```sql
students
--------
id UUID PK
school_id UUID FK
student_number VARCHAR(100)
nis VARCHAR(100) NULL
nisn VARCHAR(100) NULL
full_name VARCHAR(200)
gender VARCHAR(20)
birth_place VARCHAR(100) NULL
birth_date DATE NULL
religion VARCHAR(50) NULL
nik VARCHAR(50) NULL
address TEXT NULL
phone VARCHAR(30) NULL
email VARCHAR(150) NULL
status VARCHAR(30)
photo_file_id UUID NULL
created_at TIMESTAMP
updated_at TIMESTAMP
deleted_at TIMESTAMP NULL
```

Status:

```text
ACTIVE
INACTIVE
GRADUATED
TRANSFERRED
DROPPED_OUT
```

Sensitive fields seperti NIK harus mendapatkan perlindungan tambahan.

---

# 12. PARENTS

```sql
parents
-------
id UUID PK
user_id UUID FK NULL
full_name VARCHAR(200)
relationship VARCHAR(30)
nik VARCHAR(50) NULL
phone VARCHAR(30)
email VARCHAR(150) NULL
address TEXT NULL
occupation VARCHAR(100) NULL
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Relasi:

```sql
parent_students
---------------
parent_id UUID FK
student_id UUID FK
relationship VARCHAR(30)
is_primary BOOLEAN
PRIMARY KEY(parent_id, student_id)
```

Dengan demikian:

```text
1 anak → ayah + ibu + wali
1 orang tua → beberapa anak
```

---

# 13. TEACHERS

```sql
teachers
--------
id UUID PK
school_id UUID FK
user_id UUID FK NULL
employee_number VARCHAR(100)
nip VARCHAR(100) NULL
full_name VARCHAR(200)
gender VARCHAR(20)
birth_date DATE NULL
phone VARCHAR(30)
email VARCHAR(150) NULL
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
deleted_at TIMESTAMP NULL
```

---

# 14. ACADEMIC YEAR

```sql
academic_years
-------------
id UUID PK
school_id UUID FK
name VARCHAR(30)
start_date DATE
end_date DATE
is_active BOOLEAN
created_at TIMESTAMP
updated_at TIMESTAMP
```

Contoh:

```text
2026/2027
```

Constraint:

```text
Satu sekolah hanya memiliki satu academic year aktif.
```

---

# 15. SEMESTER

```sql
semesters
---------
id UUID PK
academic_year_id UUID FK
name VARCHAR(30)
type VARCHAR(20)
start_date DATE
end_date DATE
is_active BOOLEAN
```

Type:

```text
ODD
EVEN
```

---

# 16. CLASSES

```sql
classes
-------
id UUID PK
school_id UUID FK
academic_year_id UUID FK
name VARCHAR(100)
grade VARCHAR(30)
homeroom_teacher_id UUID NULL
room_id UUID NULL
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Contoh:

```text
VII-A
VII-B
VIII-A
IX-B
```

---

# 17. STUDENT ENROLLMENT

```sql
student_enrollments
-------------------
id UUID PK
student_id UUID FK
class_id UUID FK
academic_year_id UUID FK
start_date DATE
end_date DATE NULL
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Ini menjadi sumber histori kelas siswa.

---

# 18. SUBJECTS

```sql
subjects
--------
id UUID PK
school_id UUID FK
code VARCHAR(50)
name VARCHAR(150)
short_name VARCHAR(50)
category VARCHAR(50)
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

---

# 19. TEACHER SUBJECT ASSIGNMENT

```sql
teacher_subject_assignments
---------------------------
id UUID PK
school_id UUID FK
teacher_id UUID FK
subject_id UUID FK
class_id UUID FK
academic_year_id UUID FK
created_at TIMESTAMP
updated_at TIMESTAMP
```

---

# 20. ROOMS

```sql
rooms
-----
id UUID PK
school_id UUID FK
name VARCHAR(100)
building VARCHAR(100) NULL
floor VARCHAR(30) NULL
capacity INTEGER NULL
room_type VARCHAR(50)
status VARCHAR(30)
```

---

# 21. SCHEDULES

```sql
schedules
---------
id UUID PK
school_id UUID FK
class_id UUID FK
subject_id UUID FK
teacher_id UUID FK
room_id UUID NULL
day_of_week SMALLINT
start_time TIME
end_time TIME
academic_year_id UUID FK
semester_id UUID FK
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Validasi:

```text
start_time < end_time
```

Sistem juga harus mendeteksi:

```text
Guru bentrok
Kelas bentrok
Ruangan bentrok
```

---

# 22. ATTENDANCE

## Daily Attendance

```sql
daily_attendance
----------------
id UUID PK
school_id UUID FK
student_id UUID FK
class_id UUID FK
date DATE
status VARCHAR(20)
check_in_at TIMESTAMP NULL
check_out_at TIMESTAMP NULL
source VARCHAR(30)
device_id UUID NULL
notes TEXT NULL
created_by UUID FK
updated_by UUID NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

Status:

```text
PRESENT
LATE
ABSENT
SICK
PERMITTED
LEAVE
```

Unique:

```text
school_id + student_id + date
```

---

# 23. LESSON SESSION

```sql
lesson_sessions
---------------
id UUID PK
school_id UUID FK
schedule_id UUID FK
teacher_id UUID FK
class_id UUID FK
subject_id UUID FK
date DATE
started_at TIMESTAMP NULL
ended_at TIMESTAMP NULL
status VARCHAR(30)
notes TEXT NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

---

# 24. LESSON ATTENDANCE

```sql
lesson_attendance
-----------------
id UUID PK
lesson_session_id UUID FK
student_id UUID FK
status VARCHAR(20)
notes TEXT NULL
created_at TIMESTAMP
updated_at TIMESTAMP
```

Unique:

```text
lesson_session_id + student_id
```

---

# 25. VIOLATIONS

```sql
violation_types
---------------
id UUID PK
school_id UUID FK
name VARCHAR(150)
category VARCHAR(100)
severity VARCHAR(30)
points INTEGER
description TEXT NULL
status VARCHAR(30)
```

Student violation:

```sql
student_violations
------------------
id UUID PK
school_id UUID FK
student_id UUID FK
violation_type_id UUID FK
occurred_at TIMESTAMP
location VARCHAR(150) NULL
description TEXT
action_taken TEXT NULL
reported_by UUID FK
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Evidence:

```sql
violation_evidence
------------------
id UUID PK
student_violation_id UUID FK
file_id UUID FK
created_at TIMESTAMP
```

---

# 26. COUNSELING

```sql
counseling_sessions
-------------------
id UUID PK
school_id UUID FK
student_id UUID FK
counselor_id UUID FK
session_date TIMESTAMP
category VARCHAR(100)
summary TEXT
action_plan TEXT NULL
follow_up_date DATE NULL
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Data counseling harus memiliki permission khusus.

---

# 27. ANNOUNCEMENT

```sql
announcements
-------------
id UUID PK
school_id UUID FK
title VARCHAR(200)
content TEXT
type VARCHAR(50)
priority VARCHAR(20)
published_at TIMESTAMP NULL
expires_at TIMESTAMP NULL
created_by UUID FK
status VARCHAR(30)
created_at TIMESTAMP
updated_at TIMESTAMP
```

Target:

```sql
announcement_targets
--------------------
id UUID PK
announcement_id UUID FK
target_type VARCHAR(30)
target_id UUID NULL
```

Target dapat berupa:

```text
ALL
TEACHER
PARENT
STUDENT
STAFF
CLASS
```

---

# 28. NOTIFICATION

```sql
notifications
-------------
id UUID PK
user_id UUID FK
title VARCHAR(200)
body TEXT
type VARCHAR(50)
data JSONB NULL
read_at TIMESTAMP NULL
created_at TIMESTAMP
```

Delivery:

```sql
notification_deliveries
-----------------------
id UUID PK
notification_id UUID FK
channel VARCHAR(30)
status VARCHAR(30)
attempts INTEGER
sent_at TIMESTAMP NULL
failed_at TIMESTAMP NULL
error_message TEXT NULL
```

Channel:

```text
IN_APP
PUSH
EMAIL
WHATSAPP
SMS
```

---

# 29. FILE MANAGEMENT

```sql
files
-----
id UUID PK
school_id UUID NULL
uploaded_by UUID FK
storage_disk VARCHAR(50)
storage_key TEXT
original_name VARCHAR(255)
mime_type VARCHAR(100)
size_bytes BIGINT
visibility VARCHAR(30)
checksum VARCHAR(128) NULL
created_at TIMESTAMP
deleted_at TIMESTAMP NULL
```

File private tidak boleh diberikan menggunakan URL permanen.

Gunakan:

```text
Signed Temporary URL
```

---

# 30. AUDIT LOG

```sql
audit_logs
----------
id UUID PK
school_id UUID NULL
user_id UUID NULL
action VARCHAR(100)
entity_type VARCHAR(100)
entity_id UUID NULL
old_values JSONB NULL
new_values JSONB NULL
ip_address INET NULL
user_agent TEXT NULL
created_at TIMESTAMP
```

Contoh:

```text
ADMIN mengubah status siswa
GURU mengoreksi absensi
HEADMASTER menyetujui koreksi
ADMIN menghapus data
```

Audit log tidak boleh dapat diedit oleh user biasa.

---

# 31. BILLING

## Plans

```sql
plans
-----
id UUID PK
name VARCHAR(100)
code VARCHAR(50)
billing_cycle VARCHAR(30)
price NUMERIC(15,2)
max_students INTEGER NULL
max_teachers INTEGER NULL
features JSONB
status VARCHAR(30)
```

## Subscriptions

```sql
subscriptions
-------------
id UUID PK
organization_id UUID FK
plan_id UUID FK
started_at TIMESTAMP
ended_at TIMESTAMP NULL
status VARCHAR(30)
```

## Invoices

```sql
invoices
--------
id UUID PK
organization_id UUID FK
invoice_number VARCHAR(100) UNIQUE
amount NUMERIC(15,2)
due_date DATE
status VARCHAR(30)
created_at TIMESTAMP
```

## Payments

```sql
payments
--------
id UUID PK
invoice_id UUID FK
provider VARCHAR(50)
external_reference VARCHAR(150)
amount NUMERIC(15,2)
status VARCHAR(30)
paid_at TIMESTAMP NULL
raw_response JSONB NULL
created_at TIMESTAMP
```

Webhook pembayaran harus:

```text
signature verification
+
idempotency
+
audit
```

---

# 32. ERD LOGIKA UTAMA

```text
ORGANIZATION
     │
     ├──────── SCHOOL
     │            │
     │            ├──── USERS/MEMBERS
     │            ├──── TEACHERS
     │            ├──── STUDENTS
     │            │        │
     │            │        ├── PARENTS
     │            │        ├── ENROLLMENTS
     │            │        ├── ATTENDANCE
     │            │        ├── VIOLATIONS
     │            │        └── COUNSELING
     │            │
     │            ├──── ACADEMIC YEARS
     │            │          └── SEMESTERS
     │            │
     │            ├──── CLASSES
     │            │          └── ENROLLMENTS
     │            │
     │            ├──── SUBJECTS
     │            │
     │            ├──── SCHEDULES
     │            │
     │            ├──── ROOMS
     │            │
     │            ├──── ANNOUNCEMENTS
     │            │
     │            ├──── NOTIFICATIONS
     │            │
     │            ├──── REPORTS
     │            │
     │            └──── AUDIT LOGS
     │
     └──── BILLING
```

---

# 33. UI/UX DESIGN SYSTEM

Sistem harus memiliki satu design system agar:

```text
Web Admin
Teacher Portal
Parent Portal
Mobile App
```

tetap memiliki identitas visual yang sama.

## Design Tokens

### Typography

```text
Font Family:
Inter / equivalent modern sans-serif

Heading:
32 / 28 / 24 / 20

Body:
16 / 14

Caption:
12
```

### Border Radius

```text
Card: 12px
Button: 8px
Input: 8px
Modal: 16px
```

### Spacing

Gunakan basis:

```text
4px
8px
12px
16px
24px
32px
48px
64px
```

---

# 34. ADMIN WEB LAYOUT

```text
┌──────────────────────────────────────────────┐
│ Logo       Search       Notification  User  │
├────────────┬─────────────────────────────────┤
│ Dashboard  │                                 │
│ Students   │          MAIN CONTENT           │
│ Teachers   │                                 │
│ Parents    │                                 │
│ Classes    │                                 │
│ Subjects   │                                 │
│ Schedule   │                                 │
│ Attendance │                                 │
│ Reports    │                                 │
│ Settings   │                                 │
└────────────┴─────────────────────────────────┘
```

Sidebar dapat collapse:

```text
Expanded → 240px
Collapsed → 72px
```

Mobile:

```text
Topbar
Content
Bottom Navigation
```

---

# 35. LOGIN

## Layout

```text
┌────────────────────────────────────────┐
│                                        │
│             SCHOOL LOGO                │
│                                        │
│        Selamat Datang                  │
│        Masuk ke akun Anda              │
│                                        │
│ Email / No. HP                         │
│ [________________________]             │
│                                        │
│ Password                               │
│ [________________________] 👁          │
│                                        │
│ [        MASUK         ]               │
│                                        │
│ Lupa Password?                         │
│                                        │
└────────────────────────────────────────┘
```

Tambahkan:

```text
Remember me
2FA
Captcha/rate limit
```

jika diperlukan.

---

# 36. SCHOOL SETUP WIZARD

Saat sekolah baru dibuat:

### Step 1

```text
Informasi Sekolah
```

### Step 2

```text
Tahun Ajaran
```

### Step 3

```text
Data Guru
```

### Step 4

```text
Data Kelas
```

### Step 5

```text
Mata Pelajaran
```

### Step 6

```text
Import Siswa
```

### Step 7

```text
Selesai
```

Progress:

```text
01 ─ 02 ─ 03 ─ 04 ─ 05 ─ 06 ─ 07
```

---

# 37. DASHBOARD SCHOOL ADMIN

Dashboard harus menjawab:

> "Apa yang terjadi di sekolah hari ini?"

### KPI Cards

```text
┌────────────┐ ┌────────────┐ ┌────────────┐
│ Siswa      │ │ Guru       │ │ Kehadiran  │
│ 1.245      │ │ 82         │ │ 94,2%      │
└────────────┘ └────────────┘ └────────────┘

┌────────────┐
│ Tidak Hadir│
│ 72         │
└────────────┘
```

### Widget

```text
Kehadiran Hari Ini
────────────────────
Hadir       1.120
Terlambat      43
Izin           31
Sakit          22
Alpa           29
```

### Quick Actions

```text
+ Tambah Siswa
+ Tambah Guru
+ Buat Pengumuman
Import Data
Cetak Laporan
```

---

# 38. STUDENT MANAGEMENT

## Student List

```text
Siswa

[ Cari nama/NISN... ] [Filter]

+ Tambah Siswa

Nama          NISN       Kelas      Status
──────────────────────────────────────────
Ahmad         00123      VII-A      Aktif
Budi          00124      VII-A      Aktif
Citra         00125      VII-B      Aktif
```

Filter:

```text
Kelas
Status
Jenis Kelamin
Tahun Ajaran
```

Bulk action:

```text
Export
Import
Aktifkan
Nonaktifkan
Pindahkan Kelas
```

---

# 39. STUDENT DETAIL

Tab:

```text
Profil
Orang Tua
Kelas
Absensi
Pelanggaran
Konseling
Dokumen
Aktivitas
```

Header:

```text
[PHOTO]

Ahmad Fauzan
NISN: XXXXX
VII-A
Aktif
```

---

# 40. TEACHER MANAGEMENT

```text
Guru

[Search] [Filter] [+ Tambah Guru]

Nama
NIP
Mata Pelajaran
Kelas
Status
```

Detail:

```text
Profil
Jadwal
Mata Pelajaran
Kelas
Kehadiran
Aktivitas
```

---

# 41. PARENT MANAGEMENT

Parent dashboard:

```text
Ayah/Ibu
     │
     ├── Anak 1
     ├── Anak 2
     └── Anak 3
```

User dapat memilih anak:

```text
[ Ahmad ▼ ]
```

Kemudian melihat:

```text
Kehadiran
Jadwal
Pengumuman
Pelanggaran
Konseling
```

---

# 42. CLASS MANAGEMENT

```text
VII-A

Wali Kelas:
Budi Santoso

Jumlah Siswa:
32
```

Tabs:

```text
Siswa
Jadwal
Absensi
Mata Pelajaran
```

---

# 43. SCHEDULE

Tampilan:

```text
Senin

07:00 ─────────────────
       Matematika
       VII-A
       Budi
       
08:30 ─────────────────
       Bahasa Indonesia
       VII-A
       Siti
```

Alternatif:

```text
Hari
Guru
Kelas
Ruangan
```

---

# 44. ATTENDANCE UI

Ini salah satu modul paling penting.

## Teacher Mobile

```text
VII-A
Matematika

28 Siswa

✓ Semua Hadir

────────────────────

Ahmad       Hadir
Budi        Hadir
Citra       Sakit
Deni        Izin
Eka         Terlambat

[ SIMPAN ABSENSI ]
```

Bulk action:

```text
Semua Hadir
Semua Alpa
Reset
```

---

# 45. ATTENDANCE ADMIN

Dashboard:

```text
Tanggal: 21 September 2026

Total siswa      1.245
Hadir            1.120
Terlambat           43
Sakit               22
Izin                31
Alpa                29
```

Chart:

```text
Kehadiran 7 Hari
```

---

# 46. ATTENDANCE CORRECTION

Guru tidak boleh bebas mengubah histori.

Flow:

```text
Guru
 ↓
Ajukan Koreksi
 ↓
Isi alasan
 ↓
Submit
 ↓
Admin/Wali Kelas
 ↓
Approve / Reject
 ↓
Audit Log
```

Contoh:

```text
Tanggal:
12 Sep 2026

Data:
ALPA

Ubah menjadi:
HADIR

Alasan:
Kesalahan input

[Ajukan]
```

---

# 47. LESSON MANAGEMENT

Teacher:

```text
Jadwal Hari Ini

07:00
Matematika
VII-A

[Mulai Kelas]
```

Setelah klik:

```text
Mulai Pembelajaran

Absensi
Materi
Catatan
Tugas
```

Selesai:

```text
[ Selesaikan Pembelajaran ]
```

---

# 48. VIOLATION MANAGEMENT

Dashboard:

```text
Pelanggaran

Hari ini       4
Bulan ini     27
Belum selesai  8
```

Student detail:

```text
Nama:
Ahmad

Poin:
25

Riwayat:
• Terlambat
• Tidak memakai atribut
• Pelanggaran tata tertib
```

---

# 49. COUNSELING

Counselor dashboard:

```text
Sesi Hari Ini
Follow Up
Kasus Aktif
Kasus Selesai
```

Data sensitif harus menggunakan authorization khusus.

---

# 50. ANNOUNCEMENT

Editor:

```text
Judul
[________________________]

Isi
[                        ]
[                        ]
[________________________]

Target:
○ Semua
○ Guru
○ Siswa
○ Orang Tua
○ Kelas tertentu

Prioritas:
Normal / Penting / Darurat

[ SIMPAN DRAFT ]
[ PUBLIKASIKAN ]
```

---

# 51. NOTIFICATION CENTER

```text
🔔 Notifikasi

Hari ini

Kehadiran Ahmad
Ahmad tercatat hadir.
08:01

Pengumuman Sekolah
Rapat orang tua...
07:30
```

Unread:

```text
badge 3
```

---

# 52. REPORT CENTER

Jangan membuat semua laporan langsung menghasilkan PDF secara synchronous.

Flow:

```text
Request Report
      ↓
Generate Job
      ↓
Queue
      ↓
Generate File
      ↓
Notification
      ↓
Download
```

Contoh:

```text
Laporan Absensi
Laporan Siswa
Laporan Guru
Laporan Pelanggaran
Laporan Kelas
Laporan Akademik
```

Format:

```text
PDF
XLSX
CSV
```

---

# 53. IMPORT ENGINE

Semua import menggunakan wizard.

```text
Upload
 ↓
Detect Columns
 ↓
Mapping
 ↓
Validation
 ↓
Preview
 ↓
Confirm
 ↓
Queue
 ↓
Processing
 ↓
Result
```

Contoh hasil:

```text
Total       1.245
Berhasil    1.232
Gagal          13

Download Error Report
```

Jangan langsung memasukkan file CSV ke database tanpa validasi.

---

# 54. MOBILE PARENT APP

Bottom navigation:

```text
┌───────────────────────────────┐
│           Dashboard           │
│                               │
│ Halo, Bapak Ahmad             │
│                               │
│ [ Ahmad ▼ ]                   │
│                               │
│ Kehadiran                     │
│ 94%                           │
│                               │
│ Jadwal Hari Ini               │
│ Matematika     07:00          │
│                               │
│ Pengumuman                    │
│ ...                           │
│                               │
├──────┬──────┬──────┬─────────┤
│Home  │Jadwal│Notif │ Profil  │
└──────┴──────┴──────┴─────────┘
```

---

# 55. MOBILE TEACHER

Bottom navigation:

```text
Home
Kelas
Jadwal
Absensi
Profil
```

Dashboard:

```text
Selamat pagi, Pak Budi

3 Kelas Hari Ini

07:00
VII-A
Matematika
[Mulai]

09:00
VIII-B
Matematika
[Mulai]
```

---

# 56. MOBILE STUDENT

Jika aplikasi siswa diaktifkan:

```text
Home
Jadwal
Absensi
Pengumuman
Profil
```

Dashboard:

```text
Kehadiran
96%

Hari Ini
Matematika
Bahasa Indonesia
IPA
```

---

# 57. RESPONSIVE BREAKPOINT

```text
Mobile:
< 640px

Tablet:
640–1024px

Desktop:
> 1024px

Large Desktop:
> 1440px
```

Desktop menggunakan sidebar.

Mobile menggunakan:

```text
Bottom navigation
Drawer
Floating action button
```

---

# 58. UX STATE WAJIB

Setiap halaman harus memiliki:

### Loading

Skeleton, bukan halaman kosong.

### Empty

Contoh:

```text
Belum ada siswa

Tambahkan siswa pertama untuk mulai.
[Tambah Siswa]
```

### Error

```text
Terjadi kesalahan

Silakan coba lagi.

[ Coba Lagi ]
```

### Permission denied

```text
Akses Ditolak

Anda tidak memiliki izin untuk
mengakses halaman ini.
```

### Offline

Mobile:

```text
Tidak ada koneksi

Data terakhir tersedia secara offline.
```

---

# 59. API RESPONSE STANDARD

Success:

```json
{
  "success": true,
  "data": {},
  "meta": {}
}
```

Error:

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Data tidak valid",
    "details": {}
  }
}
```

Pagination:

```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 120,
    "last_page": 6
  }
}
```

---

# 60. API RESOURCE STRUCTURE

```text
/api/v1/auth

/api/v1/me

/api/v1/organizations

/api/v1/schools

/api/v1/students

/api/v1/parents

/api/v1/teachers

/api/v1/classes

/api/v1/subjects

/api/v1/rooms

/api/v1/schedules

/api/v1/attendance

/api/v1/lessons

/api/v1/violations

/api/v1/counseling

/api/v1/announcements

/api/v1/notifications

/api/v1/reports

/api/v1/files

/api/v1/settings

/api/v1/billing
```

---

# 61. BACKEND MODULE STRUCTURE

Laravel:

```text
app/
├── Domain/
│   ├── Auth/
│   ├── Organization/
│   ├── School/
│   ├── Student/
│   ├── Teacher/
│   ├── Parent/
│   ├── Academic/
│   ├── Attendance/
│   ├── Lesson/
│   ├── Violation/
│   ├── Counseling/
│   ├── Announcement/
│   ├── Notification/
│   ├── Report/
│   ├── File/
│   ├── Billing/
│   └── Audit/
│
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
│
├── Jobs/
├── Events/
├── Listeners/
├── Policies/
└── Services/
```

Hindari:

```text
Controllers berisi semua business logic.
```

Business logic harus berada di:

```text
Actions
Services
Domain
Policies
Jobs
```

sesuai kebutuhan.

---

# 62. FRONTEND STRUCTURE

Next.js:

```text
src/
├── app/
│   ├── login/
│   ├── dashboard/
│   ├── students/
│   ├── teachers/
│   ├── parents/
│   ├── classes/
│   ├── subjects/
│   ├── schedules/
│   ├── attendance/
│   ├── reports/
│   ├── notifications/
│   └── settings/
│
├── components/
├── features/
├── hooks/
├── services/
├── lib/
├── types/
└── utils/
```

---

# 63. FLUTTER STRUCTURE

```text
lib/
├── core/
│   ├── network/
│   ├── storage/
│   ├── routing/
│   └── theme/
│
├── features/
│   ├── auth/
│   ├── dashboard/
│   ├── attendance/
│   ├── schedule/
│   ├── notification/
│   └── profile/
│
├── shared/
└── main.dart
```

---

# 64. MULTI-TENANT SECURITY

Setiap request harus memiliki tenant context.

Contoh:

```text
Authenticated User
       ↓
Membership
       ↓
Organization
       ↓
School
       ↓
Resource
```

Jangan hanya melakukan:

```sql
SELECT * FROM students WHERE id = ?
```

Harus:

```sql
SELECT *
FROM students
WHERE id = ?
AND school_id = ?
```

Tenant ID harus berasal dari server-side context.

**Jangan mempercayai `school_id` yang dikirim client sebagai satu-satunya kontrol keamanan.**

---

# 65. DATABASE INDEX WAJIB

Minimal:

```text
students:
school_id
nisn
student_number
status

attendance:
school_id + date
student_id + date
class_id + date

enrollments:
student_id
class_id
academic_year_id

schedules:
school_id
teacher_id
class_id
room_id
day_of_week

notifications:
user_id
read_at
created_at

audit_logs:
school_id
user_id
entity_type
entity_id
created_at
```

Index harus dievaluasi berdasarkan query aktual setelah production traffic tersedia.

---

# 66. TRANSACTION RULE

Operasi yang mengubah beberapa tabel harus menggunakan database transaction.

Contoh:

```text
Create Student
    ↓
Create User
    ↓
Create Parent Relation
    ↓
Create Enrollment
    ↓
Audit Log
```

Jika gagal di tengah:

```text
ROLLBACK
```

Tidak boleh menghasilkan data setengah jadi.

---

# 67. CONCURRENCY

Kasus:

```text
Dua guru membuka absensi
bersamaan.
```

Gunakan:

```text
unique constraint
+
database transaction
+
idempotency
```

Jangan mengandalkan frontend untuk mencegah duplicate.

---

# 68. SECURITY CHECKLIST

Wajib:

```text
✓ HTTPS
✓ Password hashing
✓ Session/token security
✓ Rate limiting
✓ CSRF protection
✓ Input validation
✓ Output escaping
✓ SQL injection protection
✓ Authorization policy
✓ Tenant isolation
✓ Audit log
✓ Signed file URL
✓ Webhook signature
✓ Idempotency
✓ Backup
✓ Restore test
✓ Security headers
✓ Secure cookies
✓ Secret management
```

Untuk administrator:

```text
2FA
```

sangat disarankan.

---

# 69. BACKUP

Production:

```text
Database backup
+
Object storage backup
+
Configuration/secret recovery plan
```

Minimal:

```text
Daily backup
Weekly retention
Monthly retention
```

Tetapi retention final harus mengikuti kebutuhan bisnis dan biaya.

Backup tidak dianggap aman sebelum:

```text
RESTORE TEST
```

berhasil.

---

# 70. OBSERVABILITY

Gunakan:

```text
Application logs
Error tracking
Metrics
Health checks
Queue monitoring
Database monitoring
```

Health endpoint:

```text
GET /health
```

memeriksa:

```text
Application
Database
Redis
Queue
Storage
```

---

# 71. AUDIT CRITICAL ACTIONS

Audit minimal untuk:

```text
Login
Logout
Password change
Role change
Student create/update/delete
Attendance create/update
Attendance correction
Violation create/update
Announcement publish
File access
Billing
Payment
Settings changes
```

---

# 72. MVP PRIORITY

Jangan langsung membangun seluruh modul.

## MVP 1

```text
Auth
School
User
Role
Student
Teacher
Parent
Class
Academic Year
Subject
Schedule
Attendance
Announcement
Notification
Basic Report
```

## MVP 2

```text
Lesson
Violation
Counseling
Import/Export
Advanced Report
Mobile Parent
Mobile Teacher
```

## MVP 3

```text
Billing
Payment
Affiliate
WhatsApp
Advanced Analytics
Attendance Device
Automation
```

---

# 73. DEVELOPMENT ORDER

Urutan implementasi:

```text
01. Infrastructure
02. Database
03. Authentication
04. RBAC
05. Multi-tenancy
06. School
07. Academic
08. Student
09. Teacher
10. Parent
11. Class
12. Subject
13. Schedule
14. Attendance
15. Notification
16. Announcement
17. Reports
18. Mobile
19. Violation
20. Counseling
21. Billing
22. Integrations
```

---

# 74. ACCEPTANCE TEST — STUDENT

Scenario:

```text
Admin membuat siswa
        ↓
Siswa muncul di list
        ↓
Siswa masuk enrollment
        ↓
Siswa masuk kelas
        ↓
Siswa muncul di absensi
        ↓
Parent dapat melihat siswa
```

Semua harus konsisten.

---

# 75. ACCEPTANCE TEST — ATTENDANCE

```text
Teacher login
↓
Pilih kelas
↓
Pilih mata pelajaran
↓
Sistem menampilkan siswa
↓
Teacher mengisi absensi
↓
Submit
↓
Server validasi
↓
Database transaction
↓
Audit log
↓
Parent notification
```

---

# 76. ACCEPTANCE TEST — MULTI-TENANT

Test:

```text
School A User
```

mencoba:

```text
GET Student School B
```

Result:

```text
403 / 404
```

dan tidak boleh ada data School B yang bocor.

Test ini wajib masuk automated integration test.

---

# 77. DEFINITION OF DONE

Sebuah fitur dianggap selesai jika:

```text
✓ UI selesai
✓ API selesai
✓ Database migration selesai
✓ Validation selesai
✓ Authorization selesai
✓ Tenant isolation selesai
✓ Loading state
✓ Empty state
✓ Error state
✓ Mobile responsive
✓ Unit test
✓ Feature/integration test
✓ Audit bila diperlukan
✓ Documentation
✓ Code review
```

---

# 78. FINAL PRODUCT STRUCTURE

Pada akhirnya sistem akan menjadi:

```text
                    SCHOOL MANAGEMENT PLATFORM
                              │
        ┌─────────────────────┼─────────────────────┐
        │                     │                     │
       WEB                  MOBILE                 API
        │                     │                     │
        └─────────────────────┼─────────────────────┘
                              │
                         APPLICATION
                              │
       ┌──────────────┬───────┼────────┬──────────────┐
       │              │       │        │              │
    Academic       Student  Teacher  Attendance    Communication
       │              │       │        │              │
       └──────────────┴───────┼────────┴──────────────┘
                              │
                         PostgreSQL
                              │
                    ┌─────────┴─────────┐
                    │                   │
                  Redis              Storage
                    │
                  Queue
```

---

# 79. HASIL PHASE 3

Setelah tahap ini, tim sudah mempunyai:

```text
✓ Struktur database
✓ Relasi utama
✓ Multi-tenant model
✓ RBAC model
✓ Attendance architecture
✓ Notification architecture
✓ Audit architecture
✓ Billing foundation
✓ API structure
✓ Frontend structure
✓ Mobile structure
✓ Design system
✓ Screen architecture
✓ UX states
✓ Security foundation
✓ Testing foundation
✓ Development order
```

Ini menjadi **blueprint engineering**, bukan sekadar desain tampilan.

---

# 80. PHASE 4 — NEXT STEP

Tahap berikutnya sebaiknya masuk ke **IMPLEMENTATION BLUEPRINT**, yaitu mengubah rancangan di atas menjadi artefak yang benar-benar bisa dikerjakan developer.

Urutannya:

### A. PostgreSQL Schema

Membuat seluruh:

```text
CREATE TABLE
FOREIGN KEY
INDEX
UNIQUE
CHECK CONSTRAINT
ENUM
TRIGGER bila diperlukan
```

### B. Laravel Migration

Struktur migration per module.

### C. Seeder

```text
Roles
Permissions
Default settings
Demo school
Demo users
Demo classes
Demo subjects
```

### D. Laravel API

Endpoint lengkap:

```text
Request
Validation
Controller
Action
Service
Policy
Resource
```

### E. Next.js

Mulai dari:

```text
Login
Dashboard
Student
Teacher
Parent
Class
Schedule
Attendance
```

### F. Flutter

Mulai dari:

```text
Auth
Parent Dashboard
Teacher Dashboard
Attendance
Schedule
Notification
```

### G. Automated Testing

```text
Unit
Feature
Integration
RBAC
Tenant isolation
API
E2E
```

### H. Deployment

```text
GitHub
    ↓
CI
    ↓
Test
    ↓
Build
    ↓
Staging
    ↓
Migration
    ↓
Production
```

**Tahap 4 sebaiknya langsung menghasilkan struktur kode dan migration yang dapat digunakan developer, bukan lagi sekadar konsep.**

# PHASE 4

## IMPLEMENTATION BLUEPRINT

### School Management SaaS

**Backend:** Laravel
**Database:** PostgreSQL
**Frontend:** Next.js + React + TypeScript
**Mobile:** Flutter
**Cache/Queue:** Redis
**Storage:** S3-compatible
**API:** REST `/api/v1`

---

# 1. REPOSITORY STRUCTURE

Gunakan monorepo agar backend, web, mobile, dan dokumentasi dapat dikelola dalam satu repository.

```text
school-management/
│
├── apps/
│   ├── api/
│   ├── web/
│   └── mobile/
│
├── packages/
│   ├── api-contract/
│   ├── design-tokens/
│   └── shared-types/
│
├── infrastructure/
│   ├── docker/
│   ├── nginx/
│   ├── postgres/
│   └── redis/
│
├── docs/
│   ├── architecture/
│   ├── api/
│   ├── database/
│   ├── security/
│   └── deployment/
│
├── .github/
│   └── workflows/
│
├── docker-compose.yml
├── README.md
└── .gitignore
```

---

# 2. BACKEND STRUCTURE

Laravel:

```text
apps/api/

app/
├── Domain/
│   ├── Auth/
│   ├── Organization/
│   ├── School/
│   ├── User/
│   ├── Student/
│   ├── Parent/
│   ├── Teacher/
│   ├── Academic/
│   ├── Class/
│   ├── Subject/
│   ├── Schedule/
│   ├── Attendance/
│   ├── Lesson/
│   ├── Violation/
│   ├── Counseling/
│   ├── Announcement/
│   ├── Notification/
│   ├── Report/
│   ├── File/
│   ├── Billing/
│   └── Audit/
│
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   ├── Resources/
│   └── Middleware/
│
├── Policies/
├── Jobs/
├── Events/
├── Listeners/
├── Notifications/
├── Console/
└── Support/
```

---

# 3. DOMAIN MODULE PATTERN

Setiap module mengikuti pola:

```text
Domain/Student/

├── Actions/
├── DTOs/
├── Events/
├── Exceptions/
├── Models/
├── Queries/
├── Services/
└── ValueObjects/
```

Contoh:

```text
Student/
├── Actions/
│   ├── CreateStudent.php
│   ├── UpdateStudent.php
│   └── DeleteStudent.php
│
├── Models/
│   └── Student.php
│
├── Services/
│   └── StudentService.php
│
└── Queries/
    └── StudentQuery.php
```

Tujuannya agar business logic tidak menumpuk di controller.

---

# 4. DATABASE MIGRATION ORDER

Migration harus mengikuti dependency.

```text
01 organizations
02 schools
03 school_settings

04 users
05 roles
06 permissions
07 role_permissions
08 school_memberships
09 user_roles

10 academic_years
11 semesters

12 teachers
13 parents
14 students
15 parent_students

16 rooms
17 classes
18 student_enrollments

19 subjects
20 teacher_subject_assignments
21 schedules

22 daily_attendance
23 attendance_devices

24 lesson_sessions
25 lesson_attendance

26 violation_types
27 student_violations
28 violation_evidence

29 counseling_sessions

30 announcements
31 announcement_targets

32 notifications
33 notification_deliveries
34 notification_preferences

35 files
36 audit_logs

37 plans
38 subscriptions
39 invoices
40 payments

41 affiliates
42 affiliate_referrals
43 affiliate_commissions
44 affiliate_payouts

45 support_tickets
46 ticket_messages
```

---

# 5. UUID STRATEGY

Gunakan UUID untuk public identifiers.

Recommended:

```text
UUIDv7
```

Keuntungan:

* sortable berdasarkan waktu
* lebih baik untuk index dibanding UUID random murni
* tidak mengekspos sequential ID
* cocok untuk distributed system

Tetap gunakan database-generated timestamps.

---

# 6. ORGANIZATION MIGRATION

Konsep:

```text
organizations
```

memiliki:

```text
id
name
slug
legal_name
email
phone
status
created_at
updated_at
deleted_at
```

Constraint:

```text
slug UNIQUE
```

Status:

```text
ACTIVE
SUSPENDED
INACTIVE
```

---

# 7. SCHOOL MIGRATION

Relationship:

```text
organization
     │
     └── schools
```

Foreign key:

```text
schools.organization_id
    REFERENCES organizations.id
```

Deletion policy:

```text
Organization deletion
≠ automatic hard deletion of schools
```

Untuk production, gunakan soft-delete dan lifecycle policy yang jelas.

---

# 8. USER AUTHENTICATION

User authentication harus terpisah dari school membership.

```text
User
 │
 ├── School A
 │      └── Teacher
 │
 └── School B
        └── Admin
```

User bukan berarti otomatis memiliki akses terhadap seluruh school.

---

# 9. AUTH FLOW

```text
POST /api/v1/auth/login
          │
          ▼
Validate credential
          │
          ▼
Check account status
          │
          ▼
Resolve memberships
          │
          ▼
Issue access token/session
          │
          ▼
Return user context
```

Response:

```json
{
  "user": {
    "id": "uuid",
    "name": "Budi",
    "email": "budi@example.com"
  },
  "schools": [
    {
      "id": "uuid",
      "name": "SMA Example",
      "roles": [
        "TEACHER"
      ]
    }
  ]
}
```

Jangan mengembalikan password hash.

---

# 10. SCHOOL CONTEXT

Setelah login, user memilih school jika memiliki lebih dari satu school.

```text
GET /api/v1/me/schools
```

Kemudian:

```text
POST /api/v1/context/school
```

Server menyimpan context melalui session/token claims sesuai strategi authentication.

Tetap lakukan authorization pada setiap request.

---

# 11. RBAC

Permission berbentuk:

```text
module.action
```

Contoh:

```text
student.view
student.create
student.update
student.delete
student.export

attendance.view
attendance.create
attendance.update
attendance.approve
attendance.export

teacher.view
teacher.create
teacher.update
teacher.delete

schedule.view
schedule.create
schedule.update
schedule.delete

report.view
report.export
```

---

# 12. POLICY CHECK

Setiap resource harus melewati:

```text
Authentication
      ↓
Membership
      ↓
Role
      ↓
Permission
      ↓
Tenant Scope
      ↓
Resource Ownership/Access
```

Contoh:

```text
StudentPolicy@view
```

harus memastikan:

```text
user authenticated
+
user member of school
+
permission student.view
+
student belongs to school
```

---

# 13. API ROUTES

Struktur:

```text
/api/v1
```

## Auth

```text
POST   /auth/login
POST   /auth/logout
POST   /auth/refresh
POST   /auth/forgot-password
POST   /auth/reset-password
GET    /auth/me
```

---

# 14. ORGANIZATION API

```text
GET    /organizations
POST   /organizations
GET    /organizations/{organization}
PATCH  /organizations/{organization}
DELETE /organizations/{organization}
```

Hanya role platform tertentu yang boleh mengakses endpoint global.

---

# 15. SCHOOL API

```text
GET    /schools
POST   /schools
GET    /schools/{school}
PATCH  /schools/{school}
DELETE /schools/{school}

GET    /schools/{school}/settings
PATCH  /schools/{school}/settings
```

---

# 16. STUDENT API

```text
GET    /students
POST   /students
GET    /students/{student}
PATCH  /students/{student}
DELETE /students/{student}

POST   /students/import
GET    /students/export

GET    /students/{student}/attendance
GET    /students/{student}/violations
GET    /students/{student}/counseling
GET    /students/{student}/parents
```

Query:

```text
?page=1
&per_page=20
&search=ahmad
&class_id=uuid
&status=ACTIVE
```

---

# 17. TEACHER API

```text
GET    /teachers
POST   /teachers
GET    /teachers/{teacher}
PATCH  /teachers/{teacher}
DELETE /teachers/{teacher}

GET    /teachers/{teacher}/schedule
GET    /teachers/{teacher}/classes
```

---

# 18. PARENT API

```text
GET    /parents
POST   /parents
GET    /parents/{parent}
PATCH  /parents/{parent}

GET    /parents/{parent}/children
POST   /parents/{parent}/children
DELETE /parents/{parent}/children/{student}
```

---

# 19. CLASS API

```text
GET    /classes
POST   /classes
GET    /classes/{class}
PATCH  /classes/{class}
DELETE /classes/{class}

GET    /classes/{class}/students
GET    /classes/{class}/schedule
GET    /classes/{class}/attendance
```

---

# 20. SUBJECT API

```text
GET    /subjects
POST   /subjects
GET    /subjects/{subject}
PATCH  /subjects/{subject}
DELETE /subjects/{subject}
```

---

# 21. SCHEDULE API

```text
GET    /schedules
POST   /schedules
GET    /schedules/{schedule}
PATCH  /schedules/{schedule}
DELETE /schedules/{schedule}
```

Server harus menjalankan conflict detection:

```text
Teacher conflict
Class conflict
Room conflict
```

---

# 22. ATTENDANCE API

```text
GET    /attendance
POST   /attendance
GET    /attendance/{attendance}
PATCH  /attendance/{attendance}

POST   /attendance/bulk
POST   /attendance/corrections
GET    /attendance/corrections
POST   /attendance/corrections/{id}/approve
POST   /attendance/corrections/{id}/reject
```

---

# 23. BULK ATTENDANCE

Request:

```json
{
  "class_id": "uuid",
  "date": "2026-09-21",
  "records": [
    {
      "student_id": "uuid",
      "status": "PRESENT"
    },
    {
      "student_id": "uuid",
      "status": "LATE"
    }
  ]
}
```

Server:

```text
validate class
validate students
validate enrollment
validate date
validate duplicates
transaction
save
audit
dispatch notifications
```

---

# 24. ATTENDANCE IDEMPOTENCY

Mobile dapat mengalami retry karena koneksi buruk.

Gunakan:

```text
Idempotency-Key
```

Contoh:

```text
Idempotency-Key:
attendance-uuid-generated-by-client
```

Server harus memastikan request yang sama tidak menghasilkan dua record.

---

# 25. LESSON API

```text
GET    /lesson-sessions
POST   /lesson-sessions
GET    /lesson-sessions/{session}
PATCH  /lesson-sessions/{session}

POST   /lesson-sessions/{session}/start
POST   /lesson-sessions/{session}/finish

GET    /lesson-sessions/{session}/attendance
POST   /lesson-sessions/{session}/attendance
```

---

# 26. VIOLATION API

```text
GET    /violation-types
POST   /violation-types
PATCH  /violation-types/{type}
DELETE /violation-types/{type}

GET    /student-violations
POST   /student-violations
GET    /student-violations/{violation}
PATCH  /student-violations/{violation}
```

Evidence:

```text
POST /student-violations/{id}/evidence
DELETE /student-violations/{id}/evidence/{file}
```

---

# 27. COUNSELING API

```text
GET    /counseling-sessions
POST   /counseling-sessions
GET    /counseling-sessions/{session}
PATCH  /counseling-sessions/{session}
```

Access harus dibatasi.

Parent/student tidak otomatis memperoleh seluruh detail counseling internal.

---

# 28. ANNOUNCEMENT API

```text
GET    /announcements
POST   /announcements
GET    /announcements/{announcement}
PATCH  /announcements/{announcement}
DELETE /announcements/{announcement}

POST   /announcements/{id}/publish
POST   /announcements/{id}/archive
```

Publish harus menghasilkan event:

```text
AnnouncementPublished
```

Kemudian listener mengirim notification.

---

# 29. NOTIFICATION API

```text
GET    /notifications
POST   /notifications/{id}/read
POST   /notifications/read-all

GET    /notification-preferences
PATCH  /notification-preferences
```

---

# 30. REPORT API

```text
GET  /reports
POST /reports/attendance
POST /reports/students
POST /reports/teachers
POST /reports/violations
```

Response untuk laporan besar:

```json
{
  "job_id": "uuid",
  "status": "QUEUED"
}
```

Kemudian:

```text
GET /reports/jobs/{job}
```

---

# 31. FILE API

```text
POST   /files
GET    /files/{file}
DELETE /files/{file}

POST   /files/{file}/signed-url
```

Upload:

```text
client
 ↓
API authorization
 ↓
file validation
 ↓
object storage
 ↓
database metadata
```

---

# 32. API ERROR CODES

Gunakan standard:

```text
VALIDATION_ERROR
UNAUTHENTICATED
FORBIDDEN
NOT_FOUND
TENANT_ACCESS_DENIED
CONFLICT
DUPLICATE_RESOURCE
RATE_LIMITED
FILE_TOO_LARGE
UNSUPPORTED_FILE_TYPE
INTERNAL_ERROR
```

HTTP status:

```text
400
401
403
404
409
422
429
500
```

---

# 33. GLOBAL API MIDDLEWARE

```text
Request
 ↓
CORS
 ↓
Security headers
 ↓
Rate limit
 ↓
Authentication
 ↓
Tenant context
 ↓
Controller
```

Untuk endpoint sensitif, gunakan rate limit lebih ketat.

---

# 34. SERVICE LAYER

Contoh:

```text
CreateStudent
```

Flow:

```text
Controller
    ↓
FormRequest
    ↓
CreateStudent Action
    ↓
Transaction
    ↓
Student Model
    ↓
Enrollment
    ↓
Event
    ↓
Audit
```

Controller jangan melakukan seluruh proses tersebut sendiri.

---

# 35. EVENT ARCHITECTURE

Contoh event:

```text
StudentCreated
StudentUpdated
StudentTransferred
AttendanceRecorded
AttendanceCorrected
AnnouncementPublished
ViolationCreated
PaymentCompleted
```

Listener:

```text
AttendanceRecorded
        │
        ├── AuditAttendance
        ├── NotifyParent
        └── UpdateAttendanceStatistics
```

---

# 36. QUEUE JOBS

Gunakan Redis queue untuk:

```text
SendNotification
GenerateReport
ProcessStudentImport
ProcessTeacherImport
ExportStudents
SendEmail
SendWhatsApp
GenerateInvoice
ProcessPayment
ResizeImage
CleanupFiles
```

Jangan menjalankan pekerjaan berat di HTTP request.

---

# 37. IMPORT STUDENTS

File:

```text
students.xlsx
```

Flow:

```text
Upload
 ↓
Parse
 ↓
Map Columns
 ↓
Validate
 ↓
Preview
 ↓
Confirm
 ↓
Queue Job
 ↓
Process chunks
 ↓
Result
```

Untuk data besar, gunakan chunk processing.

---

# 38. IMPORT VALIDATION

Contoh:

```text
Nama kosong
→ ERROR

NISN duplicate
→ ERROR

Format tanggal salah
→ ERROR

Kelas tidak ditemukan
→ ERROR

Data valid
→ READY
```

Jangan memasukkan row invalid ke database.

---

# 39. PROMOTION ENGINE

Di akhir tahun:

```text
Academic Year 2026/2027
          ↓
Review enrollment
          ↓
Promotion
          ↓
Create 2027/2028 enrollment
```

Contoh:

```text
VII-A
 ↓
VIII-A
```

Sistem harus menyimpan histori.

Jangan mengubah enrollment lama.

---

# 40. ACADEMIC YEAR CLOSING

Flow:

```text
Admin
 ↓
Review data
 ↓
Lock attendance
 ↓
Lock academic records
 ↓
Close semester
 ↓
Close academic year
 ↓
Create next academic year
```

Setelah locked:

```text
Normal user
→ cannot edit
```

Koreksi harus melalui workflow khusus.

---

# 41. FRONTEND APPLICATION

Gunakan:

```text
Next.js
TypeScript
React
React Query/TanStack Query
Form validation
Component library
```

Frontend tidak boleh menjadi sumber authorization.

Frontend hanya mencerminkan permission.

Security tetap berada di backend.

---

# 42. FRONTEND ROUTING

```text
/login

/dashboard

/students
/students/[id]

/teachers
/teachers/[id]

/parents
/parents/[id]

/classes
/classes/[id]

/subjects

/schedules

/attendance

/lessons

/violations

/counseling

/announcements

/notifications

/reports

/settings

/billing
```

---

# 43. DASHBOARD COMPONENTS

```text
Dashboard
├── Header
├── KPIGrid
├── AttendanceOverview
├── TodaySchedule
├── RecentAnnouncements
├── RecentActivities
└── QuickActions
```

Component harus reusable.

---

# 44. TABLE COMPONENT

Semua data table harus mendukung:

```text
Search
Filter
Sort
Pagination
Column visibility
Export
Bulk action
Responsive behavior
```

Mobile jangan memaksakan tabel desktop.

Gunakan:

```text
Card list
```

untuk layar kecil.

---

# 45. FORM SYSTEM

Semua form menggunakan:

```text
Label
Input
Helper text
Validation
Error state
Loading state
Success state
```

Contoh:

```text
Nama Lengkap *
[_____________________]

NISN
[_____________________]

Kelas *
[ Pilih kelas ▼ ]

[ Simpan ]
```

---

# 46. DELETE CONFIRMATION

Jangan gunakan:

```text
Are you sure?
```

Gunakan context:

```text
Hapus Siswa?

Data siswa akan dinonaktifkan dan
tidak dapat digunakan pada transaksi
baru.

[ Batal ] [ Nonaktifkan ]
```

Untuk data historis, prefer soft delete/archive daripada hard delete.

---

# 47. DESIGN SYSTEM COMPONENTS

Minimal:

```text
Button
Input
Select
Textarea
Checkbox
Radio
Switch
DatePicker
TimePicker
Modal
Drawer
Dropdown
Tooltip
Toast
Badge
Avatar
Card
Table
Tabs
Pagination
Skeleton
EmptyState
ErrorState
Breadcrumb
FileUploader
```

---

# 48. MOBILE OFFLINE STRATEGY

Mobile teacher harus tetap usable ketika koneksi tidak stabil.

Strategi:

```text
API
 ↓
Local database/cache
 ↓
Offline queue
 ↓
Connection restored
 ↓
Sync
```

Status:

```text
SYNCED
PENDING
FAILED
```

Jangan menganggap data sudah tersimpan di server hanya karena tersimpan di device.

---

# 49. CONFLICT RESOLUTION

Contoh:

```text
Teacher A
mengisi absensi offline

Teacher B
mengubah data online
```

Saat sync:

```text
Server timestamp
+
version number
+
conflict detection
```

Untuk data sensitif, jangan diam-diam overwrite.

Tampilkan conflict yang membutuhkan keputusan.

---

# 50. NOTIFICATION ARCHITECTURE

```text
Business Event
      ↓
Notification Service
      ↓
Determine recipients
      ↓
Create notification
      ↓
Queue deliveries
      ↓
 ┌────┼────┬─────┐
Push Email WhatsApp SMS
```

Setiap channel memiliki retry sendiri.

---

# 51. WHATSAPP INTEGRATION

Abstraksikan provider:

```text
WhatsAppProviderInterface
```

Implementasi:

```text
Provider A
Provider B
Provider C
```

Dengan demikian jika provider berubah, business logic tidak perlu diubah.

---

# 52. PAYMENT ARCHITECTURE

```text
Create Invoice
      ↓
Payment Gateway
      ↓
Payment Page
      ↓
Webhook
      ↓
Verify Signature
      ↓
Check Idempotency
      ↓
Mark Paid
      ↓
Audit
      ↓
Notification
```

Jangan menganggap redirect browser sebagai bukti pembayaran.

Webhook/provider verification adalah sumber status pembayaran.

---

# 53. TESTING PYRAMID

```text
             E2E
            /   \
       Integration
         /       \
       Feature   API
         \       /
           Unit
```

Target utama:

```text
Unit:
business rules

Feature:
endpoint behavior

Integration:
database + services

E2E:
critical user journey
```

---

# 54. CRITICAL E2E FLOWS

Wajib diuji:

```text
Register school
Login
Create student
Create teacher
Create class
Enroll student
Create schedule
Record attendance
Correct attendance
Publish announcement
Parent receives notification
Generate report
Create invoice
Process payment
```

---

# 55. TENANT ISOLATION TEST

Test:

```text
School A
Student A
```

dan:

```text
School B
Student B
```

User A mencoba:

```text
GET /students/{studentB}
```

Expected:

```text
403 atau 404
```

Tidak boleh:

```text
200 + Student B
```

Ini adalah salah satu security test paling penting.

---

# 56. CI PIPELINE

Setiap pull request:

```text
Git push
 ↓
Lint
 ↓
Type check
 ↓
Unit tests
 ↓
Feature tests
 ↓
Build
 ↓
Security checks
```

Jika gagal:

```text
Merge blocked
```

---

# 57. BRANCH STRATEGY

Sederhana:

```text
main
develop
feature/*
fix/*
hotfix/*
```

Production hanya dari:

```text
main
```

Pull request wajib melalui review.

---

# 58. ENVIRONMENT

```text
LOCAL
STAGING
PRODUCTION
```

Database berbeda.

Storage berbeda.

API keys berbeda.

Payment sandbox berbeda dari production.

Tidak boleh menggunakan credential production di local development.

---

# 59. SECRET MANAGEMENT

Jangan commit:

```text
.env
private keys
API secret
payment secret
database password
JWT secret
```

Gunakan secret manager/platform environment variables.

---

# 60. DEPLOYMENT PIPELINE

```text
Developer
   ↓
Git Push
   ↓
CI
   ↓
Tests
   ↓
Build
   ↓
Staging
   ↓
QA
   ↓
Approval
   ↓
Production
```

Database migration production harus memiliki prosedur rollback/recovery.

---

# 61. DATABASE MIGRATION RULE

Migration production:

```text
Backward compatible
```

Hindari deployment yang langsung:

```text
rename column
+
deploy old application
```

yang menyebabkan downtime.

Gunakan pola:

```text
Add new column
 ↓
Deploy code
 ↓
Backfill
 ↓
Switch reads/writes
 ↓
Remove old column later
```

---

# 62. PERFORMANCE TARGET

Target awal:

```text
API normal:
< 500ms

Simple database query:
< 100ms

Dashboard:
< 2s

Search:
< 500ms
```

Ini adalah target engineering awal, bukan SLA final.

Performa aktual harus diukur menggunakan production-like workload.

---

# 63. CACHING

Cache kandidat:

```text
School settings
Permissions
Academic year
Subjects
Classes
Dashboard aggregates
```

Jangan cache data yang mudah berubah tanpa invalidation strategy.

Contoh:

```text
attendance
```

harus lebih hati-hati daripada:

```text
school settings
```

---

# 64. DATABASE PERFORMANCE

Gunakan:

```text
EXPLAIN ANALYZE
```

untuk query berat.

Monitor:

```text
Slow query
Sequential scan
Index usage
Connection count
Lock
Deadlock
```

---

# 65. SECURITY INCIDENT FLOW

Jika terjadi:

```text
Data leakage
Account takeover
Payment anomaly
```

Flow:

```text
Detect
 ↓
Contain
 ↓
Investigate
 ↓
Preserve logs
 ↓
Remediate
 ↓
Verify
 ↓
Document
```

Audit log jangan dihapus selama investigasi.

---

# 66. ADMIN PERMISSION MATRIX

Contoh baseline:

| Modul        | Super Admin | School Admin | Headmaster | Teacher     | Parent      |
| ------------ | ----------- | ------------ | ---------- | ----------- | ----------- |
| School       | Full        | View/Edit    | View       | -           | -           |
| Student      | Full        | Full         | View       | View        | Own Child   |
| Teacher      | Full        | Full         | View       | Own         | -           |
| Class        | Full        | Full         | View       | View        | Child Class |
| Schedule     | Full        | Full         | View       | View        | Child       |
| Attendance   | Full        | Full         | Approve    | Create/View | Own Child   |
| Violation    | Full        | Full         | View       | Create/View | Limited     |
| Counseling   | Full        | Restricted   | Restricted | Limited     | Limited     |
| Announcement | Full        | Full         | Publish    | View        | View        |
| Billing      | Full        | View         | -          | -           | -           |

**Catatan:** matrix ini adalah baseline desain dan harus disesuaikan dengan kebijakan sekolah.

---

# 67. AUDITABLE ADMIN ACTION

Untuk setiap perubahan penting:

```text
WHO
WHAT
WHEN
WHERE
BEFORE
AFTER
```

Contoh:

```text
User:
Admin Budi

Action:
ATTENDANCE_UPDATED

Student:
Ahmad

Before:
ABSENT

After:
PRESENT

Reason:
Kesalahan input

IP:
...

Time:
...
```

---

# 68. DOCUMENTATION

Repository wajib memiliki:

```text
README.md

docs/
├── architecture.md
├── database.md
├── authentication.md
├── authorization.md
├── multi-tenancy.md
├── api.md
├── deployment.md
├── backup.md
├── security.md
└── contributing.md
```

---

# 69. OPENAPI

API harus terdokumentasi menggunakan OpenAPI.

Dokumentasikan:

```text
Endpoint
Parameters
Request
Response
Authentication
Errors
Examples
```

Dari OpenAPI, beberapa client type dapat digenerate.

---

# 70. SEED DATA

Development environment harus memiliki:

```text
Organization Demo
School Demo
Admin Demo
Headmaster Demo
Teacher Demo
Parent Demo
Student Demo
Classes
Subjects
Schedules
Attendance
Announcements
```

Akun demo jangan pernah digunakan di production.

---

# 71. INITIAL RBAC SEED

Baseline:

```text
SUPER_ADMIN
PLATFORM_ADMIN
ORGANIZATION_ADMIN
SCHOOL_ADMIN
HEADMASTER
TEACHER
HOMEROOM_TEACHER
COUNSELOR
STAFF
STUDENT
PARENT
```

Permission dibuat secara deklaratif:

```text
student.view
student.create
student.update
...
```

Kemudian role mendapatkan permission melalui seeder.

---

# 72. SUPER ADMIN PANEL

Platform administrator membutuhkan dashboard terpisah.

```text
┌─────────────────────────────────────────┐
│ Platform Dashboard                      │
├─────────────────────────────────────────┤
│ Organizations     128                   │
│ Schools           342                   │
│ Students        84,291                  │
│ Active Users    12,492                  │
├─────────────────────────────────────────┤
│ Subscription Overview                   │
│ Revenue Overview                        │
│ System Health                           │
│ Queue Health                            │
└─────────────────────────────────────────┘
```

Super Admin tidak boleh menggunakan UI school biasa untuk semua operasi platform.

---

# 73. SYSTEM HEALTH

Dashboard internal:

```text
Application     HEALTHY
Database        HEALTHY
Redis           HEALTHY
Queue           HEALTHY
Storage         HEALTHY
Notifications   DEGRADED
```

Jika queue gagal:

```text
Attendance
```

tetap harus dapat tersimpan.

Notification tidak boleh menjadi dependency wajib dari transaksi utama.

---

# 74. TRANSACTION BOUNDARY

Contoh attendance:

```text
BEGIN
  validate
  insert/update attendance
  insert audit
COMMIT

AFTER COMMIT
  dispatch notification
```

Jangan membuat transaksi database bergantung pada keberhasilan WhatsApp/email.

---

# 75. DATA RETENTION

Buat policy per kategori:

```text
Operational data
Audit data
Notification data
File data
Billing data
Support data
```

Retention tidak boleh ditentukan hanya berdasarkan kebutuhan storage.

Pertimbangkan:

```text
legal
operational
security
school policy
```

---

# 76. PRIVACY BY DESIGN

Minimalkan data yang dikumpulkan.

Contoh:

```text
NIK
```

tidak boleh ditampilkan di seluruh halaman hanya karena tersedia di database.

UI sebaiknya:

```text
********1234
```

untuk field sensitif jika memang tidak perlu ditampilkan penuh.

---

# 77. DATA EXPORT

Export juga harus melewati authorization.

Jangan:

```text
GET /students/export
```

langsung memberikan semua data.

Harus menerapkan:

```text
school scope
permission
filter
audit
```

Untuk export besar:

```text
background job
```

---

# 78. FINAL IMPLEMENTATION STACK

```text
                 USERS
                   │
        ┌──────────┴──────────┐
        │                     │
      WEB                   MOBILE
   Next.js                  Flutter
        │                     │
        └──────────┬──────────┘
                   │
                 REST API
                   │
                Laravel
                   │
       ┌───────────┼───────────┐
       │           │           │
 PostgreSQL      Redis      Storage
       │           │           │
       │         Queue         │
       │           │           │
       └───────────┼───────────┘
                   │
              Integrations
          ┌────────┼────────┐
       Push      Email   WhatsApp
```

---

# 79. DEVELOPMENT EPICS

## EPIC 01 — Foundation

```text
Repository
Docker
Environment
CI/CD
Database
Logging
```

## EPIC 02 — Authentication

```text
Login
Logout
Password
Verification
Session
```

## EPIC 03 — Authorization

```text
RBAC
Permissions
Policies
Tenant context
```

## EPIC 04 — School

```text
Organization
School
Settings
Membership
```

## EPIC 05 — Academic

```text
Academic year
Semester
Class
Subject
Room
Schedule
```

## EPIC 06 — People

```text
Student
Parent
Teacher
Staff
```

## EPIC 07 — Attendance

```text
Daily attendance
Lesson attendance
Correction
Approval
Reports
```

## EPIC 08 — Communication

```text
Announcement
Notification
Push
Email
WhatsApp
```

## EPIC 09 — Student Affairs

```text
Violation
Counseling
```

## EPIC 10 — Reporting

```text
Dashboard
PDF
XLSX
CSV
```

## EPIC 11 — Mobile

```text
Parent
Teacher
Student
```

## EPIC 12 — Monetization

```text
Plans
Subscription
Invoice
Payment
Affiliate
```

---

# 80. SPRINT BREAKDOWN

### Sprint 1

```text
Repository
Infrastructure
Database foundation
Authentication
```

### Sprint 2

```text
RBAC
Multi-tenancy
School
User
```

### Sprint 3

```text
Student
Teacher
Parent
Class
```

### Sprint 4

```text
Academic year
Semester
Subject
Room
Schedule
```

### Sprint 5

```text
Attendance
```

### Sprint 6

```text
Announcement
Notification
```

### Sprint 7

```text
Reports
Import
Export
```

### Sprint 8

```text
Lesson
Violation
Counseling
```

### Sprint 9

```text
Mobile Parent
Mobile Teacher
```

### Sprint 10

```text
Billing
Payment
Production hardening
```

---

# 81. RELEASE STRATEGY

Jangan langsung meluncurkan seluruh platform.

## Release 0.1

```text
Internal development
```

## Release 0.5

```text
Pilot school
```

## Release 0.9

```text
Limited production
```

## Release 1.0

```text
General availability
```

---

# 82. PILOT SCHOOL

Untuk pilot:

```text
1–3 sekolah
```

fokus pada:

```text
Login
Student
Teacher
Class
Schedule
Attendance
Announcement
Parent notification
```

Kumpulkan:

```text
bug
UX friction
performance
missing workflow
support tickets
```

sebelum memperluas fitur.

---

# 83. PRODUCT KPI

Pantau:

```text
Active Schools
Active Teachers
Active Parents
Daily Attendance Usage
Monthly Active Users
Notification Delivery Rate
Import Success Rate
Report Generation Time
API Error Rate
Average Response Time
```

Business KPI:

```text
Trial → Paid conversion
School retention
Subscription renewal
Churn
Average Revenue per School
```

---

# 84. CRITICAL ENGINEERING PRINCIPLE

Ada lima hal yang **tidak boleh dikorbankan** hanya demi mempercepat MVP:

```text
1. Tenant isolation
2. Authorization
3. Data integrity
4. Auditability
5. Backup/recovery
```

UI dapat disederhanakan.

Fitur dapat ditunda.

Tetapi lima fondasi tersebut harus benar sejak awal.

---

# 85. DEVELOPER HANDOFF CHECKLIST

Sebelum developer mulai:

```text
□ Architecture approved
□ Database ERD approved
□ Migration order approved
□ RBAC matrix approved
□ API convention approved
□ UI design system approved
□ Environment strategy approved
□ Git strategy approved
□ Testing strategy approved
□ Backup strategy approved
```

---

# 86. HASIL AKHIR PHASE 4

Pada titik ini kita sudah mempunyai:

```text
                    PRODUCT
                       │
              ┌────────┴────────┐
              │                 │
           BUSINESS          TECHNICAL
              │                 │
          Features          Architecture
          Workflows          Database
          Roles              API
          Reports            Security
                             Testing
                             Deployment
                               │
                    ┌──────────┴──────────┐
                    │                     │
                  WEB                   MOBILE
                Next.js                 Flutter
                    │                     │
                    └──────────┬──────────┘
                               │
                             API
                               │
                            Laravel
                               │
                    ┌──────────┴──────────┐
                    │                     │
                PostgreSQL              Redis
```

Blueprint ini sudah cukup untuk masuk ke **fase implementasi nyata**.

---

# PHASE 5 — BUILD SPECIFICATION

Tahap berikutnya bukan lagi membahas konsep.

Kita masuk ke:

### 1. ERD FINAL

Seluruh tabel, field, type, relation, index, constraint.

### 2. POSTGRESQL

DDL lengkap:

```text
CREATE TABLE
CREATE INDEX
CREATE UNIQUE INDEX
FOREIGN KEY
CHECK
```

### 3. LARAVEL

Migration:

```text
2026_xx_xx_create_organizations_table.php
2026_xx_xx_create_schools_table.php
...
```

### 4. SEEDER

```text
RoleSeeder
PermissionSeeder
SchoolSeeder
DemoUserSeeder
```

### 5. API CONTRACT

Setiap endpoint akan ditulis dengan:

```text
METHOD
URL
AUTH
PERMISSION
REQUEST
VALIDATION
RESPONSE
ERROR
```

### 6. UI SCREEN SPECIFICATION

Setiap screen akan memiliki:

```text
URL
Role
Purpose
Components
Fields
Actions
Validation
Loading
Empty
Error
Permission
Responsive behavior
```

### 7. MOBILE SPECIFICATION

Parent dan Teacher akan dibedah screen-by-screen.

### 8. TEST CASE

Setiap modul memiliki:

```text
Happy path
Validation
Authorization
Tenant isolation
Edge case
Failure case
```

### 9. PRODUCTION CHECKLIST

```text
Security
Performance
Backup
Monitoring
Logging
Deployment
Rollback
```

**Phase 5 adalah tahap yang tepat jika tujuan akhirnya adalah membuat sistem ini benar-benar bisa mulai dikoding, bukan sekadar menjadi dokumen perencanaan.**

# PHASE 5

## BUILD SPECIFICATION

### School Management SaaS

---

# 1. FINAL DATABASE MAP

Relasi inti:

```text
organizations
    │
    └── schools
          │
          ├── school_settings
          ├── memberships ── users
          │                    │
          │                    └── user_roles ── roles
          │                                      │
          │                                      └── permissions
          │
          ├── academic_years
          │      └── semesters
          │
          ├── teachers
          │
          ├── parents
          │      └── parent_students ── students
          │
          ├── classes
          │      └── student_enrollments ── students
          │
          ├── subjects
          │      └── teacher_subject_assignments
          │
          ├── rooms
          │
          ├── schedules
          │
          ├── daily_attendance
          │
          ├── lesson_sessions
          │      └── lesson_attendance
          │
          ├── violation_types
          │      └── student_violations
          │
          ├── counseling_sessions
          │
          ├── announcements
          │      └── announcement_targets
          │
          ├── notifications
          │      └── notification_deliveries
          │
          ├── files
          └── audit_logs
```

---

# 2. DATABASE CONVENTION

Semua tabel:

```text
id UUID
created_at TIMESTAMP
updated_at TIMESTAMP
```

Jika diperlukan:

```text
deleted_at TIMESTAMP NULL
```

Nama:

```text
snake_case
```

Primary key:

```text
id
```

Foreign key:

```text
<entity>_id
```

Contoh:

```text
school_id
student_id
teacher_id
class_id
```

---

# 3. POSTGRESQL EXTENSIONS

Jika menggunakan UUIDv7 melalui aplikasi/database strategy yang dipilih, pastikan environment PostgreSQL mendukung mekanisme UUID yang digunakan.

Extension yang umum diperlukan:

```sql id="8o1qye"
CREATE EXTENSION IF NOT EXISTS pgcrypto;
```

Jika menggunakan `gen_random_uuid()` sebagai fallback.

Untuk UUIDv7, gunakan implementasi yang konsisten di application layer atau extension yang telah diuji pada versi PostgreSQL target.

---

# 4. ORGANIZATIONS

```sql id="u8xvce"
CREATE TABLE organizations (
    id UUID PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    legal_name VARCHAR(200),
    email VARCHAR(150),
    phone VARCHAR(50),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

Constraint:

```text id="6uyhdf"
status ∈ ACTIVE, SUSPENDED, INACTIVE
```

---

# 5. SCHOOLS

```sql id="1wwf5y"
CREATE TABLE schools (
    id UUID PRIMARY KEY,
    organization_id UUID NOT NULL,
    name VARCHAR(200) NOT NULL,
    npsn VARCHAR(50),
    school_code VARCHAR(50) NOT NULL,
    level VARCHAR(30) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    address TEXT,
    village VARCHAR(100),
    district VARCHAR(100),
    regency VARCHAR(100),
    province VARCHAR(100),
    postal_code VARCHAR(20),
    latitude DECIMAL(10,7),
    longitude DECIMAL(10,7),
    logo_file_id UUID,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL,

    CONSTRAINT fk_school_org
        FOREIGN KEY (organization_id)
        REFERENCES organizations(id)
);
```

Index:

```sql id="xw42l4"
CREATE INDEX idx_schools_org
ON schools(organization_id);

CREATE UNIQUE INDEX uq_school_code
ON schools(organization_id, school_code);
```

---

# 6. SCHOOL SETTINGS

```sql id="7o9uyy"
CREATE TABLE school_settings (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL UNIQUE,
    timezone VARCHAR(50) NOT NULL DEFAULT 'Asia/Makassar',
    currency VARCHAR(10) NOT NULL DEFAULT 'IDR',
    attendance_late_threshold INTEGER NOT NULL DEFAULT 15,
    attendance_auto_close BOOLEAN NOT NULL DEFAULT FALSE,
    parent_notification_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    whatsapp_enabled BOOLEAN NOT NULL DEFAULT FALSE,
    email_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    push_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY (school_id)
        REFERENCES schools(id)
);
```

---

# 7. USERS

```sql id="3r9qmt"
CREATE TABLE users (
    id UUID PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE,
    phone VARCHAR(30) UNIQUE,
    password_hash TEXT NOT NULL,
    avatar_file_id UUID,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    email_verified_at TIMESTAMP NULL,
    phone_verified_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL
);
```

---

# 8. MEMBERSHIP

```sql id="p4yxx4"
CREATE TABLE school_memberships (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    user_id UUID NOT NULL,
    employee_number VARCHAR(100),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    joined_at DATE,
    left_at DATE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY (school_id)
        REFERENCES schools(id),

    FOREIGN KEY (user_id)
        REFERENCES users(id)
);
```

Unique:

```sql id="n2y4l0"
CREATE UNIQUE INDEX uq_school_user
ON school_memberships(school_id, user_id);
```

---

# 9. ROLES

```sql id="g5z1ot"
CREATE TABLE roles (
    id UUID PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    scope VARCHAR(30) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

---

# 10. PERMISSIONS

```sql id="f4g1px"
CREATE TABLE permissions (
    id UUID PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(150) NOT NULL UNIQUE,
    module VARCHAR(50) NOT NULL,
    action VARCHAR(50) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

---

# 11. ROLE PERMISSION

```sql id="4sg9nd"
CREATE TABLE role_permissions (
    role_id UUID NOT NULL,
    permission_id UUID NOT NULL,

    PRIMARY KEY(role_id, permission_id),

    FOREIGN KEY(role_id)
        REFERENCES roles(id)
        ON DELETE CASCADE,

    FOREIGN KEY(permission_id)
        REFERENCES permissions(id)
        ON DELETE CASCADE
);
```

---

# 12. USER ROLE

```sql id="b8r7dd"
CREATE TABLE user_roles (
    user_id UUID NOT NULL,
    role_id UUID NOT NULL,
    school_id UUID,

    PRIMARY KEY(user_id, role_id, school_id),

    FOREIGN KEY(user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY(role_id)
        REFERENCES roles(id)
        ON DELETE CASCADE,

    FOREIGN KEY(school_id)
        REFERENCES schools(id)
);
```

Untuk role platform:

```text
school_id = NULL
```

Untuk role sekolah:

```text
school_id = school tertentu
```

Implementasikan validasi agar kombinasi tersebut tidak menghasilkan ambiguity.

---

# 13. ACADEMIC YEAR

```sql id="8f8nbi"
CREATE TABLE academic_years (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    name VARCHAR(30) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    CHECK(start_date < end_date)
);
```

Partial unique index:

```sql id="h1v8wt"
CREATE UNIQUE INDEX uq_active_academic_year
ON academic_years(school_id)
WHERE is_active = TRUE;
```

---

# 14. SEMESTERS

```sql id="cl3m7j"
CREATE TABLE semesters (
    id UUID PRIMARY KEY,
    academic_year_id UUID NOT NULL,
    name VARCHAR(30) NOT NULL,
    type VARCHAR(20) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(academic_year_id)
        REFERENCES academic_years(id),

    CHECK(start_date < end_date)
);
```

---

# 15. TEACHERS

```sql id="3p2xpk"
CREATE TABLE teachers (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    user_id UUID,
    employee_number VARCHAR(100),
    nip VARCHAR(100),
    full_name VARCHAR(200) NOT NULL,
    gender VARCHAR(20),
    birth_date DATE,
    phone VARCHAR(30),
    email VARCHAR(150),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    FOREIGN KEY(user_id)
        REFERENCES users(id)
);
```

---

# 16. PARENTS

```sql id="zq0yzn"
CREATE TABLE parents (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    user_id UUID,
    full_name VARCHAR(200) NOT NULL,
    relationship VARCHAR(30),
    nik VARCHAR(50),
    phone VARCHAR(30),
    email VARCHAR(150),
    address TEXT,
    occupation VARCHAR(100),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    FOREIGN KEY(user_id)
        REFERENCES users(id)
);
```

---

# 17. STUDENTS

```sql id="0h44t3"
CREATE TABLE students (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    student_number VARCHAR(100) NOT NULL,
    nis VARCHAR(100),
    nisn VARCHAR(100),
    full_name VARCHAR(200) NOT NULL,
    gender VARCHAR(20),
    birth_place VARCHAR(100),
    birth_date DATE,
    religion VARCHAR(50),
    nik VARCHAR(50),
    address TEXT,
    phone VARCHAR(30),
    email VARCHAR(150),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    photo_file_id UUID,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    deleted_at TIMESTAMP NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id)
);
```

Index:

```sql id="z2t3uw"
CREATE INDEX idx_students_school
ON students(school_id);

CREATE INDEX idx_students_school_name
ON students(school_id, full_name);

CREATE INDEX idx_students_nisn
ON students(nisn);
```

---

# 18. PARENT-STUDENT

```sql id="9d7f08"
CREATE TABLE parent_students (
    parent_id UUID NOT NULL,
    student_id UUID NOT NULL,
    relationship VARCHAR(30),
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,

    PRIMARY KEY(parent_id, student_id),

    FOREIGN KEY(parent_id)
        REFERENCES parents(id)
        ON DELETE CASCADE,

    FOREIGN KEY(student_id)
        REFERENCES students(id)
        ON DELETE CASCADE
);
```

---

# 19. ROOMS

```sql id="75f3fc"
CREATE TABLE rooms (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    name VARCHAR(100) NOT NULL,
    building VARCHAR(100),
    floor VARCHAR(30),
    capacity INTEGER,
    room_type VARCHAR(50),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    CHECK(capacity IS NULL OR capacity > 0)
);
```

---

# 20. CLASSES

```sql id="4hh7yz"
CREATE TABLE classes (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    academic_year_id UUID NOT NULL,
    name VARCHAR(100) NOT NULL,
    grade VARCHAR(30),
    homeroom_teacher_id UUID,
    room_id UUID,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    FOREIGN KEY(academic_year_id)
        REFERENCES academic_years(id),

    FOREIGN KEY(homeroom_teacher_id)
        REFERENCES teachers(id),

    FOREIGN KEY(room_id)
        REFERENCES rooms(id)
);
```

---

# 21. ENROLLMENT

```sql id="d6j8qm"
CREATE TABLE student_enrollments (
    id UUID PRIMARY KEY,
    student_id UUID NOT NULL,
    class_id UUID NOT NULL,
    academic_year_id UUID NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(student_id)
        REFERENCES students(id),

    FOREIGN KEY(class_id)
        REFERENCES classes(id),

    FOREIGN KEY(academic_year_id)
        REFERENCES academic_years(id),

    CHECK(end_date IS NULL OR start_date <= end_date)
);
```

Active enrollment:

```sql id="4crp48"
CREATE UNIQUE INDEX uq_active_student_enrollment
ON student_enrollments(student_id, academic_year_id)
WHERE status = 'ACTIVE';
```

---

# 22. SUBJECTS

```sql id="2ax2kd"
CREATE TABLE subjects (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    code VARCHAR(50),
    name VARCHAR(150) NOT NULL,
    short_name VARCHAR(50),
    category VARCHAR(50),
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id)
);
```

---

# 23. TEACHER ASSIGNMENT

```sql id="p2j5i7"
CREATE TABLE teacher_subject_assignments (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    teacher_id UUID NOT NULL,
    subject_id UUID NOT NULL,
    class_id UUID NOT NULL,
    academic_year_id UUID NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    FOREIGN KEY(teacher_id)
        REFERENCES teachers(id),

    FOREIGN KEY(subject_id)
        REFERENCES subjects(id),

    FOREIGN KEY(class_id)
        REFERENCES classes(id),

    FOREIGN KEY(academic_year_id)
        REFERENCES academic_years(id)
);
```

---

# 24. SCHEDULE

```sql id="n2o1b3"
CREATE TABLE schedules (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    class_id UUID NOT NULL,
    subject_id UUID NOT NULL,
    teacher_id UUID NOT NULL,
    room_id UUID,
    day_of_week SMALLINT NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    academic_year_id UUID NOT NULL,
    semester_id UUID NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    CHECK(day_of_week BETWEEN 1 AND 7),
    CHECK(start_time < end_time)
);
```

---

# 25. DAILY ATTENDANCE

```sql id="9h9k4u"
CREATE TABLE daily_attendance (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    student_id UUID NOT NULL,
    class_id UUID NOT NULL,
    date DATE NOT NULL,
    status VARCHAR(20) NOT NULL,
    check_in_at TIMESTAMP,
    check_out_at TIMESTAMP,
    source VARCHAR(30),
    device_id UUID,
    notes TEXT,
    created_by UUID NOT NULL,
    updated_by UUID,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(school_id)
        REFERENCES schools(id),

    FOREIGN KEY(student_id)
        REFERENCES students(id),

    FOREIGN KEY(class_id)
        REFERENCES classes(id),

    FOREIGN KEY(created_by)
        REFERENCES users(id),

    FOREIGN KEY(updated_by)
        REFERENCES users(id)
);
```

Unique:

```sql id="1wq2d8"
CREATE UNIQUE INDEX uq_daily_attendance
ON daily_attendance(school_id, student_id, date);
```

---

# 26. LESSON SESSION

```sql id="eq5i3w"
CREATE TABLE lesson_sessions (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    schedule_id UUID NOT NULL,
    teacher_id UUID NOT NULL,
    class_id UUID NOT NULL,
    subject_id UUID NOT NULL,
    date DATE NOT NULL,
    started_at TIMESTAMP,
    ended_at TIMESTAMP,
    status VARCHAR(30) NOT NULL DEFAULT 'PLANNED',
    notes TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

---

# 27. LESSON ATTENDANCE

```sql id="y1u1v7"
CREATE TABLE lesson_attendance (
    id UUID PRIMARY KEY,
    lesson_session_id UUID NOT NULL,
    student_id UUID NOT NULL,
    status VARCHAR(20) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,

    FOREIGN KEY(lesson_session_id)
        REFERENCES lesson_sessions(id)
        ON DELETE CASCADE,

    FOREIGN KEY(student_id)
        REFERENCES students(id)
);
```

Unique:

```sql id="i1uwtc"
CREATE UNIQUE INDEX uq_lesson_student
ON lesson_attendance(lesson_session_id, student_id);
```

---

# 28. VIOLATIONS

```sql id="a5p7av"
CREATE TABLE violation_types (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    severity VARCHAR(30),
    points INTEGER NOT NULL DEFAULT 0,
    description TEXT,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

```sql id="pfzq0a"
CREATE TABLE student_violations (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    student_id UUID NOT NULL,
    violation_type_id UUID NOT NULL,
    occurred_at TIMESTAMP NOT NULL,
    location VARCHAR(150),
    description TEXT NOT NULL,
    action_taken TEXT,
    reported_by UUID NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'OPEN',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

---

# 29. COUNSELING

```sql id="8awz6a"
CREATE TABLE counseling_sessions (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    student_id UUID NOT NULL,
    counselor_id UUID NOT NULL,
    session_date TIMESTAMP NOT NULL,
    category VARCHAR(100),
    summary TEXT NOT NULL,
    action_plan TEXT,
    follow_up_date DATE,
    status VARCHAR(30) NOT NULL DEFAULT 'OPEN',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

---

# 30. ANNOUNCEMENTS

```sql id="v8j8w4"
CREATE TABLE announcements (
    id UUID PRIMARY KEY,
    school_id UUID NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    type VARCHAR(50),
    priority VARCHAR(20) NOT NULL DEFAULT 'NORMAL',
    published_at TIMESTAMP,
    expires_at TIMESTAMP,
    created_by UUID NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'DRAFT',
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

---

# 31. NOTIFICATIONS

```sql id="6q3z4y"
CREATE TABLE notifications (
    id UUID PRIMARY KEY,
    user_id UUID NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    type VARCHAR(50),
    data JSONB,
    read_at TIMESTAMP,
    created_at TIMESTAMP NOT NULL
);
```

---

# 32. AUDIT LOG

```sql id="1z2t9b"
CREATE TABLE audit_logs (
    id UUID PRIMARY KEY,
    school_id UUID,
    user_id UUID,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100),
    entity_id UUID,
    old_values JSONB,
    new_values JSONB,
    ip_address INET,
    user_agent TEXT,
    created_at TIMESTAMP NOT NULL
);
```

---

# 33. CORE API CONTRACT

Semua API:

```text id="1mzz4f"
/api/v1/*
```

Response:

```json id="fppg3s"
{
  "success": true,
  "data": {},
  "meta": {}
}
```

Error:

```json id="j6j3eq"
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Data tidak valid",
    "details": {}
  }
}
```

---

# 34. CREATE STUDENT

### Endpoint

```text id="5o3e5r"
POST /api/v1/students
```

### Permission

```text id="i6m7x7"
student.create
```

### Request

```json id="w0zq1x"
{
  "student_number": "20260001",
  "nisn": "0012345678",
  "full_name": "Ahmad Fauzan",
  "gender": "MALE",
  "birth_date": "2012-05-10",
  "class_id": "uuid"
}
```

### Validation

```text id="4y83o8"
student_number required
full_name required
gender valid enum
birth_date valid date
class_id exists
class belongs to current school
```

---

# 35. CREATE STUDENT RESPONSE

```json id="cnp0cs"
{
  "success": true,
  "data": {
    "id": "uuid",
    "student_number": "20260001",
    "full_name": "Ahmad Fauzan",
    "status": "ACTIVE",
    "enrollment": {
      "class_id": "uuid",
      "class_name": "VII-A"
    }
  }
}
```

---

# 36. GET STUDENTS

```text id="7s2mvs"
GET /api/v1/students
```

Parameters:

```text id="0tvq6v"
page
per_page
search
status
class_id
gender
academic_year_id
```

Contoh:

```text id="s4m3qg"
/students?page=1&per_page=20&search=Ahmad&status=ACTIVE
```

---

# 37. ATTENDANCE CONTRACT

```text id="0q1n2d"
POST /api/v1/attendance/bulk
```

Request:

```json id="w5ljbo"
{
  "class_id": "uuid",
  "date": "2026-09-21",
  "records": [
    {
      "student_id": "uuid",
      "status": "PRESENT"
    },
    {
      "student_id": "uuid",
      "status": "LATE"
    }
  ]
}
```

Server harus memastikan seluruh student:

```text id="1xq2fa"
belongs to school
+
belongs to class
+
has valid enrollment
```

---

# 38. ATTENDANCE CORRECTION

```text id="0myyyo"
POST /api/v1/attendance/corrections
```

Request:

```json id="6p0f8h"
{
  "attendance_id": "uuid",
  "requested_status": "PRESENT",
  "reason": "Kesalahan input absensi"
}
```

Status:

```text id="ivqrx7"
PENDING
APPROVED
REJECTED
```

Tidak boleh langsung mengubah record jika membutuhkan approval.

---

# 39. ANNOUNCEMENT CONTRACT

```text id="z6o2qa"
POST /api/v1/announcements
```

```json id="n8j5az"
{
  "title": "Rapat Orang Tua",
  "content": "Rapat akan dilaksanakan...",
  "priority": "IMPORTANT",
  "targets": [
    {
      "type": "CLASS",
      "id": "uuid"
    }
  ]
}
```

Publish:

```text id="c3e1ni"
POST /api/v1/announcements/{id}/publish
```

---

# 40. SCREEN SPECIFICATION

## SCREEN 01 — LOGIN

Route:

```text id="7x2m6e"
/login
```

Role:

```text id="q6v2nd"
All
```

Components:

```text id="b8i3gu"
Logo
Email/Phone
Password
Remember me
Login button
Forgot password
```

States:

```text id="b2k0e6"
Default
Loading
Invalid credential
Locked/rate-limited
Network error
```

---

# 41. SCREEN 02 — DASHBOARD

```text id="s3s8cv"
/dashboard
```

Components:

```text id="0c4tka"
Header
KPI cards
Attendance chart
Today's schedule
Announcements
Recent activities
Quick actions
```

Dashboard harus berubah berdasarkan role.

---

# 42. SCREEN 03 — STUDENTS

```text id="4y3m9q"
/students
```

Components:

```text id="7v4y7s"
Search
Filter
Table
Pagination
Import
Export
Add Student
Bulk action
```

Mobile:

```text id="4d5g1x"
Card list
```

---

# 43. SCREEN 04 — STUDENT DETAIL

```text id="r5f1gi"
/students/{id}
```

Tabs:

```text id="kq4e0f"
Profile
Parents
Enrollment
Attendance
Violations
Counseling
Documents
Activity
```

---

# 44. SCREEN 05 — TEACHERS

```text id="8m2g5f"
/teachers
```

Filter:

```text id="l0f9as"
Status
Subject
Class
```

Actions:

```text id="g4v4r5"
Add
Edit
View
Deactivate
Export
```

---

# 45. SCREEN 06 — CLASSES

```text id="6l7d9n"
/classes
```

Card:

```text id="4m3y89"
VII-A
32 siswa
Wali: Budi Santoso
```

Klik:

```text id="cxk8j5"
Class Detail
```

---

# 46. SCREEN 07 — SCHEDULE

```text id="v4z7yk"
/schedules
```

View:

```text id="gk0y6j"
Weekly
Daily
Teacher
Class
Room
```

Conflict:

```text id="j4c1xn"
⚠ Jadwal bentrok
Guru Budi telah memiliki jadwal
07:00–08:30 pada kelas VIII-A.
```

---

# 47. SCREEN 08 — ATTENDANCE

```text id="r7m1bs"
/attendance
```

Header:

```text id="3w8x8g"
Tanggal
Kelas
Mata Pelajaran
```

Student list:

```text id="8g0z4w"
Ahmad       PRESENT
Budi        PRESENT
Citra       SICK
Deni        PERMITTED
```

Actions:

```text id="z2j8hj"
Semua Hadir
Reset
Simpan
```

---

# 48. SCREEN 09 — REPORTS

```text id="4q0e7v"
/reports
```

Cards:

```text id="3v1k1u"
Laporan Siswa
Laporan Guru
Laporan Absensi
Laporan Pelanggaran
```

Saat generate:

```text id="sl9f3x"
Generating...
```

Kemudian:

```text id="1h9z6f"
Ready
[Download]
```

---

# 49. SCREEN 10 — ANNOUNCEMENT

```text id="9c5y8v"
/announcements
```

List:

```text id="u3m4e0"
Title
Author
Target
Priority
Published
Status
```

---

# 50. SCREEN 11 — SETTINGS

```text id="d4x8wq"
/settings
```

Menu:

```text id="u5e4d6"
School Profile
Academic
Attendance
Notifications
Users
Roles
Integrations
Security
Billing
```

---

# 51. PARENT MOBILE

## Home

```text id="6v1h0m"
Halo, Ibu/Ayah

[ Ahmad ▼ ]

Kehadiran
94%

Hari Ini
────────────────
Matematika
07:00

Bahasa Indonesia
09:00

Pengumuman
────────────────
...
```

Bottom navigation:

```text id="g8r7m2"
Home
Jadwal
Absensi
Notifikasi
Profil
```

---

# 52. TEACHER MOBILE

```text id="d2e8u3"
Home
Kelas
Jadwal
Absensi
Profil
```

Quick action:

```text id="q0c6v2"
Mulai Pembelajaran
```

Teacher tidak perlu masuk banyak menu untuk melakukan absensi.

---

# 53. AUTHORIZATION TEST

Test case:

```text id="8o4w7q"
TC-AUTH-001
```

Given:

```text
Teacher School A
```

When:

```text
GET /students/student-school-B
```

Then:

```text
403/404
```

Expected:

```text id="b1z6ty"
No School B data returned.
```

---

# 54. STUDENT TEST

```text id="i7k1k5"
TC-STUDENT-001
```

Given:

```text
Admin School A
```

When:

```text
POST /students
```

Then:

```text
Student created
Enrollment created
Audit created
```

---

# 55. DUPLICATE ATTENDANCE TEST

```text id="0p0w0r"
TC-ATT-001
```

Submit attendance dua kali:

```text
same school
same student
same date
```

Expected:

```text id="4k3l6h"
one daily attendance record
```

Tidak boleh:

```text
two attendance records
```

---

# 56. ATTENDANCE CORRECTION TEST

```text id="k7t1j3"
TC-ATT-002
```

Teacher:

```text
request correction
```

Expected:

```text id="w6d4j9"
status = PENDING
```

Teacher tidak boleh langsung mengubah status final jika workflow approval diperlukan.

---

# 57. SCHEDULE CONFLICT TEST

Create:

```text id="e6m1w7"
Teacher Budi
07:00–08:30
VII-A
```

Create second:

```text id="f2z8j1"
Teacher Budi
07:30–09:00
VIII-A
```

Expected:

```text id="3d7n4k"
CONFLICT
```

---

# 58. IMPORT TEST

Input:

```text id="z0p5f8"
1.000 rows
```

Expected:

```text id="q7l3x4"
valid rows → imported
invalid rows → error report
```

Tidak boleh seluruh import gagal hanya karena satu row invalid, kecuali policy import memang memilih all-or-nothing.

---

# 59. REPORT TEST

Generate:

```text id="x6v9p2"
Attendance
1 year
10.000 students
```

Expected:

```text id="e7c3n1"
HTTP request tidak timeout
```

Job:

```text id="f2g5k8"
QUEUED
→ PROCESSING
→ COMPLETED
```

---

# 60. SECURITY TEST MATRIX

| Area           | Test                     |
| -------------- | ------------------------ |
| Authentication | invalid credential       |
| Authorization  | unauthorized endpoint    |
| Tenant         | cross-school access      |
| Input          | malformed payload        |
| SQL            | injection payload        |
| File           | unauthorized file        |
| Rate Limit     | repeated login           |
| Webhook        | invalid signature        |
| Replay         | duplicate webhook        |
| Session        | expired token            |
| Export         | unauthorized data export |

---

# 61. E2E USER JOURNEY

## School Admin

```text id="6j5q5a"
Login
 ↓
Dashboard
 ↓
Create academic year
 ↓
Create class
 ↓
Create subject
 ↓
Create teacher
 ↓
Import students
 ↓
Assign students
 ↓
Create schedule
 ↓
Review attendance
 ↓
Generate report
```

## Teacher

```text id="8u0p4e"
Login
 ↓
View today's schedule
 ↓
Start lesson
 ↓
Take attendance
 ↓
Save
 ↓
Finish lesson
```

## Parent

```text id="k1m7s0"
Login
 ↓
Select child
 ↓
View attendance
 ↓
View schedule
 ↓
Receive announcement
```

---

# 62. ACCEPTANCE CRITERIA MVP

MVP dapat dianggap siap pilot apabila:

```text id="g0x1r8"
✓ Login stabil
✓ School isolation tested
✓ RBAC tested
✓ Student CRUD
✓ Teacher CRUD
✓ Parent relation
✓ Class management
✓ Enrollment
✓ Subject
✓ Schedule
✓ Attendance
✓ Attendance correction
✓ Announcement
✓ Notification
✓ Basic report
✓ Audit log
✓ Backup
✓ Error monitoring
```

---

# 63. PRODUCTION GATE

Sebelum production:

```text id="q4g1f2"
□ Automated tests pass
□ Migration tested
□ Backup verified
□ Restore tested
□ Tenant isolation tested
□ Rate limit tested
□ File access tested
□ Payment webhook tested
□ Monitoring active
□ Error tracking active
□ HTTPS active
□ Secrets configured
□ Production seed reviewed
□ Admin 2FA policy reviewed
□ Disaster recovery procedure documented
```

---

# 64. DEVELOPER EXECUTION ORDER

Developer mulai dengan:

```text id="j5f3w8"
PHASE A
Infrastructure

PHASE B
Database

PHASE C
Authentication

PHASE D
RBAC + Tenant

PHASE E
School + Academic

PHASE F
Student + Teacher + Parent

PHASE G
Schedule

PHASE H
Attendance

PHASE I
Communication

PHASE J
Reports

PHASE K
Mobile

PHASE L
Student Affairs

PHASE M
Billing

PHASE N
Production Hardening
```

---

# 65. JANGAN MEMBANGUN SEMUA SEKALIGUS

Prioritas implementasi pertama:

```text id="7s4f9n"
AUTH
↓
SCHOOL
↓
RBAC
↓
STUDENT
↓
TEACHER
↓
CLASS
↓
SCHEDULE
↓
ATTENDANCE
```

Setelah alur tersebut stabil:

```text id="m8n3x2"
PARENT
↓
NOTIFICATION
↓
REPORT
```

Baru kemudian:

```text id="w2h7q0"
VIOLATION
COUNSELING
BILLING
INTEGRATIONS
```

---

# 66. HASIL PHASE 5

Sekarang blueprint sudah mempunyai:

```text id="1e7z5p"
DATABASE
    ✓ Table map
    ✓ SQL foundation
    ✓ Foreign key
    ✓ Index
    ✓ Constraint

BACKEND
    ✓ Module
    ✓ Migration order
    ✓ API
    ✓ Authorization
    ✓ Events
    ✓ Queue

FRONTEND
    ✓ Routes
    ✓ Screens
    ✓ Components
    ✓ States

MOBILE
    ✓ Parent flow
    ✓ Teacher flow

QA
    ✓ Unit
    ✓ Feature
    ✓ Integration
    ✓ E2E
    ✓ Security

DEVOPS
    ✓ CI/CD
    ✓ Environment
    ✓ Backup
    ✓ Monitoring
```

---

# 67. PHASE 6 — REAL BUILD

Tahap selanjutnya sudah masuk ke level **kode nyata**.

Urutan paling efektif:

### STEP 1

Generate:

```text
Laravel project
PostgreSQL
Redis
Docker
```

### STEP 2

Implement:

```text
organizations
schools
users
memberships
roles
permissions
```

### STEP 3

Implement:

```text
Student
Teacher
Parent
Class
Enrollment
```

### STEP 4

Implement:

```text
Subject
Room
Schedule
```

### STEP 5

Implement:

```text
Attendance
Correction
Approval
Audit
```

### STEP 6

Build:

```text
Next.js Admin Dashboard
```

### STEP 7

Build:

```text
Flutter Parent
Flutter Teacher
```

### STEP 8

Integrate:

```text
Notification
WhatsApp
Email
Push
```

### STEP 9

Add:

```text
Reports
Import
Export
```

### STEP 10

Production hardening:

```text
Security
Performance
Backup
Monitoring
Load testing
```

**Pada Phase 6, kita tidak lagi berhenti di blueprint. Kita mulai menghasilkan struktur project, migration, model, policy, service/action, controller, API resource, seeder, dan komponen frontend secara berurutan.**

# PHASE 6 — REAL BUILD / FOUNDATION

## 1. Target Arsitektur Final

Platform dibangun sebagai **Multi-Tenant School Management SaaS** dengan:

* Backend: **Laravel**
* Database: **PostgreSQL**
* Cache & Queue: **Redis**
* Web Admin: **Next.js + TypeScript**
* Mobile: **Flutter**
* Object Storage: **S3-compatible / MinIO**
* Reverse Proxy: **Nginx**
* Container: **Docker**
* API: **REST API `/api/v1`**
* Authentication: **Laravel Sanctum/token-based API**
* Documentation: **OpenAPI**
* CI/CD: GitHub Actions
* Monitoring: application logs + error tracking + uptime monitoring

Arsitektur:

```text
                         INTERNET
                            │
                       Cloudflare
                            │
                    ┌───────┴───────┐
                    │               │
                 Next.js          Flutter
                 Web App         Mobile App
                    │               │
                    └───────┬───────┘
                            │
                         REST API
                            │
                     Laravel Backend
                            │
        ┌─────────────┬─────┼──────┬──────────────┐
        │             │     │      │              │
   PostgreSQL      Redis  Queue   Storage      Mail/WA
        │             │
        └─────────────┴──────────────
```

---

# 2. Repository Structure

Gunakan satu repository utama terlebih dahulu.

```text
school-saas/
│
├── backend/
│   ├── app/
│   │   ├── Actions/
│   │   ├── Console/
│   │   ├── Enums/
│   │   ├── Exceptions/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   ├── Middleware/
│   │   │   ├── Requests/
│   │   │   └── Resources/
│   │   ├── Jobs/
│   │   ├── Listeners/
│   │   ├── Models/
│   │   ├── Notifications/
│   │   ├── Policies/
│   │   ├── Services/
│   │   └── Support/
│   │
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   │   ├── factories/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   │   ├── api.php
│   │   └── console.php
│   ├── storage/
│   ├── tests/
│   │   ├── Feature/
│   │   ├── Integration/
│   │   └── Unit/
│   └── composer.json
│
├── web/
│   ├── app/
│   │   ├── (auth)/
│   │   ├── (dashboard)/
│   │   ├── admin/
│   │   ├── students/
│   │   ├── teachers/
│   │   ├── parents/
│   │   ├── classes/
│   │   ├── schedules/
│   │   ├── attendance/
│   │   ├── reports/
│   │   ├── announcements/
│   │   └── settings/
│   ├── components/
│   ├── hooks/
│   ├── lib/
│   ├── services/
│   ├── stores/
│   ├── types/
│   └── package.json
│
├── mobile/
│   ├── lib/
│   │   ├── core/
│   │   ├── features/
│   │   │   ├── auth/
│   │   │   ├── home/
│   │   │   ├── attendance/
│   │   │   ├── schedules/
│   │   │   ├── students/
│   │   │   ├── notifications/
│   │   │   └── profile/
│   │   ├── routing/
│   │   ├── services/
│   │   └── main.dart
│   └── pubspec.yaml
│
├── infra/
│   ├── nginx/
│   ├── docker/
│   ├── postgres/
│   └── scripts/
│
├── docs/
│   ├── architecture/
│   ├── api/
│   ├── database/
│   ├── security/
│   └── product/
│
├── .github/
│   └── workflows/
│
├── docker-compose.yml
├── .env.example
└── README.md
```

Struktur ini sengaja memisahkan:

* backend
* web
* mobile
* infrastructure
* documentation

agar perubahan frontend tidak mengganggu backend.

---

# 3. Environment

File:

```text
.env.example
```

Minimal:

```env
APP_NAME="School SaaS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=school_saas
DB_USERNAME=school
DB_PASSWORD=change_me

REDIS_HOST=redis
REDIS_PORT=6379
REDIS_PASSWORD=null

CACHE_STORE=redis
QUEUE_CONNECTION=redis

FILESYSTEM_DISK=s3

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_ENDPOINT=

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"

SANCTUM_STATEFUL_DOMAINS=localhost:3000

FRONTEND_URL=http://localhost:3000

WHATSAPP_ENABLED=false
WHATSAPP_API_URL=
WHATSAPP_API_TOKEN=

FCM_ENABLED=false
FCM_PROJECT_ID=
FCM_CREDENTIALS=

TURNSTILE_ENABLED=false
TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=
```

**Jangan pernah commit `.env` asli.**

Yang masuk Git hanya:

```text
.env.example
```

---

# 4. Docker Development Environment

Service minimum:

```text
app
postgres
redis
nginx
minio
mailpit
```

Fungsi:

| Service  | Fungsi               |
| -------- | -------------------- |
| app      | Laravel API          |
| postgres | Database             |
| redis    | Cache + Queue        |
| nginx    | Reverse proxy        |
| minio    | Object storage lokal |
| mailpit  | Testing email        |

Konsep:

```text
Docker Compose
│
├── Laravel
│
├── PostgreSQL
│
├── Redis
│
├── Nginx
│
├── MinIO
│
└── Mailpit
```

Development tidak bergantung pada layanan cloud berbayar.

Ketika production, PostgreSQL/Redis/object storage dapat dipindahkan ke layanan managed.

---

# 5. Database Migration Order

Migration **tidak boleh dibuat acak**.

Urutan:

```text
001 extensions

010 organizations
020 schools
030 school_settings

040 users
050 school_memberships

060 roles
070 permissions
080 role_permissions
090 user_roles

100 academic_years
110 semesters

120 teachers
130 parents
140 students
150 parent_students

160 rooms
170 classes
180 student_enrollments

190 subjects
200 teacher_subject_assignments

210 schedules

220 daily_attendance

230 lesson_sessions
240 lesson_attendance

250 violation_types
260 student_violations

270 counseling_sessions

280 announcements
290 announcement_targets

300 notifications
310 notification_deliveries

320 files
330 audit_logs

340 plans
350 subscriptions
360 invoices
370 payments

380 affiliates
390 affiliate_referrals
400 affiliate_commissions
401 affiliate_payouts

410 support_tickets
420 ticket_messages
```

Nomor hanya sebagai konvensi urutan; migration Laravel tetap menggunakan timestamp aktual.

---

# 6. Core Model

Model Laravel:

```text
Organization
School
SchoolSetting

User
SchoolMembership

Role
Permission
RolePermission
UserRole

AcademicYear
Semester

Teacher
Parent
Student
ParentStudent

Room
ClassRoom
StudentEnrollment

Subject
TeacherSubjectAssignment

Schedule

DailyAttendance

LessonSession
LessonAttendance

ViolationType
StudentViolation

CounselingSession

Announcement
AnnouncementTarget

Notification
NotificationDelivery

File
AuditLog

Plan
Subscription
Invoice
Payment

Affiliate
AffiliateReferral
AffiliateCommission
AffiliatePayout

SupportTicket
TicketMessage
```

Gunakan nama model yang tidak ambigu.

Contoh:

```text
ClassRoom
```

bukan:

```text
Class
```

karena `class` adalah keyword dalam beberapa konteks pemrograman dan berpotensi membingungkan.

---

# 7. UUID

Semua primary key menggunakan UUID.

Contoh:

```text
0199a9f1-....
```

Hindari ID berurutan seperti:

```text
1
2
3
4
```

Alasannya:

* lebih sulit ditebak
* aman untuk API publik
* lebih cocok untuk distributed system
* mempermudah integrasi
* mengurangi enumeration attack

---

# 8. Tenant Isolation

Ini adalah salah satu bagian **paling kritis**.

Jangan hanya mengandalkan:

```php
where('school_id', $schoolId)
```

di setiap controller.

Gunakan kombinasi:

```text
Authenticated User
        ↓
School Membership
        ↓
Current School Context
        ↓
Policy
        ↓
Service
        ↓
Repository/Query
```

Contoh:

```text
GET /api/v1/students
```

tidak boleh berarti:

```sql
SELECT * FROM students;
```

tetapi secara konseptual:

```sql
SELECT *
FROM students
WHERE school_id = :current_school_id;
```

Dan `current_school_id` harus berasal dari context tenant yang tervalidasi.

---

# 9. Current School Context

Buat:

```text
CurrentSchoolContext
```

Tanggung jawab:

```text
resolve()
getSchoolId()
getOrganizationId()
setSchool()
clear()
```

Flow:

```text
Login
 ↓
User
 ↓
School Memberships
 ↓
User memilih sekolah
 ↓
School Context
 ↓
Semua request berikutnya menggunakan context tersebut
```

Untuk user yang hanya memiliki satu sekolah:

```text
Login
 ↓
1 membership
 ↓
otomatis memilih sekolah
```

Untuk user multi-school:

```text
Login
 ↓
School Selector
 ↓
Pilih Sekolah
 ↓
Dashboard
```

---

# 10. Middleware

Minimal:

```text
Authenticate
EnsureSchoolContext
EnsureSchoolMembership
SetTenantContext
CheckPermission
LogRequest
```

Urutan:

```text
Request
 ↓
Authenticate
 ↓
SetTenantContext
 ↓
Membership Validation
 ↓
Permission
 ↓
Controller
```

---

# 11. RBAC

Permission menggunakan pola:

```text
module.action
```

Contoh:

```text
students.view
students.create
students.update
students.delete

teachers.view
teachers.create
teachers.update
teachers.delete

attendance.view
attendance.create
attendance.correct

classes.view
classes.create
classes.update

schedules.view
schedules.create
schedules.update

reports.view
reports.export

announcements.view
announcements.create
announcements.publish

settings.view
settings.update
```

Jangan membuat permission hanya:

```text
admin
teacher
parent
```

karena terlalu kasar.

---

# 12. Role Default

Seed awal:

```text
SUPER_ADMIN
PLATFORM_ADMIN
ORGANIZATION_ADMIN
SCHOOL_ADMIN
HEADMASTER
TEACHER
HOMEROOM_TEACHER
COUNSELOR
STAFF
STUDENT
PARENT
```

Contoh:

```text
SCHOOL_ADMIN
```

dapat:

```text
students.*
teachers.*
parents.*
classes.*
subjects.*
schedules.*
attendance.view
attendance.correct
reports.*
announcements.*
settings.*
```

Sedangkan:

```text
TEACHER
```

misalnya:

```text
students.view
classes.view
schedules.view
attendance.view
attendance.create
lesson_sessions.*
announcements.view
```

Hak akses final harus tetap mengikuti kebutuhan sekolah.

---

# 13. Auth Flow

Flow login:

```text
POST /api/v1/auth/login
        ↓
Validate credentials
        ↓
Check account status
        ↓
Check verification
        ↓
Create token/session
        ↓
Load memberships
        ↓
Return user + schools
```

Response:

```json
{
  "success": true,
  "data": {
    "user": {},
    "schools": [],
    "requires_school_selection": false
  }
}
```

Jika user memiliki beberapa sekolah:

```json
{
  "requires_school_selection": true
}
```

---

# 14. Authentication Endpoint

```text
POST /api/v1/auth/login
POST /api/v1/auth/logout
POST /api/v1/auth/refresh
GET  /api/v1/auth/me
POST /api/v1/auth/forgot-password
POST /api/v1/auth/reset-password
POST /api/v1/auth/verify-email
```

Tambahkan:

```text
POST /api/v1/auth/select-school
```

untuk multi-school user.

---

# 15. API Route Structure

```text
/api/v1

/auth
/schools
/users
/students
/teachers
/parents
/classes
/subjects
/rooms
/schedules
/attendance
/lessons
/violations
/counseling
/announcements
/notifications
/reports
/files
/settings
/billing
/support
```

Contoh:

```text
GET    /students
POST   /students
GET    /students/{student}
PUT    /students/{student}
DELETE /students/{student}
```

---

# 16. Student Creation

Request:

```json
{
  "nis": "20260001",
  "nisn": "0012345678",
  "name": "Nama Siswa",
  "gender": "MALE",
  "birth_place": "Kendari",
  "birth_date": "2012-05-10",
  "phone": null,
  "address": "Alamat"
}
```

Backend:

```text
Validate
 ↓
Tenant check
 ↓
Duplicate NIS check
 ↓
Create student
 ↓
Create parent relation if supplied
 ↓
Audit
 ↓
Return resource
```

---

# 17. Attendance Architecture

Jangan langsung menerima status kehadiran tanpa validasi.

Request:

```json
{
  "date": "2026-09-22",
  "class_id": "uuid",
  "records": [
    {
      "student_id": "uuid",
      "status": "PRESENT"
    },
    {
      "student_id": "uuid",
      "status": "LATE"
    }
  ]
}
```

Backend:

```text
Authenticate
 ↓
School context
 ↓
Teacher/class authorization
 ↓
Validate student enrollment
 ↓
Check duplicate
 ↓
Save attendance
 ↓
Audit
 ↓
Notification event
```

Status:

```text
PRESENT
LATE
ABSENT
SICK
EXCUSED
```

---

# 18. Attendance Correction

Koreksi tidak boleh:

```text
UPDATE attendance
```

secara diam-diam.

Flow:

```text
Guru mengajukan koreksi
        ↓
Alasan wajib
        ↓
Approval
        ↓
Data berubah
        ↓
Audit log
```

Audit:

```text
who
what
when
before
after
reason
IP
user_agent
```

Contoh:

```text
Sebelum : ABSENT
Sesudah : PRESENT
Alasan  : Siswa hadir tetapi tercatat tidak hadir
```

---

# 19. Audit Log

Semua aktivitas sensitif dicatat.

Contoh:

```text
LOGIN
LOGOUT

CREATE_STUDENT
UPDATE_STUDENT
DELETE_STUDENT

CREATE_ATTENDANCE
UPDATE_ATTENDANCE
CORRECT_ATTENDANCE

CREATE_USER
UPDATE_ROLE

PUBLISH_ANNOUNCEMENT

CHANGE_SCHOOL_SETTING
CHANGE_BILLING
```

Audit tidak boleh dapat dihapus oleh admin sekolah.

---

# 20. Next.js Web Structure

Gunakan App Router:

```text
web/app/

(auth)
├── login
├── forgot-password
└── reset-password

(dashboard)
├── dashboard
├── students
├── teachers
├── parents
├── classes
├── subjects
├── rooms
├── schedules
├── attendance
├── lessons
├── violations
├── counseling
├── announcements
├── notifications
├── reports
└── settings

admin
├── organizations
├── schools
├── users
├── subscriptions
├── payments
├── affiliates
└── support
```

---

# 21. Dashboard

Dashboard tidak boleh menjadi halaman yang hanya berisi angka.

School Admin:

```text
┌─────────────────────────────────────┐
│ Selamat datang                      │
│ SMA/SMK/SD ...                      │
├─────────────────────────────────────┤
│ Siswa │ Guru │ Kelas │ Kehadiran    │
├─────────────────────────────────────┤
│ Grafik Kehadiran                    │
├─────────────────────────────────────┤
│ Aktivitas Terbaru                   │
├─────────────────────────────────────┤
│ Pengumuman                          │
└─────────────────────────────────────┘
```

Quick actions:

```text
+ Tambah Siswa
+ Tambah Guru
+ Buat Pengumuman
+ Input Kehadiran
+ Jadwal Hari Ini
```

---

# 22. Student List

Tampilan:

```text
Siswa
─────────────────────────────────────

[ Cari siswa... ] [Filter] [+ Tambah]

Nama       NIS       Kelas      Status
─────────────────────────────────────
Ahmad      001       VII-A      Aktif
Budi       002       VII-A      Aktif
Citra      003       VII-B      Aktif
```

Filter:

```text
Kelas
Status
Jenis Kelamin
Tahun Ajaran
```

Bulk action:

```text
Export
Import
Print
```

---

# 23. Student Detail

Tab:

```text
Profil
Orang Tua
Kelas
Kehadiran
Pelanggaran
Konseling
Dokumen
Riwayat
```

Tujuannya supaya seluruh informasi siswa berada dalam satu pusat data.

---

# 24. Mobile Parent

Bottom navigation:

```text
Beranda
Jadwal
Kehadiran
Notifikasi
Profil
```

Home:

```text
Halo, Ayah/Bunda

Anak Saya
────────────────
Ahmad
VII-A

Kehadiran Bulan Ini
92%

Jadwal Hari Ini
08:00 Matematika
09:30 Bahasa Indonesia

Pengumuman
...
```

Jika memiliki beberapa anak:

```text
Anak Saya

[ Ahmad ]
[ Aisyah ]
[ Budi ]
```

---

# 25. Mobile Teacher

Bottom navigation:

```text
Beranda
Kelas
Jadwal
Kehadiran
Profil
```

Quick action:

```text
[ Input Kehadiran ]
[ Kelas Saya ]
[ Jadwal Hari Ini ]
```

Input attendance harus dirancang agar guru dapat menyelesaikannya dalam beberapa detik.

---

# 26. Notification Engine

Gunakan event-driven architecture.

Contoh:

```text
AttendanceCreated
        ↓
Notification Listener
        ↓
Parent Notification
        ↓
Push / WhatsApp / Email / In-App
```

Channel:

```text
IN_APP
PUSH
EMAIL
WHATSAPP
SMS
```

Tidak semua sekolah wajib mengaktifkan semua channel.

---

# 27. Queue

Pekerjaan berat tidak dijalankan langsung dalam HTTP request.

Contoh:

```text
GenerateReportJob
SendNotificationJob
SendWhatsAppJob
SendEmailJob
ProcessImportJob
GenerateExportJob
ResizeImageJob
```

Flow:

```text
Request
 ↓
Create Job
 ↓
Redis Queue
 ↓
Worker
 ↓
Process
 ↓
Success / Failed
```

---

# 28. File Storage

Dokumen:

```text
Student Photos
Teacher Photos
Student Documents
Violation Evidence
Counseling Documents
School Documents
```

Jangan menyimpan file sensitif langsung sebagai public URL.

Gunakan:

```text
Private Storage
      ↓
Authorization
      ↓
Temporary Signed URL
```

---

# 29. Import Siswa

Jangan langsung:

```text
Upload Excel → INSERT
```

Gunakan:

```text
Upload
 ↓
Detect columns
 ↓
Mapping
 ↓
Validation
 ↓
Preview
 ↓
User confirms
 ↓
Queue
 ↓
Import
 ↓
Summary
```

Summary:

```text
Total rows       : 500
Imported         : 487
Failed           : 13
Duplicate        : 7
Invalid NIS      : 6
```

User dapat mengunduh daftar error.

---

# 30. Reporting

Report kecil:

```text
langsung
```

Report besar:

```text
Generate
 ↓
Queue
 ↓
Processing
 ↓
File ready
 ↓
Download
```

Contoh:

```text
Laporan Kehadiran Bulanan
Laporan Siswa
Laporan Guru
Laporan Pelanggaran
Laporan Kelas
Laporan Statistik Sekolah
```

---

# 31. Flutter Architecture

Gunakan feature-based architecture:

```text
lib/
├── core/
│   ├── network/
│   ├── storage/
│   ├── theme/
│   ├── router/
│   └── utils/
│
├── features/
│   ├── auth/
│   ├── home/
│   ├── attendance/
│   ├── schedules/
│   ├── students/
│   ├── notifications/
│   └── profile/
│
└── main.dart
```

Setiap feature:

```text
data/
domain/
presentation/
```

Contoh:

```text
attendance/
├── data/
├── domain/
└── presentation/
```

---

# 32. CI/CD

Pipeline:

```text
Push
 ↓
Lint
 ↓
Unit Test
 ↓
Feature Test
 ↓
Build
 ↓
Security Check
 ↓
Docker Build
 ↓
Deploy Staging
 ↓
Smoke Test
 ↓
Production
```

Branch:

```text
main
develop
feature/*
fix/*
hotfix/*
```

Production tidak boleh langsung menerima eksperimen.

---

# 33. Testing Minimum

Backend:

```text
Unit Test
Feature Test
Integration Test
Authorization Test
Tenant Isolation Test
```

Frontend:

```text
Component Test
E2E Test
```

Mobile:

```text
Unit Test
Widget Test
Integration Test
```

Critical E2E:

```text
Login
Create school
Create student
Enroll student
Create schedule
Input attendance
Correct attendance
Send notification
Generate report
```

---

# 34. Security Test — WAJIB

Sebelum production:

```text
Tenant A → tidak dapat melihat Tenant B
Teacher → tidak dapat mengubah billing
Parent → tidak dapat membuka siswa lain
Student → tidak dapat membuka admin endpoint
User biasa → tidak dapat mengubah role sendiri
URL file → tidak dapat diakses tanpa authorization
Attendance → tidak dapat dibuat untuk siswa di luar sekolah
API → rate limited
Webhook → signature validated
```

---

# 35. Production Infrastructure

Production:

```text
Cloudflare
     │
     ▼
Load Balancer / Nginx
     │
     ▼
Laravel App
     │
 ┌───┴─────────────┐
 ▼                 ▼
PostgreSQL       Redis
 │                 │
 ▼                 ▼
Backup           Queue Worker
```

Storage:

```text
S3-compatible Object Storage
```

Monitoring:

```text
Application Errors
Server Metrics
Database Metrics
Queue Failure
HTTP Errors
Uptime
Disk Usage
Backup Status
```

---

# 36. Backup Strategy

Minimal:

```text
Database backup:
daily

Database retention:
30 days

Object storage:
versioning

Critical production:
off-site backup
```

Yang paling penting bukan hanya:

```text
backup berhasil
```

tetapi:

```text
backup berhasil
+
restore pernah diuji
```

Lakukan restore drill secara berkala.

---

# 37. Development Sprint

## Sprint 1 — Infrastructure

```text
[ ] Repository
[ ] Docker
[ ] PostgreSQL
[ ] Redis
[ ] Laravel
[ ] Next.js
[ ] Flutter
[ ] CI
```

## Sprint 2 — Identity

```text
[ ] Organizations
[ ] Schools
[ ] Users
[ ] Membership
[ ] Roles
[ ] Permissions
[ ] Authentication
[ ] Tenant Context
```

## Sprint 3 — Core School

```text
[ ] Academic Year
[ ] Semester
[ ] Student
[ ] Parent
[ ] Teacher
[ ] Class
[ ] Enrollment
```

## Sprint 4 — Academic

```text
[ ] Subject
[ ] Room
[ ] Schedule
[ ] Teacher Assignment
```

## Sprint 5 — Attendance

```text
[ ] Daily Attendance
[ ] Lesson Attendance
[ ] Correction
[ ] Approval
[ ] Audit
```

## Sprint 6 — Communication

```text
[ ] Announcement
[ ] Notification
[ ] Push
[ ] Email
[ ] WhatsApp
```

## Sprint 7 — Reports

```text
[ ] Reports
[ ] Export
[ ] Import
[ ] Dashboard statistics
```

## Sprint 8 — Mobile

```text
[ ] Parent
[ ] Teacher
[ ] Push Notification
[ ] Attendance
[ ] Schedule
```

## Sprint 9 — Student Affairs

```text
[ ] Violations
[ ] Counseling
[ ] Evidence
```

## Sprint 10 — SaaS

```text
[ ] Plans
[ ] Subscription
[ ] Invoice
[ ] Payment
[ ] Affiliate
```

## Sprint 11 — Hardening

```text
[ ] Security
[ ] Load testing
[ ] Backup
[ ] Restore
[ ] Monitoring
[ ] Error handling
```

---

# 38. Definition of Done

Sebuah modul **belum dianggap selesai** hanya karena halaman sudah tampil.

Modul dianggap selesai jika:

```text
Database
✓

Migration
✓

Model
✓

Validation
✓

Authorization
✓

Tenant isolation
✓

Service/Action
✓

Controller
✓

API Resource
✓

Frontend
✓

Loading state
✓

Empty state
✓

Error state
✓

Permission denied state
✓

Audit
✓

Unit test
✓

Feature test
✓

Security test
✓

Documentation
✓
```

---

# 39. Urutan Implementasi Nyata

Mulai coding dengan urutan berikut:

```text
01. Docker + Environment
        ↓
02. Laravel + PostgreSQL
        ↓
03. Redis + Queue
        ↓
04. Organizations + Schools
        ↓
05. Users + Membership
        ↓
06. Roles + Permissions
        ↓
07. Authentication
        ↓
08. Tenant Context
        ↓
09. Student
        ↓
10. Teacher
        ↓
11. Parent
        ↓
12. Class + Enrollment
        ↓
13. Subject + Room
        ↓
14. Schedule
        ↓
15. Attendance
        ↓
16. Correction + Approval
        ↓
17. Audit
        ↓
18. Next.js Dashboard
        ↓
19. Notification
        ↓
20. Reports
        ↓
21. Flutter
        ↓
22. Student Affairs
        ↓
23. Billing
        ↓
24. Security Hardening
        ↓
25. Production
```

---

# 40. Prinsip Utama Produk

Ada lima prinsip yang tidak boleh dikorbankan selama development:

### 1. Data sekolah harus aman

Tidak boleh ada kebocoran antar sekolah.

### 2. Permission harus berasal dari backend

Frontend hanya menyembunyikan UI.

Backend tetap melakukan authorization.

### 3. Riwayat harus dapat ditelusuri

Data penting memiliki audit trail.

### 4. Guru harus dapat bekerja cepat

Terutama:

```text
Jadwal
Kelas
Kehadiran
Pengumuman
```

### 5. Sistem harus bisa berkembang

Arsitektur awal harus mampu berkembang dari:

```text
1 sekolah
```

menjadi:

```text
100 sekolah
```

kemudian:

```text
1.000+ sekolah
```

tanpa mengubah fundamental data model.

---

# PHASE 6 CHECKPOINT

Setelah tahap foundation ini, status rancangan menjadi:

```text
Architecture       ██████████ 100%
Database Design    ██████████ 100%
RBAC Design        ██████████ 100%
Tenant Design      ██████████ 100%
API Strategy       ██████████ 100%
Web Architecture   ██████████ 100%
Mobile Architecture██████████ 100%

Actual Coding      ███░░░░░░░ 30%
```

Tahap berikutnya adalah **PHASE 7 — CORE BACKEND IMPLEMENTATION**, yaitu mulai membangun bagian yang benar-benar executable:

```text
Laravel Project
        ↓
Migration
        ↓
Models
        ↓
Enums
        ↓
Seeders
        ↓
Authentication
        ↓
RBAC
        ↓
Tenant Middleware
        ↓
Student API
        ↓
Teacher API
        ↓
Parent API
        ↓
Class & Enrollment API
```

Dari titik ini kita tidak lagi hanya mendesain produk; kita mulai menyusun **kode backend inti yang dapat langsung dijadikan basis repository proyek**.

# MASTER CODING-READY SPECIFICATION

## School Management SaaS

**Status:** Ready for Development
**Architecture:** Modular Monolith + Multi-Tenant SaaS
**Backend:** Laravel + PostgreSQL + Redis
**Web:** Next.js + TypeScript
**Mobile:** Flutter
**Storage:** S3-compatible
**API:** REST `/api/v1`

---

# 1. FINAL PRODUCT STRUCTURE

Produk terdiri dari tiga aplikasi utama:

```text
school-saas/
│
├── backend/       Laravel API
├── web/           Next.js Web App
├── mobile/        Flutter App
│
├── infra/         Docker / Nginx / deployment
├── docs/          Technical documentation
└── .github/       CI/CD
```

Konsep bisnis:

```text
Platform
   │
   ├── Organization
   │      │
   │      ├── School A
   │      ├── School B
   │      └── School C
   │
   └── Platform Administration
```

Untuk MVP, satu sekolah dapat menjadi satu organization.

Tetapi database tetap disiapkan agar satu organization dapat memiliki beberapa sekolah.

---

# 2. TECHNOLOGY STACK

## Backend

```text
PHP
Laravel
PostgreSQL
Redis
Laravel Queue
Laravel Scheduler
Laravel Sanctum
Laravel Notifications
```

## Frontend

```text
Next.js
React
TypeScript
Tailwind CSS
TanStack Query
Zod
React Hook Form
```

## Mobile

```text
Flutter
Dart
Riverpod
Dio
GoRouter
Firebase Cloud Messaging
```

## Infrastructure

```text
Docker
Nginx
Cloudflare
S3-compatible storage
GitHub Actions
```

---

# 3. CODING CONVENTION

## Backend

PHP:

```text
PSR-12
```

Class:

```php
StudentService
CreateStudentAction
StudentController
StudentResource
StudentPolicy
```

Database:

```text
snake_case
```

Contoh:

```text
student_enrollments
daily_attendance
teacher_subject_assignments
```

API:

```text
kebab-case
```

Contoh:

```text
/api/v1/student-enrollments
```

JSON:

```text
snake_case
```

Contoh:

```json
{
  "student_id": "...",
  "academic_year_id": "..."
}
```

---

# 4. DIRECTORY BACKEND

```text
backend/
│
├── app/
│   ├── Actions/
│   │   ├── Auth/
│   │   ├── Students/
│   │   ├── Teachers/
│   │   ├── Attendance/
│   │   └── Schools/
│   │
│   ├── Enums/
│   ├── Events/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   │
│   ├── Jobs/
│   ├── Listeners/
│   ├── Models/
│   ├── Notifications/
│   ├── Policies/
│   ├── Services/
│   └── Support/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── routes/
│   ├── api.php
│   └── console.php
│
└── tests/
    ├── Feature/
    ├── Integration/
    └── Unit/
```

---

# 5. ENUMS

Semua status penting menggunakan Enum.

## UserStatus

```text
ACTIVE
INACTIVE
SUSPENDED
```

## SchoolStatus

```text
ACTIVE
INACTIVE
SUSPENDED
```

## Gender

```text
MALE
FEMALE
```

## EnrollmentStatus

```text
ACTIVE
TRANSFERRED
GRADUATED
DROPPED_OUT
INACTIVE
```

## AttendanceStatus

```text
PRESENT
LATE
ABSENT
SICK
EXCUSED
```

## AcademicYearStatus

```text
DRAFT
ACTIVE
CLOSED
```

## AnnouncementStatus

```text
DRAFT
PUBLISHED
ARCHIVED
```

## NotificationStatus

```text
PENDING
SENT
FAILED
READ
```

## SubscriptionStatus

```text
TRIAL
ACTIVE
PAST_DUE
CANCELLED
EXPIRED
```

---

# 6. DATABASE FINAL

## organizations

```text
id
name
slug
status
created_at
updated_at
deleted_at
```

Constraint:

```text
slug UNIQUE
```

---

# 7. schools

```text
id
organization_id
school_code
npsn
name
level
address
province
city
district
village
postal_code
phone
email
website
logo_file_id
status
created_at
updated_at
deleted_at
```

Index:

```text
organization_id
school_code
npsn
```

Unique:

```text
organization_id + school_code
```

---

# 8. school_settings

```text
id
school_id
timezone
currency
attendance_late_threshold
attendance_start_time
attendance_end_time
enable_parent_notification
enable_teacher_notification
enable_whatsapp
enable_email
enable_push
created_at
updated_at
```

Default timezone:

```text
Asia/Makassar
```

Karena aplikasi akan digunakan di wilayah Indonesia dan timezone harus ditentukan per sekolah.

---

# 9. users

```text
id
name
email
phone
password
status
email_verified_at
phone_verified_at
last_login_at
remember_token
created_at
updated_at
deleted_at
```

Jangan simpan password plaintext.

Gunakan Laravel hashing.

---

# 10. school_memberships

```text
id
school_id
user_id
status
joined_at
created_at
updated_at
```

Unique:

```text
school_id + user_id
```

Ini adalah penghubung user dengan sekolah.

---

# 11. roles

```text
id
name
slug
description
is_system
created_at
updated_at
```

Contoh:

```text
super_admin
platform_admin
organization_admin
school_admin
headmaster
teacher
homeroom_teacher
counselor
staff
student
parent
```

---

# 12. permissions

```text
id
name
slug
module
action
created_at
updated_at
```

Contoh:

```text
students.view
students.create
students.update
students.delete
```

---

# 13. role_permissions

```text
role_id
permission_id
```

Unique:

```text
role_id + permission_id
```

---

# 14. user_roles

```text
id
user_id
role_id
school_id nullable
created_at
updated_at
```

`school_id = NULL` dapat digunakan untuk role platform.

Contoh:

```text
SUPER_ADMIN
```

Sedangkan:

```text
SCHOOL_ADMIN
```

wajib memiliki school context.

---

# 15. academic_years

```text
id
school_id
name
start_date
end_date
status
created_at
updated_at
```

Contoh:

```text
2026/2027
```

Hanya satu tahun ajaran aktif per sekolah.

---

# 16. semesters

```text
id
academic_year_id
name
sequence
start_date
end_date
status
created_at
updated_at
```

Contoh:

```text
Semester 1
Semester 2
```

---

# 17. teachers

```text
id
school_id
user_id nullable
employee_number
nip
name
gender
birth_place
birth_date
phone
email
address
photo_file_id
status
created_at
updated_at
deleted_at
```

`user_id` boleh nullable karena tidak semua guru langsung mempunyai akun.

---

# 18. parents

```text
id
school_id
user_id nullable
father_name
mother_name
guardian_name
phone
email
address
created_at
updated_at
deleted_at
```

Satu parent account dapat memiliki beberapa anak.

---

# 19. students

```text
id
school_id
nis
nisn
name
gender
birth_place
birth_date
nik nullable
religion nullable
phone nullable
address
photo_file_id
status
created_at
updated_at
deleted_at
```

Jangan menyimpan `class_id` langsung pada students.

Kelas ditentukan melalui enrollment.

---

# 20. parent_students

```text
id
parent_id
student_id
relationship
is_primary
created_at
updated_at
```

Relationship:

```text
FATHER
MOTHER
GUARDIAN
```

---

# 21. rooms

```text
id
school_id
name
code
capacity
description
status
created_at
updated_at
deleted_at
```

---

# 22. classes

```text
id
school_id
academic_year_id
name
grade_level
homeroom_teacher_id
room_id
capacity
status
created_at
updated_at
deleted_at
```

Contoh:

```text
VII-A
VII-B
VIII-A
IX-A
```

---

# 23. student_enrollments

```text
id
school_id
student_id
academic_year_id
class_id
status
enrolled_at
ended_at
created_at
updated_at
```

Unique:

```text
student_id + academic_year_id
```

Dengan demikian histori kelas tetap aman.

---

# 24. subjects

```text
id
school_id
code
name
description
status
created_at
updated_at
deleted_at
```

---

# 25. teacher_subject_assignments

```text
id
school_id
teacher_id
subject_id
class_id
academic_year_id
created_at
updated_at
```

---

# 26. schedules

```text
id
school_id
class_id
subject_id
teacher_id
room_id
day_of_week
start_time
end_time
created_at
updated_at
```

Validasi:

```text
start_time < end_time
```

Tidak boleh terjadi benturan jadwal.

---

# 27. daily_attendance

```text
id
school_id
student_id
date
status
check_in_at
check_out_at
notes
recorded_by
created_at
updated_at
```

Unique:

```text
school_id + student_id + date
```

---

# 28. lesson_sessions

```text
id
school_id
schedule_id
teacher_id
class_id
subject_id
date
start_at
end_at
status
notes
created_at
updated_at
```

---

# 29. lesson_attendance

```text
id
lesson_session_id
student_id
status
notes
created_at
updated_at
```

Unique:

```text
lesson_session_id + student_id
```

---

# 30. violation_types

```text
id
school_id
name
description
severity
points
status
created_at
updated_at
```

---

# 31. student_violations

```text
id
school_id
student_id
violation_type_id
reported_by
occurred_at
description
points
status
created_at
updated_at
```

---

# 32. counseling_sessions

```text
id
school_id
student_id
counselor_id
scheduled_at
started_at
ended_at
summary
recommendation
is_confidential
created_at
updated_at
```

Data counseling dapat memiliki tingkat kerahasiaan lebih tinggi.

---

# 33. announcements

```text
id
school_id
created_by
title
content
status
published_at
expires_at
created_at
updated_at
deleted_at
```

---

# 34. announcement_targets

```text
id
announcement_id
target_type
target_id
```

Target dapat berupa:

```text
ALL
TEACHERS
PARENTS
STUDENTS
CLASS
```

---

# 35. notifications

```text
id
school_id
user_id
type
title
body
data
status
read_at
created_at
updated_at
```

---

# 36. notification_deliveries

```text
id
notification_id
channel
status
provider_message_id
sent_at
failed_at
error_message
created_at
updated_at
```

---

# 37. files

```text
id
school_id
uploaded_by
disk
path
original_name
mime_type
size
visibility
created_at
updated_at
deleted_at
```

Visibility:

```text
PRIVATE
PROTECTED
PUBLIC
```

Default:

```text
PRIVATE
```

---

# 38. audit_logs

```text
id
school_id nullable
user_id nullable
action
entity_type
entity_id
old_values
new_values
ip_address
user_agent
created_at
```

---

# 39. BILLING

## plans

```text
id
name
slug
description
price
billing_interval
student_limit
teacher_limit
storage_limit
features
status
created_at
updated_at
```

## subscriptions

```text
id
school_id
plan_id
status
starts_at
ends_at
trial_ends_at
cancelled_at
created_at
updated_at
```

## invoices

```text
id
school_id
subscription_id
invoice_number
amount
status
due_at
paid_at
created_at
updated_at
```

## payments

```text
id
invoice_id
provider
provider_reference
amount
status
paid_at
raw_response
created_at
updated_at
```

---

# 40. API RESPONSE STANDARD

Success:

```json
{
  "success": true,
  "data": {},
  "meta": {}
}
```

Error:

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Data tidak valid.",
    "details": {}
  }
}
```

Pagination:

```json
{
  "success": true,
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}
```

---

# 41. STANDARD ERROR CODES

```text
UNAUTHENTICATED
UNAUTHORIZED
FORBIDDEN
VALIDATION_ERROR
NOT_FOUND
CONFLICT
DUPLICATE_RESOURCE
TENANT_ACCESS_DENIED
INVALID_SCHOOL_CONTEXT
ATTENDANCE_ALREADY_EXISTS
INVALID_ATTENDANCE_CORRECTION
SCHEDULE_CONFLICT
FILE_ACCESS_DENIED
RATE_LIMITED
SERVER_ERROR
```

---

# 42. API ROUTES

## Auth

```text
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/me
POST /api/v1/auth/forgot-password
POST /api/v1/auth/reset-password
POST /api/v1/auth/select-school
```

## Students

```text
GET    /api/v1/students
POST   /api/v1/students
GET    /api/v1/students/{student}
PUT    /api/v1/students/{student}
DELETE /api/v1/students/{student}
```

## Parents

```text
GET  /api/v1/parents
POST /api/v1/parents
GET  /api/v1/parents/{parent}
PUT  /api/v1/parents/{parent}
```

## Teachers

```text
GET  /api/v1/teachers
POST /api/v1/teachers
GET  /api/v1/teachers/{teacher}
PUT  /api/v1/teachers/{teacher}
```

## Classes

```text
GET  /api/v1/classes
POST /api/v1/classes
GET  /api/v1/classes/{class}
PUT  /api/v1/classes/{class}
```

## Enrollment

```text
POST /api/v1/student-enrollments
PUT  /api/v1/student-enrollments/{enrollment}
```

## Attendance

```text
GET  /api/v1/attendance
POST /api/v1/attendance
POST /api/v1/attendance/bulk
POST /api/v1/attendance/{attendance}/correction
```

## Schedule

```text
GET  /api/v1/schedules
POST /api/v1/schedules
PUT  /api/v1/schedules/{schedule}
DELETE /api/v1/schedules/{schedule}
```

---

# 43. LARAVEL LAYERING

Jangan menaruh semua logic di Controller.

Gunakan:

```text
Request
 ↓
Controller
 ↓
Action / Service
 ↓
Model
 ↓
Database
```

Contoh:

```text
StoreStudentRequest
        ↓
StudentController
        ↓
CreateStudentAction
        ↓
Student
```

Controller harus tipis.

---

# 44. STUDENT ACTION

```text
CreateStudentAction
UpdateStudentAction
DeleteStudentAction
ImportStudentsAction
EnrollStudentAction
TransferStudentAction
```

Attendance:

```text
RecordAttendanceAction
RecordBulkAttendanceAction
RequestAttendanceCorrectionAction
ApproveAttendanceCorrectionAction
```

---

# 45. POLICY

Setiap resource sensitif memiliki Policy.

```text
StudentPolicy
TeacherPolicy
ParentPolicy
ClassPolicy
SchedulePolicy
AttendancePolicy
AnnouncementPolicy
ReportPolicy
```

Contoh logika:

```text
canView()
canCreate()
canUpdate()
canDelete()
```

Policy wajib memeriksa:

```text
User
+
Role
+
Permission
+
School Membership
+
Resource School
```

---

# 46. TENANT QUERY RULE

Semua operational model wajib mempunyai `school_id`.

Contoh:

```text
students
teachers
parents
classes
subjects
rooms
schedules
attendance
announcements
notifications
files
```

Query default harus tenant-aware.

Jangan pernah membuat endpoint seperti:

```php
Student::find($id)
```

tanpa memastikan student tersebut berada dalam school context.

Gunakan pola:

```php
Student::query()
    ->where('school_id', $schoolId)
    ->findOrFail($id);
```

---

# 47. FRONTEND STRUCTURE

```text
web/
├── app/
├── components/
│   ├── ui/
│   ├── layout/
│   ├── tables/
│   ├── forms/
│   └── charts/
│
├── lib/
│   ├── api.ts
│   ├── auth.ts
│   ├── permissions.ts
│   └── utils.ts
│
├── services/
│   ├── student.service.ts
│   ├── teacher.service.ts
│   ├── attendance.service.ts
│   └── schedule.service.ts
│
├── hooks/
├── stores/
└── types/
```

---

# 48. UI DESIGN SYSTEM

Gunakan design token.

## Spacing

```text
4
8
12
16
20
24
32
40
48
64
```

## Radius

```text
6
8
12
16
```

## Typography

```text
Display
Heading 1
Heading 2
Heading 3
Body
Small
Caption
```

## Components

Minimal:

```text
Button
Input
Select
Textarea
Checkbox
Radio
Switch
Modal
Drawer
Dropdown
Tabs
Badge
Avatar
Card
Table
Pagination
Toast
Alert
Skeleton
EmptyState
ErrorState
```

---

# 49. DASHBOARD COMPONENTS

```text
DashboardHeader
SchoolSelector
StatCard
AttendanceSummary
TodaySchedule
RecentActivity
AnnouncementCard
QuickActions
NotificationPanel
```

---

# 50. TABLE STANDARD

Semua tabel menggunakan:

```text
Search
Filter
Sort
Pagination
Column visibility
Bulk action
Export
```

Untuk mobile:

```text
Table → Responsive Card/List
```

---

# 51. FORM STANDARD

Setiap form memiliki:

```text
Loading
Validation
Error
Success
Cancel
Unsaved changes protection
```

Contoh:

```text
[ Simpan ]
```

ketika submit berubah menjadi:

```text
[ Menyimpan... ]
```

Tidak boleh ada double submit.

---

# 52. EMPTY STATE

Jangan hanya:

```text
Data tidak ditemukan.
```

Gunakan:

```text
Belum ada siswa

Tambahkan siswa pertama untuk mulai mengelola
data peserta didik sekolah Anda.

[ + Tambah Siswa ]
```

---

# 53. MOBILE OFFLINE STRATEGY

Aplikasi mobile harus mampu menangani koneksi buruk.

Minimal:

```text
Cache
Retry
Offline indicator
Pending action
Sync
```

Khusus attendance:

```text
Input lokal
 ↓
Pending
 ↓
Internet tersedia
 ↓
Sync
 ↓
Server confirmation
```

Tetap gunakan server sebagai source of truth.

---

# 54. SECURITY BASELINE

Wajib:

```text
HTTPS
Password hashing
Rate limiting
CSRF protection
CORS restriction
Input validation
Output escaping
SQL parameter binding
Authorization
Tenant isolation
Audit logging
Signed file URLs
Webhook signature validation
Secret management
```

Tambahkan:

```text
2FA
```

minimal untuk:

```text
SUPER_ADMIN
PLATFORM_ADMIN
```

dan dapat diperluas ke admin sekolah.

---

# 55. RATE LIMIT

Contoh:

```text
Login:
5 attempts / minute / IP

Password reset:
3 requests / hour

General API:
60 requests / minute

Bulk operation:
lebih ketat
```

Angka final dapat disesuaikan berdasarkan load testing.

---

# 56. OBSERVABILITY

Log:

```text
request_id
user_id
school_id
route
method
status
duration
ip
user_agent
```

Error harus mempunyai:

```text
unique error ID
```

agar support dapat berkata:

> Mohon kirim Error ID: `ERR-20260922-XXXX`.

---

# 57. API DOCUMENTATION

Semua endpoint harus didokumentasikan dengan OpenAPI.

Dokumen:

```text
docs/api/openapi.yaml
```

Setiap endpoint menjelaskan:

```text
method
path
authentication
permission
parameters
request body
response
errors
examples
```

---

# 58. SEED DATA

Seeder pertama:

```text
DatabaseSeeder
RoleSeeder
PermissionSeeder
RolePermissionSeeder
DemoOrganizationSeeder
DemoSchoolSeeder
DemoUserSeeder
DemoAcademicYearSeeder
DemoClassSeeder
DemoStudentSeeder
```

Development:

```text
php artisan migrate:fresh --seed
```

harus menghasilkan environment demo yang langsung bisa digunakan.

---

# 59. DEMO ACCOUNT

Development saja:

```text
Super Admin
admin@example.test

School Admin
schooladmin@example.test

Teacher
teacher@example.test

Parent
parent@example.test
```

Password hanya untuk development.

Jangan menggunakan credential demo tersebut di production.

---

# 60. TEST CASE UTAMA

## Authentication

```text
[ ] Login valid
[ ] Login invalid
[ ] Logout
[ ] Token/session invalid
[ ] Password reset
[ ] Account suspended
```

## Tenant

```text
[ ] School A tidak melihat School B
[ ] User tanpa membership ditolak
[ ] Resource school lain ditolak
[ ] Role school lain tidak berlaku
```

## Student

```text
[ ] Create
[ ] Update
[ ] Delete
[ ] Duplicate NIS
[ ] Import
[ ] Enrollment
[ ] Transfer
```

## Attendance

```text
[ ] Present
[ ] Late
[ ] Absent
[ ] Sick
[ ] Excused
[ ] Duplicate prevention
[ ] Correction
[ ] Approval
[ ] Audit
```

---

# 61. CRITICAL E2E JOURNEY

Journey 1:

```text
Register School
 ↓
Create Admin
 ↓
Login
 ↓
Create Academic Year
 ↓
Create Class
 ↓
Create Teacher
 ↓
Create Student
 ↓
Enroll Student
 ↓
Create Subject
 ↓
Create Schedule
 ↓
Teacher Login
 ↓
Open Class
 ↓
Input Attendance
 ↓
Parent receives notification
```

Journey 2:

```text
Admin
 ↓
Import 500 students
 ↓
Validation
 ↓
Preview
 ↓
Confirm
 ↓
Queue
 ↓
Import
 ↓
Error report
```

Journey 3:

```text
Teacher
 ↓
Incorrect attendance
 ↓
Correction request
 ↓
Admin approval
 ↓
Audit log
 ↓
Parent notification
```

---

# 62. DEVELOPMENT COMMANDS

Backend:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Queue:

```bash
php artisan queue:work
```

Scheduler:

```bash
php artisan schedule:work
```

Frontend:

```bash
npm install
npm run dev
```

Flutter:

```bash
flutter pub get
flutter run
```

---

# 63. LOCAL DEVELOPMENT

Expected:

```text
Web:
http://localhost:3000

API:
http://localhost/api

Mailpit:
http://localhost:8025

MinIO:
http://localhost:9001
```

Production URL nanti dapat menggunakan:

```text
app.domain.com
api.domain.com
```

---

# 64. GIT WORKFLOW

Feature:

```text
feature/student-management
feature/attendance
feature/schedule
feature/notifications
```

Commit:

```text
feat: add student management
feat: add attendance recording
fix: prevent duplicate attendance
refactor: improve tenant context
test: add student authorization tests
docs: update api specification
```

---

# 65. PULL REQUEST CHECKLIST

Sebelum merge:

```text
[ ] Feature selesai
[ ] No debug code
[ ] No secret
[ ] Tests pass
[ ] Tenant isolation checked
[ ] Authorization checked
[ ] Migration reversible
[ ] API documented
[ ] Error handling
[ ] Loading state
[ ] Empty state
[ ] Mobile responsive
```

---

# 66. PRODUCTION CHECKLIST

```text
[ ] HTTPS
[ ] Domain
[ ] DNS
[ ] Cloudflare
[ ] PostgreSQL production
[ ] Redis production
[ ] Object storage
[ ] Queue workers
[ ] Scheduler
[ ] Backup
[ ] Restore test
[ ] Monitoring
[ ] Error tracking
[ ] Rate limiting
[ ] CORS
[ ] Security headers
[ ] Secrets
[ ] Email
[ ] WhatsApp
[ ] Push notification
[ ] Payment webhook
[ ] Terms
[ ] Privacy policy
```

---

# 67. MVP SCOPE

Jangan langsung mengembangkan semua fitur.

MVP:

```text
AUTH
✓

MULTI-TENANT
✓

SCHOOL
✓

USER/RBAC
✓

STUDENT
✓

TEACHER
✓

PARENT
✓

CLASS
✓

ENROLLMENT
✓

SUBJECT
✓

ROOM
✓

SCHEDULE
✓

ATTENDANCE
✓

ANNOUNCEMENT
✓

NOTIFICATION
✓

BASIC REPORT
✓

AUDIT
✓
```

Fase setelah MVP:

```text
VIOLATION
COUNSELING
IMPORT ADVANCED
REPORT BUILDER
WHATSAPP
PAYMENT
SUBSCRIPTION
AFFILIATE
ADVANCED ANALYTICS
```

---

# 68. HAL YANG JANGAN DILAKUKAN

## Jangan

```text
❌ Semua logic di Controller
❌ Semua user memiliki akses admin
❌ Query tanpa school_id
❌ Password plaintext
❌ File sensitif public
❌ Attendance dapat diedit bebas
❌ Tidak ada audit
❌ Hard delete data penting
❌ Mengandalkan frontend untuk security
❌ Membuat satu tabel terlalu besar untuk semua data
❌ Menyimpan class_id permanen di student
❌ Mengirim email/WhatsApp langsung dalam HTTP request
```

## Gunakan

```text
✓ Action
✓ Service
✓ Policy
✓ Form Request
✓ Resource
✓ Event
✓ Queue
✓ Audit
✓ Tenant Context
✓ Database Constraint
✓ Automated Test
```

---

# 69. URUTAN CODING PERTAMA

Developer **jangan langsung membuat dashboard**.

Urutan:

```text
STEP 01
Create repository

STEP 02
Setup Docker

STEP 03
Setup Laravel

STEP 04
Setup PostgreSQL

STEP 05
Setup Redis

STEP 06
Create migrations

STEP 07
Create enums

STEP 08
Create models

STEP 09
Create factories

STEP 10
Create seeders

STEP 11
Create authentication

STEP 12
Create tenant context

STEP 13
Create RBAC

STEP 14
Create policies

STEP 15
Create Student API

STEP 16
Create Teacher API

STEP 17
Create Parent API

STEP 18
Create Class API

STEP 19
Create Enrollment API

STEP 20
Create Schedule API

STEP 21
Create Attendance API

STEP 22
Create Audit

STEP 23
Create Next.js authentication

STEP 24
Create dashboard

STEP 25
Create Student UI
```

Setelah itu baru masuk notification, reports, mobile dan billing.

---

# 70. CODING READINESS GATE

Proyek dinyatakan **READY TO CODE** jika:

```text
Architecture       ✓
Database           ✓
Relationships      ✓
Enums              ✓
RBAC               ✓
Tenant Model       ✓
API Convention     ✓
Error Convention   ✓
Backend Structure  ✓
Frontend Structure ✓
Mobile Structure   ✓
Security Baseline  ✓
Testing Strategy   ✓
CI/CD Strategy     ✓
Deployment Plan    ✓
```

Status:

# READY TO CODE

---

# 71. LANGKAH IMPLEMENTASI BERIKUTNYA

Tahap berikutnya bukan lagi membuat blueprint.

Kita masuk ke:

## PHASE 7 — ACTUAL BACKEND CODEBASE

Urutannya:

```text
1. Laravel bootstrap
2. composer dependencies
3. PostgreSQL migration files
4. PHP Enums
5. Eloquent Models
6. Model relationships
7. Factories
8. Seeders
9. Authentication
10. Tenant middleware
11. RBAC middleware
12. Policies
13. Student CRUD
14. Teacher CRUD
15. Parent CRUD
16. Class CRUD
17. Enrollment
18. Schedule
19. Attendance
20. Audit
21. Feature tests
```

Setelah backend core selesai:

```text
PHASE 8
Next.js Dashboard

PHASE 9
Flutter Parent & Teacher

PHASE 10
Notification + WhatsApp + Email + Push

PHASE 11
Reports + Import/Export

PHASE 12
Billing + Subscription + Affiliate

PHASE 13
Security + Load Testing

PHASE 14
Production Deployment
```

**Dengan spesifikasi ini, developer sudah tidak perlu menentukan ulang struktur dasar proyek. Coding dapat dimulai dari bootstrap Laravel → migration → model → authentication → tenant → RBAC.**