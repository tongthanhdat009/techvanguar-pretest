# Kế hoạch tối ưu giao diện và nghiệp vụ

## Mục tiêu
Tối ưu lại project theo hai hướng chính:

- Nghiệp vụ phải rõ ràng, ít lặp logic, dễ mở rộng và dễ test.
- Giao diện phải phản ánh đúng luồng sản phẩm, ưu tiên trải nghiệm học tập và khả năng quản trị.

## Hiện trạng codebase

### Kiến trúc tổng quan
- Dự án đang dùng Laravel 11, Blade, Tailwind CSS và Vite.
- Có hai lớp giao diện chính: client portal và admin dashboard.
- Có JWT API cho auth, client và admin.
- Logic spaced repetition hiện tập trung ở `App\Services\StudyScheduler`.

### Điểm mạnh hiện có
- Đã có phân tách route web và route api.
- Đã có các chức năng cốt lõi: auth, deck, flashcard, review, profile, progress, leaderboard.
- UI đã có design language cơ bản tương đối đồng nhất.
- Đã có test smoke cho web portal và API.

### Vấn đề chính đang tồn tại
- Controller web đang ôm quá nhiều trách nhiệm, đặc biệt là `ClientPortalController` và `AdminDashboardController`.
- Logic truy cập deck, validate dữ liệu và một phần nghiệp vụ bị lặp giữa web và API.
- Study flow hiện tại vẫn thiên về render danh sách thay vì tối ưu cho từng phiên học.
- Admin dashboard dồn quá nhiều chức năng trên một màn hình.
- Test hiện có chưa đủ sâu cho các rule nghiệp vụ quan trọng.

## Các vấn đề nghiệp vụ cần ưu tiên xử lý

### 1. Controller đang quá dày
Hiện controller xử lý đồng thời:

- query tổng hợp dữ liệu dashboard
- rule truy cập deck
- validate request
- import/export CSV
- copy deck
- update study progress
- build payload cho study mode

Điều này làm code khó bảo trì, khó tái sử dụng giữa web và API, và tăng rủi ro sai lệch hành vi khi sửa một bên.

### 2. Logic bị lặp giữa web và API
Các rule như deck nào được xem, deck nào được học, ai được update progress đang xuất hiện ở nhiều controller khác nhau. Đây là nguồn gây bug rất điển hình khi mở rộng tính năng.

### 3. Study queue đang xử lý nhiều bằng collection
Một phần chọn card học đang load dữ liệu rồi sort/filter ở PHP. Khi dữ liệu lớn lên, cách này sẽ không còn hiệu quả.

### 4. Validation chưa được chuẩn hóa
Create/update deck, flashcard, profile, review hiện validate trực tiếp trong controller. Điều này làm controller dài và khiến rule khó đồng bộ.

### 5. Test coverage chưa bám các use case quan trọng
Chưa có test tốt cho:

- due queue
- access rule public/private/owned
- copy public deck
- import CSV lỗi format
- scheduling theo again/hard/good/easy

## Các vấn đề giao diện cần ưu tiên xử lý

### 1. Client dashboard đang nhiều khối chức năng nhưng thiếu thứ tự ưu tiên
Dashboard hiện hiển thị khá nhiều nội dung cùng lúc: review queue, create deck, import, my decks, community decks, leaderboard. Người dùng mới vào chưa thấy rõ việc nào nên làm trước.

### 2. Study screen chưa tạo cảm giác học theo phiên
Trang study đã có 3 mode nhưng trải nghiệm vẫn còn nặng dạng form:

- chưa có session progress rõ ràng
- chưa có nhịp one-card-at-a-time
- multiple choice và typed mode chưa có feedback tương tác tốt
- chưa có summary cuối phiên học

### 3. Admin dashboard quá dài
Toàn bộ user management, deck management, flashcard management, moderation và import đang nằm trong một view lớn. Điều này gây khó dùng và khó bảo trì UI.

### 4. Component hóa mới ở mức nền tảng
Đã có layout chung và một số component nhỏ, nhưng chưa có bộ component theo page section, toolbar, action panel, management card, study session panel.

## Hướng tối ưu nghiệp vụ

### Phase 1: Chuẩn hóa domain và tách logic khỏi controller

#### Mục tiêu
- Giảm trách nhiệm của controller.
- Dùng chung nghiệp vụ giữa web và API.
- Tạo nền để refactor UI mà không phá behavior.

#### Việc cần làm
- Tạo các action/service riêng cho:
  - lấy client dashboard data
  - lấy study queue
  - copy deck
  - import deck từ CSV
  - export deck
  - lưu review deck
  - update profile
- Đưa các rule truy cập deck về policy hoặc service dùng chung.
- Đưa validation sang Form Request cho deck, flashcard, profile, review, progress.
- Tách phần build study payload khỏi controller.

#### Kết quả mong muốn
- Controller ngắn, đọc được theo use case.
- Web và API dùng chung lõi nghiệp vụ.
- Giảm duplication, dễ test hơn.

### Phase 2: Tối ưu study flow

#### Mục tiêu
- Biến study mode thành trải nghiệm sản phẩm trung tâm.

#### Việc cần làm
- Thiết kế lại study session theo từng card.
- Bổ sung progress bar cho phiên học.
- Thể hiện rõ số card còn lại, card đã xong, card cần học lại.
- Thêm feedback rõ ràng cho multiple choice và typed recall.
- Thêm summary sau phiên học: số câu đúng, số card mastered, XP nhận được, lịch ôn tiếp theo.
- Tối ưu query lấy due cards và upcoming cards.

#### Kết quả mong muốn
- Study screen phản ánh đúng luồng học.
- Người dùng hiểu hôm nay cần học gì và sau phiên học đạt được gì.

### Phase 3: Chuẩn hóa tính năng community và quản lý nội dung

#### Mục tiêu
- Làm rõ ranh giới giữa nội dung cá nhân, nội dung cộng đồng và nội dung quản trị.

#### Việc cần làm
- Chuẩn hóa hành vi public/private deck.
- Bổ sung query/filter cho category, tag, visibility, ownership.
- Chuẩn hóa copy public deck thành use case riêng.
- Chuẩn hóa review/rating để dễ mở rộng moderation.
- Rà lại import/export để xử lý tốt file lỗi hoặc thiếu cột.

#### Kết quả mong muốn
- Nội dung cộng đồng dễ quản lý hơn.
- Hành vi deck nhất quán giữa client web, admin và API.

## Hướng tối ưu giao diện

### Phase 1: Sắp xếp lại information architecture

#### Client
Tách dashboard thành 3 vùng chính:

- Today: due cards, streak, XP, nút học nhanh
- My Library: deck của tôi, tạo deck, import deck
- Explore: community deck, leaderboard, review nổi bật

#### Admin
Chia admin dashboard thành các route hoặc tab riêng:

- Overview
- Users
- Decks
- Reviews
- Imports / Exports

### Phase 2: Component hóa giao diện

#### Cần bổ sung các component dùng chung
- page header
- section toolbar
- action card
- management form section
- study session card
- queue summary card
- list empty state chuẩn hóa
- confirm delete pattern

#### Kết quả mong muốn
- View ngắn hơn.
- Dễ tái sử dụng UI.
- Khi đổi style không phải sửa nhiều file.

### Phase 3: Nâng cấp trải nghiệm từng màn chính

#### Public landing
- Tập trung vào giá trị sản phẩm.
- Làm rõ 2 luồng client và admin.
- Nhấn mạnh community deck, spaced repetition và learning progress.

#### Auth screens
- Làm rõ sự khác nhau giữa client login và admin login.
- Thống nhất bố cục, CTA và error presentation.

#### Client deck detail
- Làm rõ trạng thái deck, metadata, review, progress.
- Tách phần quản lý deck và phần học deck.

#### Study screen
- Ưu tiên một card chính tại một thời điểm.
- Hiển thị hành động trả lời rõ, nhanh và ít phân tán.

#### Admin
- Chuyển từ long-form page sang task-oriented management UI.

## Kế hoạch test sau refactor

### Test nghiệp vụ
- StudyScheduler tính đúng next review theo `again`, `hard`, `good`, `easy`.
- User chỉ truy cập được owned deck hoặc public active deck.
- Copy public deck tạo bản sao đúng dữ liệu flashcard.
- Import CSV xử lý đúng header hợp lệ và báo lỗi khi sai format.

### Test giao diện và integration
- Client login và admin login đi đúng portal.
- Client dashboard hiển thị đúng due queue.
- Study session update progress xong thì dữ liệu phản ánh lại đúng.
- Admin chỉ nhìn thấy nhóm chức năng đúng với route tương ứng.

## Thứ tự triển khai đề xuất

### Ưu tiên cao
1. Tách logic khỏi controller.
2. Chuẩn hóa validation và access rules.
3. Viết test cho các rule nghiệp vụ chính.

### Ưu tiên trung bình
1. Refactor client dashboard.
2. Refactor study screen.
3. Chia lại admin dashboard.

### Ưu tiên sau cùng
1. Tối ưu thêm performance truy vấn.
2. Mở rộng filter/search/community features.
3. Bổ sung session summary và analytics sâu hơn.

## Đề xuất bắt đầu thực thi
Nếu triển khai ngay, nên làm theo thứ tự sau:

1. Refactor `ClientPortalController` và gom study flow về service/action.
2. Tách rule truy cập deck thành policy hoặc query service dùng chung cho web và API.
3. Viết test nghiệp vụ cho study queue và progress update.
4. Sau khi lõi ổn định, mới làm lại study UI và chia nhỏ admin UI.

## Kết luận
Project hiện đã có đầy đủ nền tảng tính năng nhưng kiến trúc application layer và information architecture vẫn còn ở giai đoạn đầu. Nếu tối ưu đúng thứ tự, hệ thống sẽ đạt được ba lợi ích lớn:

- nghiệp vụ rõ và ít bug hơn
- giao diện dễ dùng hơn cho cả client và admin
- dễ mở rộng thêm tính năng học tập và community trong các vòng sau