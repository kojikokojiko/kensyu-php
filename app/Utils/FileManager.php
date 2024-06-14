<?php
declare(strict_types=1);

namespace App\Utils;

use InvalidArgumentException;

class FileManager
{
    /**
     * Save the uploaded file to the specified directory.
     *
     * @param array $file The uploaded file info from $_FILES.
     * @param string $type The type of file ('thumbnail' or 'image').
     * @return string The path to the saved file.
     * @throws InvalidArgumentException If the file could not be saved.
     */
    public static function saveFile(array $file, string $type): string
    {
        $allowedMimeTypes = [
            'thumbnails' => ['image/jpeg', 'image/png', 'image/gif'],
            'article_images' => ['image/jpeg', 'image/png', 'image/gif'],
        ];

        $baseDir = dirname(__DIR__, 2) . '/public/uploads/';

        // ファイルの種類に応じてディレクトリを設定
        switch ($type) {
            case 'thumbnails':
                $uploadDir = $baseDir . 'thumbnails/';
                break;
            case 'article_images':
                $uploadDir = $baseDir . 'article_images/';
                break;
            default:
                throw new InvalidArgumentException('無効なファイルタイプです。');
        }
        // ファイルがアップロードされているか確認
        if (empty($file['tmp_name'])) {
            throw new InvalidArgumentException('ファイルがアップロードされていません。');
        }

        // ファイルのMIMEタイプを取得
        $mimeType = self::getMimeType($file['tmp_name']);
        // MIMEタイプを検証
        if (!in_array($mimeType, $allowedMimeTypes[$type])) {
            throw new InvalidArgumentException('無効なファイルタイプです。許可されているタイプ: ' . implode(', ', $allowedMimeTypes[$type]));
        }

        // ディレクトリが存在しない場合は作成
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $uploadFile = $uploadDir . basename($file['name']);

        if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
            throw new InvalidArgumentException('ファイルのアップロードに失敗しました。');
        }

        return '/uploads/' . $type . '/' . basename($file['name']);
    }

    /**
     * Get the MIME type of a file.
     *
     * @param string $filePath The path to the file.
     * @return string The MIME type of the file.
     * @throws InvalidArgumentException If the file path is empty.
     */
    private static function getMimeType(string $filePath): string
    {
        if (empty($filePath)) {
            throw new InvalidArgumentException('ファイルパスが空です。');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->file($filePath);
    }

    /**
     * Delete the specified file.
     *
     * @param string $filePath The path to the file to delete.
     * @throws InvalidArgumentException If the file could not be deleted.
     */
    public static function deleteFile(string $filePath): void
    {
        $fullPath = dirname(__DIR__, 2) . '/public' . $filePath;

        if (!file_exists($fullPath)) {
            throw new InvalidArgumentException('ファイルが存在しません。');
        }

        if (!unlink($fullPath)) {
            throw new InvalidArgumentException('ファイルの削除に失敗しました。');
        }
    }
}
