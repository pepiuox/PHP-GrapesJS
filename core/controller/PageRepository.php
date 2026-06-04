<?php
// Separar responsabilidades - Patrón Single Responsibility
class PageRepository {
    private $conn;

    public function findById($id) { /*...*/ }
    public function findBySlug($slug) { /*...*/ }
    public function findByParent($parentId) { /*...*/ }
    public function getHierarchy($pageId) { /*...*/ }
}


