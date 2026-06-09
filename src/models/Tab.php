<?php
/**
 * Data access for the `tabs` table. Each tab drives one slider in the section.
 */
declare(strict_types=1);

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/Slide.php';

class Tab
{
    /** All tabs ordered for display. */
    public static function all(): array
    {
        return db()->query(
            'SELECT * FROM tabs ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    }

    /** A single tab by id, or null. */
    public static function find(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM tabs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Insert a tab; returns the new id. */
    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO tabs (name, slug, icon, sort_order)
             VALUES (:name, :slug, :icon, :sort_order)'
        );
        $stmt->execute(self::params($data));
        return (int) db()->lastInsertId();
    }

    /** Update an existing tab. */
    public static function update(int $id, array $data): void
    {
        $params = self::params($data);
        $params['id'] = $id;
        $stmt = db()->prepare(
            'UPDATE tabs
                SET name = :name, slug = :slug, icon = :icon, sort_order = :sort_order
              WHERE id = :id'
        );
        $stmt->execute($params);
    }

    /**
     * Delete a tab. Its slides cascade away via the FK, so we collect their
     * uploaded images first and prune the now-unreferenced files afterwards.
     */
    public static function delete(int $id): void
    {
        $stmt = db()->prepare('SELECT image_path FROM slides WHERE tab_id = ?');
        $stmt->execute([$id]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $del = db()->prepare('DELETE FROM tabs WHERE id = ?');
        $del->execute([$id]);

        foreach (array_unique($images) as $image) {
            Slide::pruneImage((string) $image);
        }
    }

    /** Normalise raw form input into bound parameters. */
    private static function params(array $data): array
    {
        $name = trim((string) ($data['name'] ?? ''));
        return [
            'name'       => $name,
            'slug'       => self::slugify($data['slug'] ?? $name),
            'icon'       => trim((string) ($data['icon'] ?? '')),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
        ];
    }

    private static function slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        return trim($value, '-');
    }
}
