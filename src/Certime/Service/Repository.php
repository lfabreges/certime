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

namespace Certime\Service;

use Certime\Entity\Snippet as SnippetEntity;
use Certime\Entity\Theme as ThemeEntity;

/**
 * @category Certime
 * @package  Certime_Service
 * @author   Ludovic Fabrèges
 */
class Repository
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @var array
     */
    protected $themes;

    /**
     * Construit une instance du service.
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
     * @return array
     */
    public function getThemes()
    {
        if (null !== $this->themes) {
            return $this->themes;
        }

        $this->themes = array();
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
                $this->themes[$currentTheme->name] = $currentTheme;
            } elseif (1 === $iterator->getDepth() && null !== $currentTheme
                && $fileInfo->isFile() && 'php' === $fileInfo->getExtension()
            ) {
                $snippet = new SnippetEntity();
                $snippet->name = $fileInfo->getBasename('.php');
                $snippet->path = $fileInfo->getRealpath();
                $currentTheme->snippets[$snippet->name] = $snippet;
            }
        }

        return $this->themes;
    }

    /**
     * Le dépôt est-il vide ?
     *
     * @return bool
     */
    public function isEmpty()
    {
        foreach ($this->getThemes() as $theme) {
            if ($theme->hasSnippets()) {
                return true;
            }
        }
        return false;
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
     * Enregistre un snippet.
     *
     * @param string $themeName
     * @param string $snippetName
     * @param string $code
     *
     * @return self
     *
     * @throws Exception\DomainException  si le thème est invalide.
     * @throws Exception\RuntimeException si l'enregistrement du snippet est un échec.
     */
    public function saveSnippet($themeName, $snippetName, $code)
    {
        if (!is_dir("{$this->directory}/{$themeName}")) {
            throw new Exception\DomainException('Le thème est invalide');
        }
        if (false === file_put_contents("{$this->directory}/{$themeName}/{$snippetName}.php", $code)) {
            throw new Exception\RuntimeException("Echec lors de l'enregistrement du snippet");
        }
        return $this;
    }

    /**
     * Supprime un snippet.
     *
     * @param string $themeName
     * @param string $snippetName
     *
     * @return self
     *
     * @throws Exception\RuntimeException si la suppression du snippet est un échec.
     */
    public function deleteSnippet($themeName, $snippetName)
    {
        $themes = $this->getThemes();
        if (isset($themes[$themeName]->snippets[$snippetName])) {
            if (false === unlink($themes[$themeName]->snippets[$snippetName]->path)) {
                throw new Exception\RuntimeException('Echec lors de la suppression du snippet');
            }
        }
        return $this;
    }
}
