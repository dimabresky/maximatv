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
        $parentWebDir = $dir === '' ? '' : $dir;
        $siblingBasenames = self::listSiblingBasenamesInParentDir($documentRoot, $parentWebDir);

        $variants = [];
        if ($documentRoot !== '' && is_dir($extFullPath) && is_readable($extFullPath)) {
            $names = scandir($extFullPath);
            if ($names !== false) {
                $patternFallback = '/^(.+)_' . preg_quote($basename, '/') . '$/';
                foreach ($names as $fileName) {
                    if ($fileName === '.' || $fileName === '..') {
                        continue;
                    }
                    $fullFile = $extFullPath . '/' . $fileName;
                    if (!is_file($fullFile)) {
                        continue;
                    }
                    $ownerBasename = self::findOwnerBasenameForExtFile($fileName, $siblingBasenames, $patternFallback, $basename);
                    if ($ownerBasename === null || $ownerBasename !== $basename) {
                        continue;
                    }
                    $suffix = '_' . $ownerBasename;
                    $sufLen = strlen($suffix);
                    if (strlen($fileName) <= $sufLen) {
                        continue;
                    }
                    $prefix = substr($fileName, 0, -$sufLen);
                    if ($prefix === '') {
                        continue;
                    }
                    $variants[] = [
                        'prefix' => $prefix,
                        'label' => self::formatQualityLabel($prefix),
                        'src' => $extDir . '/' . $fileName,
                        'isSource' => false,
                    ];
                }
            }
        }

        usort($variants, static function (array $a, array $b): int {
            return self::comparePrefixForSort((string)$a['prefix'], (string)$b['prefix']);
        });

        $sourceLabel = '1080p';
        foreach ($variants as $v) {
            if (($v['label'] ?? '') === '1080p') {
                $sourceLabel = 'Оригинал';
                break;
            }
        }

        $source = [
            'label' => $sourceLabel,
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
        $webDir = rtrim(str_replace('\\', '/', self::normalizeWebPath($videoPath)), '/');
        if ($webDir === '') {
            return [];
        }
        $dirAbs = rtrim($documentRoot, '/\\') . $webDir;
        $arFileNames = @scandir($dirAbs, SCANDIR_SORT_ASCENDING);
        if ($arFileNames === false) {
            return [];
        }
        $arFiles = [];
        foreach ($arFileNames as $fileName) {
            if ($fileName === '.' || $fileName === '..') {
                continue;
            }
            $fullPath = $dirAbs . '/' . $fileName;
            if (!is_file($fullPath)) {
                continue;
            }
            $arFiles[] = $webDir . '/' . $fileName;
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

    /**
     * Basenames of regular files in the same web directory as the main video (excludes subdirectories like `ext/`).
     *
     * @return list<string>
     */
    private static function listSiblingBasenamesInParentDir(string $documentRoot, string $parentWebDir): array
    {
        if ($documentRoot === '') {
            return [];
        }
        $dirAbs = rtrim($documentRoot, "/\\") . $parentWebDir;
        if (!is_dir($dirAbs) || !is_readable($dirAbs)) {
            return [];
        }
        $names = @scandir($dirAbs);
        if ($names === false) {
            return [];
        }
        $out = [];
        foreach ($names as $n) {
            if ($n === '.' || $n === '..') {
                continue;
            }
            if (!is_file($dirAbs . '/' . $n)) {
                continue;
            }
            $out[] = $n;
        }

        return $out;
    }

    /**
     * Resolves which sibling video basename an ext/ file belongs to (longest matching suffix wins).
     */
    private static function findOwnerBasenameForExtFile(
        string $fileName,
        array $siblingBasenames,
        string $patternFallback,
        string $basename
    ): ?string {
        if ($siblingBasenames !== []) {
            $best = null;
            $bestLen = 0;
            foreach ($siblingBasenames as $sib) {
                $suf = '_' . $sib;
                $ll = strlen($suf);
                if (strlen($fileName) <= $ll) {
                    continue;
                }
                if (substr($fileName, -$ll) === $suf && strlen($sib) > $bestLen) {
                    $bestLen = strlen($sib);
                    $best = $sib;
                }
            }

            return $best;
        }
        if (preg_match($patternFallback, $fileName)) {
            return $basename;
        }

        return null;
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
