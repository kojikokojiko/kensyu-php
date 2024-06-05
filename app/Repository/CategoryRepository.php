<?php

namespace App\Repository;

use App\Model\Category;
use PDO;

class CategoryRepository implements RepositoryInterface
{

    /**
     * @var PDO The PDO instance for database connection.
     */
    private PDO $db;

    /**
     * ArticleRepository constructor.
     *
     * Initializes the repository with the given PDO instance.
     *
     * @param PDO $db The PDO instance for database connection.
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Get all categories.
     *
     * Retrieves all categories from the database.
     *
     * @return Category[] An array of Category objects.
     */
    public function getAllCategories(): array
    {
        $stmt = $this->db->query("SELECT * FROM categories");
        $categoriesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[] = new Category($data['id'], $data['name']);
        }

        return $categories;
    }
}
