<?php
$objBlogPost = new BlogPost();

$arrPosts = $objBlogPost->getBlogPosts();

?>
<div id="main">
	<h1>My Simple Blog</h1>
	<div id="blogPosts">
		<?php
		if (count($arrPosts)) {

			foreach ($arrPosts as $post) {

				$tags = implode(",", $objBlogPost->getTagsForBlogPost($post['id']));

				echo "<div class='post'>";
				echo "<h1>" . $post['title'] . "</h1>";
				echo "<p>" . $post['post'] . "</h1>";
				echo "<span class='footer'>Posted By: " . $post['first_name'] . " Posted On: " . $post['date_posted'] . " Tags: " . $tags . "</span>";
				echo "</div>";
			}
		}
		?>
	</div>
</div>
