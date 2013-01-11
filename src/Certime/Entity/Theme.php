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

namespace Certime\Entity;

/**
 * @category Certime
 * @package  Certime_Entity
 * @author   Ludovic Fabrèges
 */
class Theme
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $snippets = array();

    /**
     * Le thème a t-il des snippets ?
     *
     * @return bool
     */
    public function hasSnippets()
    {
        return !empty($this->snippets);
    }
}
