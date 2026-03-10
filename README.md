# Flashcard Learning Hub

Flashcard Learning Hub là hệ thống học tập bằng flashcard được xây dựng bằng Laravel 11, PHP và Blade Template. Dự án có 2 cổng sử dụng riêng biệt cho admin và client, đồng thời cung cấp JWT API để phục vụ các tác vụ xác thực và thao tác dữ liệu theo vai trò.

README này tập trung vào 4 phần chính:

- Tài khoản test để review nhanh hệ thống.
- Cấu trúc thư mục và cách tổ chức code.
- Kiến trúc hệ thống theo mô hình MVC.
- Đối chiếu trực tiếp với các tiêu chí đánh giá của đồ án.

## 1. Công nghệ sử dụng

- Backend: Laravel 11, PHP 8.2+
- Frontend server-rendered: Blade Template
- Styling: Tailwind CSS
- Build tool: Vite
- Authentication:
	- Session guard riêng cho admin và client trên web
	- JWT cho API
- Database: SQLite hoặc MySQL tùy môi trường

## 2. Chức năng chính của hệ thống

- Đăng ký, đăng nhập client bằng giao diện web.
- Đăng nhập admin bằng giao diện web riêng.
- Quản lý deck flashcard và flashcard.
- Học theo deck hoặc học tổng hợp.
- Theo dõi tiến độ học, streak, XP và cấp độ người dùng.
- Sao chép deck public từ cộng đồng.
- Đánh giá deck.
- Import và export deck bằng CSV.
- Cung cấp API cho auth, client và admin.

## 3. Tài khoản test

Sau khi chạy:

```bash
php artisan migrate:fresh --seed
```

có thể dùng các tài khoản seed sau để test nhanh hệ thống.

### Admin

| Tên | Email | Mật khẩu | Vai trò |
|---|---|---|---|
| Super Admin | admin@techvanguard.com | password | Admin |
| Moderator Admin | moderator@techvanguard.com | password | Admin |

### Client

| Tên | Email | Mật khẩu | Trạng thái |
|---|---|---|---|
| John Learner | john@example.com | password | active |
| Sarah Student | sarah@example.com | password | active |
| Mike Developer | mike@example.com | password | active |
| Emily Teacher | emily@example.com | password | active |
| Inactive User | inactive@example.com | password | inactive |

Ngoài các tài khoản trên, seeder còn tạo thêm 10 client ngẫu nhiên để tăng dữ liệu demo.

## 4. Cách chạy dự án

```bash
cp .env.example .env
composer install
npm install
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Nếu dùng môi trường local với SQLite, cần bảo đảm file database đã tồn tại trước khi migrate.

## 5. Cấu trúc thư mục chính

```text
techvanguar-pretest/
├── app/
│   ├── Auth/                  # Custom guards cho admin/client
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/           # Controller cho JWT API
│   │   │   └── Web/           # Controller cho giao diện web
│   │   ├── Middleware/        # Middleware role, session cookie, redirect
│   │   └── Requests/          # Form Request validate dữ liệu
│   ├── Models/                # Các model Eloquent
│   ├── Providers/             # Đăng ký auth guard, service provider
│   ├── Services/              # Business logic: study scheduler, CSV, OTP...
│   └── Support/               # Lớp hỗ trợ truy cập deck
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── client/
│       ├── components/
│       ├── layouts/
│       └── public/
├── routes/
│   ├── api.php
│   ├── web.php
│   └── console.php
├── storage/
└── vendor/
```

## 6. Cấu trúc hệ thống theo MVC

Hệ thống được tổ chức đúng kiểu Laravel MVC, trong đó mỗi lớp có vai trò tách biệt.

### Model

Các model chính trong `app/Models`:

- `User`: quản lý tài khoản, role, status, XP, streak và JWT claim.
- `Deck`: đại diện bộ flashcard, có owner, visibility, category, tags, source deck.
- `Flashcard`: dữ liệu từng thẻ học trong một deck.
- `StudyProgress`: lưu tiến độ học theo từng user và từng flashcard.
- `DeckReview`: lưu đánh giá deck theo user.

### View

Các giao diện Blade nằm trong `resources/views` và được tách theo từng khu vực chức năng:

- `public/`: landing page công khai.
- `auth/`: login, register, forgot password.
- `client/`: dashboard và các màn hình học tập của người dùng.
- `admin/`: dashboard quản trị.
- `components/`: component tái sử dụng.
- `layouts/`: layout tổng cho từng khu vực giao diện.

Điều này giúp phần hiển thị giao diện tách khỏi business logic và truy vấn dữ liệu.

### Controller

Controller được chia theo 2 luồng lớn:

- `app/Http/Controllers/Web`
	- `AuthPageController`: landing, login, register, forgot password, logout.
	- `ClientPortalController`: dashboard client, deck cá nhân, community, study, profile.
	- `AdminDashboardController`: quản lý user, deck, review, profile, account.

- `app/Http/Controllers/Api`
	- `AuthController`: register, login, logout, me bằng JWT.
	- `Admin/*`: CRUD user, deck, flashcard và thống kê cho admin.
	- `Client/*`: lấy deck, xem deck, cập nhật study progress.

### Service Layer hỗ trợ MVC

Ngoài MVC cơ bản, dự án có thêm `app/Services` để tách business logic phức tạp ra khỏi controller, giúp controller gọn và dễ bảo trì hơn:

- `StudyScheduler`: xử lý spaced repetition, lịch ôn tập và XP.
- `StudySessionService`: hỗ trợ luồng học.
- `CsvImportService`, `CsvExportService`: import/export deck.
- `DeckCopyService`: sao chép deck public.
- `PasswordResetService`, `RegistrationOtpService`: OTP và reset password.

Đây là cách tổ chức phù hợp với chuẩn Laravel thực tế, vì business logic không bị nhồi hết vào controller.

## 7. Kiến trúc hệ thống đã xây dựng

### 7.1. Web portal

Hệ thống web được chia thành 3 vùng giao diện rõ ràng:

- Public portal:
	- Route `/`
	- Hiển thị landing page, deck public và review nổi bật.

- Client portal:
	- Prefix `/client`
	- Dùng guard `client`
	- Bao gồm dashboard, quản lý deck, community, study, profile, import/export.

- Admin portal:
	- Prefix `/admin`
	- Dùng guard `admin`
	- Bao gồm quản lý user, deck, flashcard, review, profile và account.

### 7.2. API

API được tách ở `routes/api.php` và dùng JWT:

- `/api/auth/*`: xác thực.
- `/api/client/*`: nghiệp vụ cho client.
- `/api/admin/*`: CRUD và thống kê cho admin.

### 7.3. Authentication

Hệ thống dùng 2 kiểu xác thực song song:

- Session auth cho giao diện web.
- JWT auth cho API.

Điểm đáng chú ý là web có custom session guard riêng cho admin và client để tách cookie đăng nhập, tránh xung đột phiên làm việc.

### 7.4. Database logic và quan hệ dữ liệu

Các bảng nghiệp vụ chính:

- `users`
- `decks`
- `flashcards`
- `study_progress`
- `deck_reviews`

Relationship chính:

- Một `users` có nhiều `decks`.
- Một `decks` có nhiều `flashcards`.
- Một `users` có nhiều `study_progress`.
- Một `flashcards` có nhiều `study_progress`.
- Một `decks` có nhiều `deck_reviews`.
- Một `users` có nhiều `deck_reviews`.
- Một `decks` có thể tham chiếu `source_deck_id` để thể hiện deck được copy từ deck khác.

Các thành phần này phù hợp với chức năng hệ thống học flashcard, quản lý nội dung, tracking tiến độ và review cộng đồng.

## 8. Link DB Diagram

Bạn có thể dán link DB diagram tại đây sau khi hoàn thiện sơ đồ:

- DB Diagram: `https://dbdiagram.io/d/techvanguard-pretest-69afaba477d079431b44cbae`
