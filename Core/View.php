<?php

namespace Core;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = []) //oryginalna renderTemplate funkcja posluzyla jako funkcja ponizsza getTemplate.
																 // na koncu niej bylo echo $twig->render ($template, $args) teraz jest echo static:: wywolanie...
    {
		echo static::getTemplate($template, $args);
    }
	
	/**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function getTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
			
            //$loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views'); // ten zapis dziala dobrze
            //$loader = new \Twig_Loader_Filesystem('../App/Views');  ten zapis nie dziala mi z lekcji
            $twig = new \Twig\Environment($loader);
            //$twig = new \Twig_Environment($loader);  ten zapis nie dziala mi z lekcji
			
			//$twig -> addGlobal('session', $_SESSION); // adding Twig tamplates as global variable so that we can use twig methods globally within $_SESSION parameters...
			
			//$twig -> addGlobal('is_logged_in', \App\Auth::isLoggedIn()); // adding is user logged in information to twig template and removing above addGlobal for session
			
			$twig -> addGlobal('current_user', \App\Auth::getUser());  // with addition of this variable we can remove is_logged_in as we will use this instead
			
			$twig -> addGlobal('flash_messages', \App\Flash::getMessages()); // adding flash messages to twig
        }

        return $twig->render($template, $args);
    }
}
