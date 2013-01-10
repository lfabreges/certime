<?php

/*
 * This file is part of Certime.
 *
 * Certime is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Certime is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Certime. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Certime\Repository;

use Certime\Entity\Snippet as SnippetEntity;
use Certime\Entity\Theme as ThemeEntity;

/**
 * @category Certime
 * @package  Certime_Repository
 * @author   Ludovic Fabrèges
 */
class Snippet
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * Construit une instance du dépôt.
     *
     * @param string $directory
     *
     * @return void
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    /**
     * Renvoie la liste des thèmes.
     *
     * @return array [ThemeEntity]
     */
    public function getThemes()
    {
        $themes = array();
        $currentTheme = null;

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $this->directory,
                \RecursiveDirectoryIterator::KEY_AS_FILENAME | \RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $iterator->setMaxDepth(1);

        foreach ($iterator as $fileInfo) {
            if (0 === $iterator->getDepth() && $fileInfo->isDir()) {
                $currentTheme = new ThemeEntity();
                $currentTheme->name = $fileInfo->getBasename();
                $themes[$currentTheme->name] = $currentTheme;
            } elseif (1 === $iterator->getDepth() && null !== $currentTheme
                && $fileInfo->isFile() && 'php' === $fileInfo->getExtension()
            ) {
                $snippet = new SnippetEntity();
                $snippet->name = $fileInfo->getBasename('.php');
                $snippet->path = $fileInfo->getRealpath();
                $currentTheme->snippets[$snippet->name] = $snippet;
            }
        }

        return $themes;
    }

    /**
     * Renvoie un snippet.
     *
     * @param string $themeName
     * @param string $snippetName
     *
     * @return SnippetEntity|false
     */
    public function getSnippet($themeName, $snippetName)
    {
        $themes = $this->getThemes();
        if (isset($themes[$themeName]->snippets[$snippetName])) {
            return $themes[$themeName]->snippets[$snippetName];
        }
        return false;
    }

    /**
     * Supprimer un snippet.
     *
     * @param string $themeName
     * @param string $snippetName
     *
     * @return bool
     */
    public function deleteSnippet($themeName, $snippetName)
    {
        $themes = $this->getThemes();
        if (isset($themes[$themeName]->snippets[$snippetName])) {
            return unlink($themes[$themeName]->snippets[$snippetName]->path);
        }
        return true;
    }
}
