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

namespace Certime\View;

/**
 * @category Certime
 * @package  Certime_View
 * @author   Ludovic Fabrèges
 */
class Standard implements ViewInterface
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * Construit une instance de la vue.
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
     * @see ViewInterface::render()
     */
    public function render($name, $layout = 'layout')
    {
        ob_start();
        include $this->directory . '/' . $name . '.phtml';
        $content = ob_get_clean();

        if (null !== $layout && $name !== $layout) {
            $this->content = $content;
            $this->render($layout, $layout);
        } else {
            echo $content;
        }

        return $this;
    }

    /**
     * Renvoie la valeur nulle lorsque l'on cherche à accéder à une propriété protégée ou inexistante.
     *
     * @param string $property
     *
     * @return null
     */
    public function __get($property)
    {
        return null;
    }
}
