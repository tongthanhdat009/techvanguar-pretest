# Yêu cầu hoàn thiện hệ thống flashcard

## Mục tiêu
Hoàn thiện hệ thống để trải nghiệm sử dụng rõ ràng hơn giữa admin và client, đảm bảo đăng nhập hoạt động đúng, giao diện được phân chia hợp lý, và các chức năng học tập cốt lõi được đầy đủ.

## Yêu cầu chức năng

### 1. Đăng nhập và phiên làm việc
- Sau khi đăng nhập, hệ thống phải lưu đúng thông tin phiên đăng nhập và nhận diện được người dùng hiện tại.
- Sau khi đăng nhập thành công, người dùng phải được đưa vào đúng khu vực theo vai trò.
- Trong suốt quá trình sử dụng, phiên đăng nhập phải hoạt động ổn định và không làm mất thông tin người dùng.

### 2. Tách biệt giao diện đăng nhập
- Cần có giao diện đăng nhập riêng cho admin và client.
- Mỗi nhóm người dùng chỉ nhìn thấy giao diện và nội dung phù hợp với vai trò của mình.
- Không để giao diện đăng nhập chung gây nhầm lẫn giữa hai nhóm.

### 3. Nâng cấp cốt lõi trải nghiệm học
- Client phải có chức năng học toàn bộ flashcard đang khả dụng.
- Client phải xem được danh sách deck, vào chi tiết deck, và học theo từng deck hoặc học toàn bộ.
- Tiến trình học của từng flashcard phải được cập nhật đúng sau mỗi lần người dùng thao tác.
- Trạng thái tiến trình đã cập nhật phải được hiển thị lại chính xác trên giao diện.
- Hệ thống học phải hỗ trợ cơ chế lặp lại ngắt quãng để xác định thời điểm ôn tập phù hợp cho từng flashcard.
- Lịch ôn tập phải phản ánh được các mốc học lại theo thời gian để người dùng biết hôm nay cần ôn gì.
- Hệ thống phải có nhiều chế độ học khác nhau ngoài lật thẻ, bao gồm trắc nghiệm, nhập đáp án và các kiểu luyện tập tương tác.
- Flashcard cần hỗ trợ nội dung đa phương tiện như hình ảnh và âm thanh để tăng hiệu quả ghi nhớ.

### 4. Tính năng tạo và quản lý nội dung cho client
- Client phải có chức năng tạo deck của riêng mình.
- Client phải có chức năng tạo flashcard trong các deck mà mình sở hữu.
- Client phải có thể chỉnh sửa và quản lý nội dung học tập do mình tạo ra.
- Deck cần hỗ trợ phân loại theo danh mục hoặc gắn thẻ để dễ tìm kiếm và lọc.

### 5. Tương tác và cộng đồng
- Client phải có khả năng chia sẻ các flashcard hoặc bộ flashcard hiện có của mình.
- Mỗi deck do client tạo cần có quyền riêng tư rõ ràng, cho phép chọn công khai hoặc riêng tư.
- Người dùng phải có thể khám phá và học các deck công khai từ cộng đồng.
- Người dùng phải có thể sao chép một deck công khai về tài khoản của mình để tiếp tục chỉnh sửa và học tập.
- Người dùng phải có thể đánh giá và bình luận các deck công khai để phản ánh chất lượng nội dung.

### 6. Trò chơi hóa để giữ chân người dùng
- Hệ thống cần theo dõi chuỗi ngày học liên tục để khuyến khích người dùng quay lại mỗi ngày.
- Người dùng phải nhận được điểm thưởng khi hoàn thành hoạt động học tập như ôn tập hoặc hoàn thành deck.
- Hệ thống cần thể hiện cấp độ hoặc tiến trình phát triển của người dùng dựa trên điểm tích lũy.
- Cần có bảng xếp hạng theo tuần hoặc theo tháng để tạo động lực cạnh tranh lành mạnh giữa người dùng.

### 7. Hồ sơ người dùng
- Cần có giao diện để người dùng xem thông tin cá nhân.
- Cần có giao diện để người dùng chỉnh sửa thông tin cá nhân.
- Sau khi cập nhật, thông tin mới phải được hiển thị lại chính xác trên hệ thống.
- Hồ sơ người dùng nên thể hiện được các thông tin học tập nổi bật như chuỗi ngày học, điểm hoặc thành tích cơ bản.

### 8. Quản lý nội dung nâng cao
- Admin hoặc người dùng phù hợp phải có khả năng nhập dữ liệu deck và flashcard từ file CSV hoặc Excel để tạo nội dung hàng loạt.
- Hệ thống cần hỗ trợ xuất dữ liệu học tập hoặc nội dung deck ra các định dạng phổ biến để chia sẻ hoặc in ấn.
- Hệ thống phải hỗ trợ tìm kiếm và lọc deck theo tên, danh mục, tag hoặc trạng thái hiển thị.

### 9. Giao diện trang công khai
- Không hiển thị danh sách API endpoint trên giao diện người dùng.
- Không hiển thị tài khoản demo trên giao diện.
- Giao diện công khai chỉ nên tập trung vào trải nghiệm sử dụng sản phẩm.

### 10. Giao diện admin
- Giao diện admin phải được chia rõ theo từng nhóm chức năng.
- Các khu vực quản lý người dùng, deck, flashcard, nội dung cộng đồng và thống kê cần được tách biệt rõ ràng.
- Bố cục admin phải giúp thao tác quản trị dễ theo dõi và không bị dồn toàn bộ chức năng vào một màn hình lẫn lộn.

### 11. Tổ chức giao diện
- Khi xử lý lại giao diện, các phần giao diện cần được tách thành component riêng theo từng chức năng.
- Các component cần được phân chia rõ ràng để dễ tái sử dụng và quản lý.
- Không để toàn bộ giao diện dồn trong một file lớn khó bảo trì.

## Kết quả mong muốn
- Hệ thống đăng nhập đúng và lưu được phiên người dùng.
- Admin và client có giao diện tách biệt, rõ vai trò.
- Client có đầy đủ chức năng học, tạo deck, tạo flashcard, chia sẻ, học lại theo lịch ôn tập và theo dõi tiến trình học.
- Người dùng có thể học bằng nhiều chế độ khác nhau và sử dụng flashcard có nội dung đa phương tiện.
- Người dùng có thể xem và chỉnh sửa thông tin cá nhân.
- Hệ thống có thêm cơ chế giữ chân người dùng như streak, XP, level và leaderboard.
- Hệ thống hỗ trợ deck public/private, đánh giá, bình luận và sao chép nội dung từ cộng đồng.
- Hệ thống hỗ trợ import/export và phân loại nội dung bằng tag hoặc danh mục.
- Giao diện công khai không còn hiển thị API endpoint và tài khoản demo.
- Giao diện admin được phân chia hợp lý theo chức năng.
- Giao diện toàn hệ thống được tổ chức lại theo component rõ ràng.
