<?php
/**
 * Data access for the `slides` table. Slides belong to a tab and render in
 * Column 2 (content) + Column 3 (image) of the section.
 */
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';

class Slide
{
    /** All slides for a tab, ordered for the slider. */
    public static function forTab(int $tabId): array
    {
        $stmt = db()->prepare(
            'SELECT * FROM slides WHERE tab_id = ? ORDER BY sort_order ASC, id ASC'
        );
        $stmt->execute([$tabId]);
        return $stmt->fetchAll();
    }

    /** A single slide by id, or null. */
    public static function find(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM slides WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Insert a slide; returns the new id. */
    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO slides
                (tab_id, category_label, title, link_text, link_url, image_path, sort_order)
             VALUES
                (:tab_id, :category_label, :title, :link_text, :link_url, :image_path, :sort_order)'
        );
        $stmt->execute(self::params($data));
        return (int) db()->lastInsertId();
    }

    /** Update an existing slide. */
    public static function update(int $id, array $data): void
    {
        $params = self::params($data);
        $params['id'] = $id;
        $stmt = db()->prepare(
            'UPDATE slides SET
                tab_id = :tab_id,
                category_label = :category_label,
                title = :title,
                link_text = :link_text,
                link_url = :link_url,
                image_path = :image_path,
                sort_order = :sort_order
             WHERE id = :id'
        );
        $stmt->execute($params);
    }

    /** Delete a slide, pruning its image file if nothing else uses it. */
    public static function delete(int $id): void
    {
        $slide = self::find($id);
        $stmt = db()->prepare('DELETE FROM slides WHERE id = ?');
        $stmt->execute([$id]);
        if ($slide) {
            self::pruneImage($slide['image_path']);
        }
    }

    /**
     * Remove an uploaded image file when no slide references it any more.
     * Only touches files under uploads/, never the seeded design images, and
     * never deletes a file still referenced by another slide.
     */
    public static function pruneImage(string $imagePath, int $exceptId = 0): void
    {
        $imagePath = trim($imagePath);
        if ($imagePath === '' || !str_starts_with($imagePath, 'uploads/')) {
            return;
        }

        // Never delete the seeded images that ship with the design.
        $seeds = ['uploads/DL-Learning-1.jpg', 'uploads/DL-Technology.jpg', 'uploads/DL-Communication.jpg'];
        if (in_array($imagePath, $seeds, true)) {
            return;
        }

        $stmt = db()->prepare('SELECT COUNT(*) FROM slides WHERE image_path = ? AND id <> ?');
        $stmt->execute([$imagePath, $exceptId]);
        if ((int) $stmt->fetchColumn() > 0) {
            return; // still referenced elsewhere
        }

        $uploads = realpath(dirname(__DIR__, 2) . '/public/uploads');
        $file = realpath(dirname(__DIR__, 2) . '/public/' . $imagePath);
        if ($uploads && $file && str_starts_with($file, $uploads . DIRECTORY_SEPARATOR)) {
            @unlink($file);
        }
    }

    /** Normalise raw form input into bound parameters. */
    private static function params(array $data): array
    {
        return [
            'tab_id'         => (int) ($data['tab_id'] ?? 0),
            'category_label' => trim((string) ($data['category_label'] ?? '')),
            'title'          => trim((string) ($data['title'] ?? '')),
            'link_text'      => trim((string) ($data['link_text'] ?? '')) ?: 'Learn More',
            'link_url'       => trim((string) ($data['link_url'] ?? '')) ?: '#',
            'image_path'     => trim((string) ($data['image_path'] ?? '')),
            'sort_order'     => (int) ($data['sort_order'] ?? 0),
        ];
    }
}
