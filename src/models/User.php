<?php

// Model User đại diện cho thực thể người dùng trong hệ thống
class User
{
    // Các thuộc tính của User
    public $id;
    public $username;
    public $full_name;
    public $name;
    public $email;
    public $role;
    public $status;

    // Constructor để khởi tạo thực thể User
    public function __construct($data = [])
    {
        // Nếu truyền vào mảng dữ liệu thì gán vào các thuộc tính
        if (is_array($data)) {
            $this->id = $data['id'] ?? null;
            $this->username = $data['username'] ?? '';
            $this->name = $data['name'] ?? $data['full_name'] ?? '';
            $this->full_name = $data['full_name'] ?? $data['name'] ?? ''; // Tương thích với code cũ
            $this->email = $data['email'] ?? '';
            $this->role = $data['role'] ?? 'guide';
            $this->status = $data['status'] ?? 1;
        } else {
            // Nếu truyền vào string thì coi như tên (tương thích với code cũ)
            $this->name = $data;
            $this->full_name = $data;
        }
    }
    // Lấy tất cả users
    public static function getAll()
    {
        $db = getDB();
        if (!$db) return [];
        try {
            $stmt = $db->query("SELECT * FROM users ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return [];
        }
    }

    public static function create($data)
    {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO users (username, full_name, email, password, role, status) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->execute([
            $data['username'] ?? $data['email'], 
            $data['full_name'] ?? $data['name'] ?? '', 
            $data['email'], 
            $data['password'], 
            $data['role']
        ]);
        return $db->lastInsertId();
    }

    // Tìm user theo email
    public static function findByEmail($email){
        $db = getDB(); 
        if(!$db) {
            return null;
        }
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND status = 1");
            $stmt->execute([$email]);
            $data = $stmt->fetch();
            if($data) {
                return new User($data);
            }
            return null;
        }
        catch(\Throwable $e){
            error_log("Lỗi tìm user theo email: " . $e->getMessage());
            return null;
        }
    }
    // Xác thực đăng nhập bằng email và password
    public static function authenticate($email, $password)
    {
        // thuc hien tim user theo email
        $user = self::findByEmail($email);
        if (!$user) {
            return null; // Không tìm thấy user
        }

        // lay pass hash tu db
        $db = getDB();
        if (!$db) {
            return null;
        } 
        try {
            $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$user->id]);
            $result = $stmt->fetch();

            // Kiem tra so sanh password 
            if ($result && (password_verify($password, $result['password']) || $password === $result['password'])) {
                return $user; // Đăng nhập thành công
            }
            return null; // Mật khẩu không đúng
        } catch (\Throwable $e) {
            error_log("Lỗi xác thực user: " . $e->getMessage());
            return null;
        }
    }

    // Trả về tên người dùng để hiển thị
    public function getName()
    {
        return $this->full_name ?: $this->username;
    }

    // Kiểm tra xem user có phải là admin không
    // @return bool true nếu là admin, false nếu không
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Kiểm tra xem user có phải là hướng dẫn viên không
    // @return bool true nếu là hướng dẫn viên, false nếu không
    public function isGuide()
    {
        return $this->role === 'guide';
    }
}
