<?php

namespace PatternLab\PatternData;

use PatternLab\Config;

abstract class PatternLocator
{
    public static function locateHead($breadcrumb = null)
    {
        return static::locate('head', $breadcrumb);
    }

    public static function locateFoot($breadcrumb = null)
    {
        return static::locate('foot', $breadcrumb);
    }

    private static function locate($type, $breadcrumb = null)
    {
        $patternExtension = Config::getOption("patternExtension");
        $metaDir          = Config::getOption("metaDir");

        $patternPaths = [];

        if ($breadcrumb) {
            foreach ($breadcrumb as $index => $value) {
                $breadcrumb[$index] = str_replace(' ', '-', $value);
            }

            $patternPaths[] = $metaDir . $breadcrumb['patternType'] . DIRECTORY_SEPARATOR . $breadcrumb['patternSubtype'] . DIRECTORY_SEPARATOR . "_{$type}." . $patternExtension;
            $patternPaths[] = $metaDir . $breadcrumb['patternType'] . DIRECTORY_SEPARATOR . "_{$type}." . $patternExtension;
        }

        switch ($type) {
            case 'head':
                $patternPaths[] = $metaDir . "_00-head." . $patternExtension;
                break;

            case 'foot':
                $patternPaths[] = $metaDir . "_01-foot." . $patternExtension;
                break;
        }

        foreach ($patternPaths as $patternPath) {
            if (file_exists($patternPath)) {
                return file_get_contents($patternPath);
            }
        }

        return '';
    }
}