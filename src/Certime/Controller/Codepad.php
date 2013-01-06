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

namespace Certime\Controller;

use Certime\Repository\Theme as ThemeRepository;

/**
 * @category Certime
 * @package  Certime_Controller
 * @author   Ludovic Fabrèges
 */
class Codepad extends AbstractController
{
    /**
     * @var string
     */
    protected $directory;

    public function indexAction()
    {
        $themeRepository = new ThemeRepository($this->directory);
        $this->view->themes = $themeRepository->getThemes();
        $this->view->page = 'codepad';
        $this->view->render('codepad');
    }

    public function evalAction()
    {
        $this->view->setLayout(null);
        ob_start();
        eval('?>' . filter_input(INPUT_GET, 'code'));
        $this->view->content = ob_get_clean();
        $this->view->render('content');
    }

    public function saveAction()
    {
        $theme = filter_input(
            INPUT_GET,
            'theme',
            FILTER_CALLBACK,
            array('options' => array('\\Certime\\File\\Filter', 'sanitizeBasename'))
        );
        $snippet = filter_input(
            INPUT_GET,
            'snippet',
            FILTER_CALLBACK,
            array('options' => array('\\Certime\\File\\Filter', 'sanitizeBasename'))
        );
        $code = filter_input(INPUT_GET, 'code');

        if (is_dir("{$this->directory}/{$theme}")) {
            file_put_contents("{$this->directory}/{$theme}/{$snippet}.php", $code);
        }
    }
}
