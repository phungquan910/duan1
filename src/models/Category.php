<?php
// Model Category đại diện cho thực thể danh mục tour
class Category
{
    // Khai báo thuộc tính của category
    public $id;
    public $name;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;

    // Khởi tạo đối tượng Category từ mảng dữ liệu
    public function __construct($data = [])
    {
        if (is_array($data)) {
            $this->id = $data["id"] ?? null;
            $this->name = $data['name'] ??'';
            $this->description = $data['description'] ?? '';
            $this->status = $data['status'] ?? '';
            $this->created_at = $data['created_at'] ?? null;
            $this->updated_at = $data['updated_at'] ?? null;
        }
    }

    // Lấy danh sách tất cả danh mục với bộ lọc trạng thái và từ khóa
    public static function all($status = null , $keyword = null )
    {
        $db = getDB();
        if (!$db) {
            return [];
        }

        try{
            $where =[];
            $params = [];

            // Loc theo trang thai

            if ($status !== null && $status !== '') {
                $where[] = "status = ?";
                $params[] = (int)$status;
            }

            // Loc theo tu khoa
            if (!empty($keyword)) {
                $where[] = ("name LIKE ? OR description LIKE ?");
                $searchTerm = '%' . trim($keyword) .'%';
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            // Viet cau SQL
            $sql = "SELECT * FROM categories";
            if (!empty($where)) {
                $sql .= " WHERE " . implode(" AND ", $where);
            }

            $sql .= " ORDER BY created_at DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            $result = $stmt->fetchAll();
            $categories = [];

            foreach ($result as $key => $row) {
                $categories[] = new Category($row);
            }
            return $categories;

            
        } catch (PDOException $e) {
            error_log("Loi khi lay du lieu danh muc tour" . $e->getMessage());
            return [];
        }
    }

    // Phương thức tương thích với getAll()
    public static function getAll($status = null, $keyword = null)
    {
        return self::all($status, $keyword);
    }

    // Lấy danh mục theo ID
    public static function getById($id)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Cập nhật thông tin danh mục
    public static function update($id, $data)
    {
        $db = getDB();
        $stmt = $db->prepare("UPDATE categories SET name = ?, description = ?, status = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['description'], $data['status'], $id]);
    }

    // Xóa danh mục (kiểm tra có tour nào đang sử dụng không)
    public static function delete($id)
    {
        $db = getDB();
        // Kiểm tra xem có tour nào đang dùng danh mục này không
        $stmt = $db->prepare("SELECT COUNT(*) FROM tours WHERE category_id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return false; // Không thể xóa vì còn tour đang dùng
        }
        
        $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}