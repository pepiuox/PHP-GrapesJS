<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author     : PePiuoX
//  Email      : contact@pepiuox.net
//
class BlogPost
{
    protected $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Obtiene todos los posts del blog con información del autor.
     */
    public function getBlogPosts(): array
    {
        $query = "SELECT bp.id, bp.title, bp.post,
        p.first_name, p.last_name, bp.date_posted
        FROM blog_posts bp
        INNER JOIN people p ON bp.author_id = p.id
        ORDER BY bp.date_posted DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los tags asociados a un post específico.
     */
    public function getTagsForBlogPost(int $blogPostId): array
    {
        $query = "SELECT t.name
        FROM tags t
        INNER JOIN blog_post_tags bpt ON t.id = bpt.tag_id
        WHERE bpt.blog_post_id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $blogPostId]);

        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = $row["name"];
        }
        return $tags;
    }

    /**
     * Obtiene un post específico por su ID.
     */
    public function getBlogPostById(int $blogPostId): ?array
    {
        $query = "SELECT bp.id, bp.title, bp.post,
        p.first_name, p.last_name, bp.date_posted
        FROM blog_posts bp
        INNER JOIN people p ON bp.author_id = p.id
        WHERE bp.id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $blogPostId]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    /**
     * Crea un nuevo post en el blog.
     */
    public function createBlogPost(string $title, string $post, int $authorId): int
    {
        $query = "INSERT INTO blog_posts (title, post, author_id, date_posted)
        VALUES (:title, :post, :author_id, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':post' => $post,
            ':author_id' => $authorId,
        ]);

        return (int) $this->conn->lastInsertId();
    }

    /**
     * Actualiza un post existente.
     */
    public function updateBlogPost(int $id, string $title, string $post): bool
    {
        $query = "UPDATE blog_posts
        SET title = :title, post = :post
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':id' => $id,
            ':title' => $title,
            ':post' => $post,
        ]);
    }

    /**
     * Elimina un post del blog.
     */
    public function deleteBlogPost(int $id): bool
    {
        $query = "DELETE FROM blog_posts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>
