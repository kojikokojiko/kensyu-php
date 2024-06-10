<?php
declare(strict_types=1);

namespace App\Model;

class Category
{
    const CATEGORIES = [
        1 => '総合',
        2 => 'テクノロジー',
        3 => 'モバイル',
        4 => 'アプリ',
        5 => 'エンタメ',
        6 => 'ビューティー',
        7 => 'ファッション',
        8 => 'ライフスタイル',
        9 => 'ビジネス',
        10 => 'グルメ',
        11 => 'スポーツ',
    ];

    public int $categoryId;
    public string $categoryName;
    public int $articleId;

    public function __construct(int $categoryId, int $articleId)
    {
        if (!isset(self::CATEGORIES[$categoryId])) {
            throw new \InvalidArgumentException("Invalid category ID: $categoryId");
        }
        $this->categoryId = $categoryId;
        $this->articleId = $articleId;
        $this->categoryName = self::CATEGORIES[$categoryId];
    }

    public static function getNameById(int $categoryId): ?string
    {
        return self::CATEGORIES[$categoryId] ?? null;
    }

    public static function getIdByName(string $categoryName): ?int
    {
        return array_search($categoryName, self::CATEGORIES, true) ?: null;
    }

    public static function getAllCategories(): array
    {
        return self::CATEGORIES;
    }
}
