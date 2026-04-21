<?php

namespace Maxima\Helpers;

/**
 * Builds a list of video sources: original file plus optional {prefix}_{basename} files in ext/.
 */
class VideoQualityHelper
{
    /**
     * @return array<int, array{label: string, src: string, isSource: bool, selected: bool}>
     */
    public static function getSourcesForWebPath(string $webPath): array
    {
        $webPath = self::normalizeWebPath($webPath);
        if ($webPath === '') {
            return [];
        }

        $basename = basename($webPath);
        $dir = dirname($webPath);
        $dir = str_replace('\\', '/', $dir);
        if ($dir === '.' || $dir === '/') {
            $dir = '';
        }

        $extDir = ($dir === '' ? '' : rtrim($dir, '/')) . '/ext';
        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        $extFullPath = $documentRoot . $extDir;

        $variants = [];
        if ($documentRoot !== '' && is_dir($extFullPath) && is_readable($extFullPath)) {
            $names = scandir($extFullPath);
            if ($names !== false) {
                $pattern = '/^(.+)_' . preg_quote($basename, '/') . '$/';
                foreach ($names as $fileName) {
                    if ($fileName === '.' || $fileName === '..') {
                        continue;
                    }
                    $fullFile = $extFullPath . '/' . $fileName;
                    if (!is_file($fullFile)) {
                        continue;
                    }
                    if (preg_match($pattern, $fileName, $m)) {
                        $prefix = $m[1];
                        $variants[] = [
                            'prefix' => $prefix,
                            'label' => self::formatQualityLabel($prefix),
                            'src' => $extDir . '/' . $fileName,
                            'isSource' => false,
                        ];
                    }
                }
            }
        }

        usort($variants, static function (array $a, array $b): int {
            return self::comparePrefixForSort((string)$a['prefix'], (string)$b['prefix']);
        });

        $source = [
            'label' => '1080p',
            'src' => $webPath,
            'isSource' => true,
            'selected' => true,
        ];

        if ($variants === []) {
            return [$source];
        }

        $out = [];
        foreach ($variants as $row) {
            unset($row['prefix']);
            $row['selected'] = false;
            $out[] = $row;
        }
        $out[] = $source;

        return $out;
    }

    /**
     * Lists only regular files in a PATH_TO_VIDEO directory (excludes subdirectories such as `ext/`).
     *
     * @return list<string> Paths in the form used by templates: $videoPath . $fileName
     */
    public static function listRegularFilesInVideoDir(string $videoPath): array
    {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        if ($documentRoot === '' || $videoPath === '') {
            return [];
        }
        $dirAbs = $documentRoot . $videoPath;
        $arFileNames = @scandir($dirAbs, SCANDIR_SORT_ASCENDING);
        if ($arFileNames === false) {
            return [];
        }
        $arFiles = [];
        foreach ($arFileNames as $fileName) {
            if ($fileName === '.' || $fileName === '..') {
                continue;
            }
            if (!is_file($dirAbs . $fileName)) {
                continue;
            }
            $arFiles[] = $videoPath . $fileName;
        }

        return $arFiles;
    }

    public static function normalizeWebPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        return $path;
    }

    private static function formatQualityLabel(string $prefix): string
    {
        if ($prefix !== '' && ctype_digit($prefix)) {
            return $prefix . 'p';
        }

        return $prefix;
    }

    /**
     * Numeric prefixes first (ascending), then non-numeric lexicographically.
     */
    private static function comparePrefixForSort(string $aPrefix, string $bPrefix): int
    {
        $aNum = ($aPrefix !== '' && ctype_digit($aPrefix)) ? (int)$aPrefix : null;
        $bNum = ($bPrefix !== '' && ctype_digit($bPrefix)) ? (int)$bPrefix : null;
        if ($aNum !== null && $bNum !== null) {
            return $aNum <=> $bNum;
        }
        if ($aNum !== null) {
            return -1;
        }
        if ($bNum !== null) {
            return 1;
        }

        return strcmp($aPrefix, $bPrefix);
    }
}
