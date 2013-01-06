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
     * Renvoie la liste des snippets du dépôt.
     *
     * @return \RecursiveIteratorIterator
     */
    public function getTree()
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator(
                    $this->directory,
                    \RecursiveDirectoryIterator::KEY_AS_FILENAME | \RecursiveDirectoryIterator::CURRENT_AS_FILEINFO
                ),
                function ($current, $key, $iterator) {
                    return $current->isDir() || 'php' === $current->getExtension();
                }
            ),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        $iterator->setMaxDepth(1);
        return $iterator;
    }
}
