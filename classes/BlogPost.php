<?php

class BlogPost {

    private $connection;

    public function __construct() {
        global $conn;
        $this->connection = $conn;
    }

    public function getBlogPosts() {
        $query = "SELECT blog_posts.id, blog_posts.title, blog_posts.post, people.first_name, people.last_name, blog_posts.date_posted
FROM blog_posts
INNER JOIN people ON blog_posts.author_id = people.id";
        $result = $this->connection->query($query);
        $blogPosts = $result->fetch_all(MYSQLI_ASSOC);

        return $blogPosts;
    }

    public function getTagsForBlogPost($blogPostId) {
        $query = "SELECT tags.name
FROM tags
INNER JOIN blog_post_tags ON tags.id = blog_post_tags.tag_id
WHERE blog_post_tags.blog_post_id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $blogPostId);
        $stmt->execute();
        $result = $stmt->get_result();
        $tags = [];

        while ($row = $result->fetch_assoc()) {
            $tags[] = $row['name'];
        }

        return $tags;
    }

    public function getBlogPostById($blogPostId) {
        $query = "SELECT blog_posts.id, blog_posts.title, blog_posts.post, people.first_name, people.last_name, blog_posts.date_posted
FROM blog_posts
INNER JOIN people ON blog_posts.author_id = people.id
WHERE blog_posts.id = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param('i', $blogPostId);
        $stmt->execute();
        $result = $stmt->get_result();
        $blogPost = $result->fetch_assoc();

        return $blogPost;
    }
}

?>
