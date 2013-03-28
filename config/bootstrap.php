<?php

    ini_set("display_errors", true);
    error_reporting(E_ALL | E_STRICT);
    
    setlocale(LC_ALL, array("pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese"));
    ini_set("date.timezone", "America/Sao_Paulo");

    $include_path = explode(PATH_SEPARATOR, get_include_path());
    $include_path[] = stream_resolve_include_path("..");
    $include_path[] = stream_resolve_include_path("tests");
    set_include_path(implode(PATH_SEPARATOR, array_filter($include_path)));

    spl_autoload_register(
        function($class) {
            $filename = sprintf("%s.php", str_replace("\\", DIRECTORY_SEPARATOR, $class));
            $classPath = stream_resolve_include_path($filename);

            if ($classPath !== false) {
                require_once $classPath;
            }
        }
    );


    