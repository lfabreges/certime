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

/**
 * @category Certime
 * @package  Certime_Service
 * @author   Ludovic Fabrèges
 */
class Codepad
{
    /**
     * @var string
     */
    protected $tmpDirectory;

    /**
     * Construit une instance du service.
     *
     * @param string $tmpDirectory
     *
     * @return void
     */
    public function __construct($tmpDirectory)
    {
        $this->tmpDirectory = $tmpDirectory;
    }

    /**
     * Exécute une chaîne comme un code PHP et renvoie le résultat.
     *
     * @param string $code
     *
     * @return string
     *
     * @throws Exception\RuntimeException si l'exécution de la chaîne comme un code PHP est un échec.
     */
    public function evalCode($code)
    {
        if (false !== ($filename = tempnam($this->tmpDirectory, 'sni'))) {
            register_shutdown_function('unlink', $filename);
            if (false !== file_put_contents($filename, $code)) {
                ob_start();
                include $filename;
                return ob_get_clean();
            }
        }
        throw new Exception\RuntimeException("Echec de l'exécution de la chaîne comme un code PHP");
    }
}
