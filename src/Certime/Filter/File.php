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

namespace Certime\Filter;

/**
 * @category Certime
 * @package  Certime_Filter
 * @author   Ludovic Fabrèges
 */
class File
{
    /**
     * Renvoie et désinfecte le nom du fichier dans un chemin contenu dans une variable externe.
     *
     * @param int $type
     * @param string $variableName
     *
     * @return string
     */
    public static function getAndSanitizeBasename($type, $variableName)
    {
        return filter_input(
            $type,
            $variableName,
            FILTER_CALLBACK,
            array(
                'options' => function($path) {
                    return str_replace(
                        array('/', '\\', '?', '%', '*', ':', '|', '"', '<', '>'),
                        '_',
                        basename($path)
                    );
                }
            )
        );
    }
}
