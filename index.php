<?php

require_once('vendor/autoload.php');

$uploadDirectory = __DIR__ . '/uploads';
$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

if ($request->isMethod('POST') && ($kml = $request->request->get('kml'))) {

    file_put_contents($uploadDirectory . '/' . strftime('%Y%m%d%H%M%S') . '.kml', $kml);
    $response = new \Symfony\Component\HttpFoundation\Response('ok');
    $response->send();

} else {

    $finder = new \Symfony\Component\Finder\Finder();
    $finder->files()->in($uploadDirectory)->sortByName();

    $content = '<h1>KML-Repository</h1>';
    $content .= '<h2>Bisherige Dateien</h2>';
    $content .= '<ul>';

    foreach ($finder as $file) {
        $content .= '<li><a href="uploads/' . urlencode($file->getRelativePathName()) . '">' . htmlspecialchars($file->getRelativePathName()) . '</a></li>';
    }

    $content .= '</ul>';

    $response = new \Symfony\Component\HttpFoundation\Response();
    $response->setContent('<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>KML-Repository</title>
    </head>
    <body>' . $content . '</body>
</html>');
    $response->send();

}