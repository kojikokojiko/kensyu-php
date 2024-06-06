<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Category;
use PDO;

class ArticleCategoryRepository
{


    private PDO $db;


    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function createArticleCategory(int $articleId, int $categoryId): void
    {
        $stmt = $this->db->prepare("INSERT INTO article_category_tagging (article_id, category_id) VALUES (:article_id, :category_id)");
        $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateArticleCategories(int $articleId, array $categoryIds): void
    {
        $this->db->beginTransaction();

        // 既存のカテゴリIDを取得
        $stmt = $this->db->prepare("SELECT category_id FROM article_category_tagging WHERE article_id = :article_id");
        $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $existingCategoryIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // 削除対象のカテゴリIDを特定
        $categoryIdsToDelete = array_diff($existingCategoryIds, $categoryIds);
        if (!empty($categoryIdsToDelete)) {
            $stmt = $this->db->prepare("DELETE FROM article_category_tagging WHERE article_id = :article_id AND category_id = :category_id");
            foreach ($categoryIdsToDelete as $categoryIdToDelete) {
                $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
                $stmt->bindValue(':category_id', $categoryIdToDelete, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        // 挿入対象のカテゴリIDを特定
        $categoryIdsToInsert = array_diff($categoryIds, $existingCategoryIds);
        if (!empty($categoryIdsToInsert)) {
            $stmt = $this->db->prepare("INSERT INTO article_category_tagging (article_id, category_id) VALUES (:article_id, :category_id)");
            foreach ($categoryIdsToInsert as $categoryIdToInsert) {
                $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
                $stmt->bindValue(':category_id', $categoryIdToInsert, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        $this->db->commit();
    }

    public function getCategoriesByArticleId(int $articleId): array
    {
        $stmt = $this->db->prepare(
            "SELECT act.category_id, c.name 
         FROM article_category_tagging act 
         JOIN categories c ON act.category_id = c.id
         WHERE act.article_id = :article_id"
        );
        $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($results as $row) {
            $categories[] = new Category((int)$row['category_id'], $row['name']);
        }

        return $categories;
    }


}

