<?php
declare(strict_types=1);

namespace App\Utils;

use InvalidArgumentException;

class FileUploader
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
        $baseDir = dirname(__DIR__, 2) . '/public/uploads/';

        // ファイルの種類に応じてディレクトリを設定
        switch ($type) {
            case 'thumbnails':
                $uploadDir = $baseDir . 'thumbnails/';
                break;
            case 'image':
                $uploadDir = $baseDir . 'images/';
                break;
            default:
                throw new InvalidArgumentException('無効なファイルタイプです。');
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
}
