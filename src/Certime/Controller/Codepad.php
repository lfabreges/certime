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

use Certime\Filter\File as FilterFile;
use Certime\Repository\Snippet as SnippetRepository;

/**
 * @category Certime
 * @package  Certime_Controller
 * @author   Ludovic Fabrèges
 */
class Codepad extends AbstractController
{
    public function indexAction()
    {
        $snippetRepository = new SnippetRepository($this->snippetDirectory);
        $this->view->themes = $snippetRepository->getThemes();
        $this->view->page = 'codepad';
        $this->view->render('codepad');
    }

    public function editAction()
    {
        $themeName = FilterFile::getAndSanitizeBasename(INPUT_GET, 'theme');
        $snippetName = FilterFile::getAndSanitizeBasename(INPUT_GET, 'snippet');

        $snippetRepository = new SnippetRepository($this->snippetDirectory);
        $snippet = $snippetRepository->getSnippet($themeName, $snippetName);

        if (false !== $snippet) {
            $this->view->theme = $themeName;
            $this->view->snippet = $snippetName;
            $this->view->code = file_get_contents($snippet->path);
        }

        $this->view->themes = $snippetRepository->getThemes();
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
        $errors = array();

        $theme = FilterFile::getAndSanitizeBasename(INPUT_GET, 'theme');
        $snippet = FilterFile::getAndSanitizeBasename(INPUT_GET, 'snippet');

        if (empty($snippet)) {
            $errors[] = 'Le nom du snippet doit être renseigné.';
        }
        if (empty($theme)) {
            $errors[] = 'Le thème doit être renseigné.';
        }

        $code = filter_input(INPUT_GET, 'code');

        if (empty($errors)) {
            if (is_dir("{$this->snippetDirectory}/{$theme}")) {
                if (false === file_put_contents("{$this->snippetDirectory}/{$theme}/{$snippet}.php", $code)) {
                    $errors[] = "Echec lors de l'enregistrement du snippet ; son nom est peut-être invalide.";
                }
            } else {
                $errors[] = 'Le thème sélectionné est invalide.';
            }
        }

        if (!empty($errors)) {
            $this->view->errors = $errors;
            $this->view->render('error');
        }
    }

    protected function filterInputSanitizeBasename()
    {
        return filter_input(
            INPUT_GET,
            'theme',
            FILTER_CALLBACK,
            array('options' => array('\\Certime\\File\\Filter', 'sanitizeBasename'))
        );
    }
}
